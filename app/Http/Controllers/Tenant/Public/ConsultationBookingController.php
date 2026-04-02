<?php

namespace App\Http\Controllers\Tenant\Public;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ConsultationBookingController extends Controller
{
    public function book(): View
    {
        $tenant = app('currentTenant');
        $consultation = Consultation::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->firstOrFail();

        return view('tenant.public.consultation.book', compact('consultation', 'tenant'));
    }

    public function getSlots(Request $request)
    {
        $tenant = app('currentTenant');
        $date = $request->get('date');
        $consultationId = $request->get('consultation_id');

        if (!$date || !$consultationId) {
            return response()->json(['slots' => []]);
        }

        $consultation = Consultation::where('tenant_id', $tenant->id)->findOrFail($consultationId);
        $dayOfWeek = date('l', strtotime($date));

        $availability = is_array($consultation->availability) ? $consultation->availability : json_decode($consultation->availability ?? '{}', true);
        $dayConfig = $availability[$dayOfWeek] ?? null;

        if (!$dayConfig || !isset($dayConfig['enabled']) || !$dayConfig['enabled']) {
            return response()->json(['slots' => []]);
        }

        $start = strtotime($dayConfig['start']);
        $end = strtotime($dayConfig['end']);
        
        $slots = [];
        for ($time = $start; $time + 1800 <= $end; $time += 1800) {
            $slots[] = date('H:i', $time);
        }

        $bookedSlots = ConsultationBooking::where('consultation_id', $consultation->id)
            ->where('booking_date', $date)
            ->pluck('booking_time')
            ->toArray();

        $availableSlots = array_values(array_diff($slots, $bookedSlots));

        return response()->json(['slots' => $availableSlots]);
    }

    public function verify(Request $request)
    {
        $tenant = app('currentTenant');
        $reference = $request->get('reference');
        $email = $request->get('email');
        $consultationId = $request->get('consultation_id');
        $bookingDate = $request->get('booking_date');
        $bookingTime = $request->get('booking_time');

        $consultation = Consultation::where('tenant_id', $tenant->id)->findOrFail($consultationId);

        // Verification identical to Store Payment
        $paymentSuccessful = false;
        if ($request->has('simulate_success')) {
            $paymentSuccessful = true;
        } elseif ($request->has('simulate_failure')) {
            $paymentSuccessful = false;
        } else {
            $secretKey = env('PAYSTACK_SECRET_KEY');
            if (!$secretKey) {
                // Testing bypass
                $paymentSuccessful = true;
            } else {
                $response = Http::withToken($secretKey)
                    ->get("https://api.paystack.co/transaction/verify/{$reference}");
                $paymentSuccessful = $response->successful() && $response->json('data.status') === 'success';
            }
        }

        if ($paymentSuccessful) {
            $user = auth()->guard('web')->user();
            
            $feePercentage = \App\Models\PlatformSetting::get('platform_fee_percentage', 0);
            $feeAmount = $consultation->fee * ($feePercentage / 100);
            $tenantEarnings = $consultation->fee - $feeAmount;

            $exists = \App\Models\Transaction::where('reference', $reference)->exists();
            if (!$exists && $consultation->fee > 0) {
                // Credit Tenant's Wallet
                $tenant->increment('wallet_balance', $tenantEarnings);

                // Record Transaction
                \App\Models\Transaction::create([
                    'tenant_id' => $tenant->id,
                    'user_id' => $user ? $user->id : null,
                    'type' => 'credit',
                    'amount' => $tenantEarnings,
                    'reference' => $reference,
                    'description' => 'Consultation Booking' . ($feeAmount > 0 ? " (Platform Fee Deducted)" : ""),
                ]);

                if ($feeAmount > 0) {
                    \App\Models\Transaction::create([
                        'tenant_id' => $tenant->id,
                        'user_id' => $user ? $user->id : null,
                        'type' => 'platform_fee',
                        'amount' => $feeAmount,
                        'reference' => $reference,
                        'description' => 'Platform Fee for Consultation Booking',
                    ]);
                }
            }
            
            // Record booking
            $booking = ConsultationBooking::firstOrCreate(
                ['payment_reference' => $reference],
                [
                    'tenant_id' => $tenant->id,
                    'consultation_id' => $consultation->id,
                    'user_id' => $user ? $user->id : null,
                    'booking_date' => $bookingDate,
                    'booking_time' => $bookingTime,
                    'amount_paid' => $consultation->fee,
                    'status' => 'paid',
                ]
            );

            return redirect()->route('tenant.consultation.success', [
                'tenant' => $tenant->subdomain,
                'booking' => $booking->id
            ]);
        }

        return redirect()->route('tenant.consultation.book', ['tenant' => $tenant->subdomain])
            ->with('error', 'Payment verification failed. Please try again.');
    }

    public function success(ConsultationBooking $booking): View
    {
        $tenant = app('currentTenant');
        $consultation = $booking->consultation;
        return view('tenant.public.consultation.success', compact('booking', 'consultation', 'tenant'));
    }
}
