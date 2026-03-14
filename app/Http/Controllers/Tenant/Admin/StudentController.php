<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        $students = User::where('role', 'student')->latest()->paginate(20);
        return view('tenant.admin.students', compact('students'));
    }

    public function approve(User $user): RedirectResponse
    {
        $user->update(['status' => 'approved']);
        return back()->with('success', "{$user->name} has been approved.");
    }

    public function reject(User $user): RedirectResponse
    {
        $user->update(['status' => 'rejected']);
        return back()->with('success', "{$user->name} has been rejected.");
    }
}
