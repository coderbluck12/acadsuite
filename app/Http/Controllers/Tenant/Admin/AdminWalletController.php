<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminWalletController extends Controller
{
    public function index(): View
    {
        $tenant = app('currentTenant');
        $transactions = Transaction::where('tenant_id', $tenant->id)->latest()->paginate(15);
        return view('tenant.admin.wallet.index', compact('tenant', 'transactions'));
    }

    public function withdraw(Request $request): RedirectResponse
    {
        $tenant = app('currentTenant');
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100|max:' . $tenant->wallet_balance,
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
        ]);

        // Deduct from wallet instantly
        $tenant->update([
            'wallet_balance' => $tenant->wallet_balance - $validated['amount'],
            // Optionally update bank details for future reference
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'],
        ]);

        // Record withdrawal transaction as pending for manual approval
        Transaction::create([
            'tenant_id' => $tenant->id,
            'type' => 'withdrawal',
            'amount' => $validated['amount'],
            'status' => 'pending',
            'description' => 'Withdrawal request to ' . $validated['bank_name'] . ' - ' . $validated['account_number']
        ]);

        return back()->with('success', 'Withdrawal request submitted successfully.');
    }
}
