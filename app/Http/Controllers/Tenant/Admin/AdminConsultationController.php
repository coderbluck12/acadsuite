<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ConsultationCallLinkMail;
use App\Models\Consultation;
use App\Models\ConsultationBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AdminConsultationController extends Controller
{
    public function edit(): View
    {
        $user = auth()->guard('web')->user();
        $consultation = Consultation::firstOrCreate(
            ['user_id' => $user->id],
            [
                'tenant_id' => app('currentTenant')->id,
                'fee' => 0,
                'availability' => [
                    'Monday' => ['enabled' => false, 'start' => '09:00', 'end' => '17:00'],
                    'Tuesday' => ['enabled' => false, 'start' => '09:00', 'end' => '17:00'],
                    'Wednesday' => ['enabled' => false, 'start' => '09:00', 'end' => '17:00'],
                    'Thursday' => ['enabled' => false, 'start' => '09:00', 'end' => '17:00'],
                    'Friday' => ['enabled' => false, 'start' => '09:00', 'end' => '17:00'],
                    'Saturday' => ['enabled' => false, 'start' => '10:00', 'end' => '14:00'],
                    'Sunday' => ['enabled' => false, 'start' => '10:00', 'end' => '14:00'],
                ],
                'instructions' => '',
                'is_active' => false,
            ]
        );

        return view('tenant.admin.consultation.edit', compact('consultation'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->guard('web')->user();
        $consultation = Consultation::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'fee' => 'required|numeric|min:0',
            'availability' => 'nullable|array',
            'instructions' => 'nullable|string|max:2000',
        ]);

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $submitted = $request->input('availability', []);
        $availability = [];

        foreach ($days as $day) {
            $dayData = $submitted[$day] ?? [];
            $availability[$day] = [
                'enabled' => isset($dayData['enabled']) && $dayData['enabled'] == 1,
                'start'   => $dayData['start'] ?? '09:00',
                'end'     => $dayData['end'] ?? '17:00',
            ];
        }

        $consultation->update([
            'fee'          => $validated['fee'],
            'availability' => $availability,
            'instructions' => $validated['instructions'] ?? '',
            'is_active'    => $request->has('is_active'),
        ]);

        return back()->with('success', 'Consultation settings updated successfully.');
    }

    public function bookings(Request $request): View
    {
        $tenant = app('currentTenant');
        $consultation = Consultation::where('tenant_id', $tenant->id)->firstOrFail();

        $query = ConsultationBooking::where('consultation_id', $consultation->id)
            ->with('user')
            ->latest('booking_date');

        if ($request->filled('date')) {
            $query->where('booking_date', $request->date);
        }

        $bookings     = $query->paginate(15);
        $todayCount   = ConsultationBooking::where('consultation_id', $consultation->id)
            ->where('booking_date', today()->toDateString())->count();
        $upcomingCount = ConsultationBooking::where('consultation_id', $consultation->id)
            ->whereBetween('booking_date', [today()->toDateString(), today()->addDays(7)->toDateString()])->count();

        return view('tenant.admin.consultation.bookings', compact('bookings', 'tenant', 'todayCount', 'upcomingCount'));
    }

    public function sendLink(Request $request): RedirectResponse
    {
        $request->validate([
            'booking_id' => 'required|integer',
            'call_link'  => 'required|url',
            'message'    => 'nullable|string|max:1000',
        ]);

        $tenant  = app('currentTenant');
        $booking = ConsultationBooking::with('user')->findOrFail($request->booking_id);

        if (!$booking->user || !$booking->user->email) {
            return back()->with('error', 'No email address found for this booking.');
        }

        Mail::to($booking->user->email)->send(new ConsultationCallLinkMail(
            studentName:  $booking->user->name,
            callLink:     $request->call_link,
            bookingDate:  $booking->booking_date ? Carbon::parse($booking->booking_date)->format('l, jS F Y') : '—',
            bookingTime:  $booking->booking_time ?? '—',
            academicName: $tenant->owner_name,
            extraMessage: $request->message ?? '',
        ));

        return back()->with('success', 'Call link sent to ' . $booking->user->email . ' successfully.');
    }
}
