<?php

namespace App\Http\Controllers\Tenant\Student;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentNotificationController extends Controller
{
    public function index(): View
    {
        $user          = Auth::guard('web')->user();
        $notifications = Notification::where('user_id', $user->id)->latest()->paginate(15);
        return view('tenant.student.notifications', compact('notifications'));
    }

    public function markRead(Notification $notification): RedirectResponse
    {
        $notification->update(['read_at' => now()]);
        return back();
    }
}
