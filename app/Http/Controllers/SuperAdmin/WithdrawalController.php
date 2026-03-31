<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    public function index(): View
    {
        // Get all withdrawal transactions, order by latest
        $withdrawals = Transaction::where('type', 'withdrawal')
            ->with('tenant')
            ->latest()
            ->paginate(15);
            
        return view('superadmin.withdrawals.index', compact('withdrawals'));
    }

    public function approve(Transaction $transaction): RedirectResponse
    {
        if ($transaction->type !== 'withdrawal') {
            abort(400, 'Invalid transaction type.');
        }

        $transaction->update(['status' => 'completed']);

        return back()->with('success', 'Withdrawal request marked as completed/paid.');
    }
}
