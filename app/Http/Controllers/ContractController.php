<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function create()
    {
        return view('Contract and Permit.make-contract');

    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'contract_title' => 'required|string|max:255',
        'client_name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'equipment_type' => 'required|string',
        'payment_type' => 'required|string',
        'contract_details' => 'nullable|string',
    ]);

    // 🔵 Save the contract
    $contract = Contract::create($validated);

    // 🔵 Redirect with session data
    return redirect()
        ->route('make-contract')
        ->with('success', 'Contract saved successfully!')
        ->with('new_contract', $contract);
}
}
