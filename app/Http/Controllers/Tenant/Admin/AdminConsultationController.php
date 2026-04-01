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
                'calendly_link' => '',
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
            'calendly_link' => 'required|url',
            'instructions' => 'nullable|string|max:2000',
        ]);

        $consultation->update([
            'fee' => $validated['fee'],
            'calendly_link' => $validated['calendly_link'],
            'instructions' => $validated['instructions'] ?? '',
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Consultation settings updated successfully.');
    }
}
