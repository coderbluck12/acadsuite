<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

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

        $consultation->update([
            'fee' => $validated['fee'],
            'availability' => $validated['availability'] ?? [],
            'instructions' => $validated['instructions'] ?? '',
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Consultation settings updated successfully.');
    }
}
