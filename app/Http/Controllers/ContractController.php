<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{

public function index()
{
    $contracts = Contract::latest()->get();
    return view('contract and permit.contract-management', compact('contracts'));
}
    public function create()
    {
        return view('contract and permit.make-contract');
    }

    public function store(Request $request)
    {
        // ✅ Validate request
        $validated = $request->validate([
            'company_name'     => 'required|string|max:255',
            'client_name'      => 'required|string|max:255',
            'client_email'     => 'required|email|max:255',
            'client_number'    => 'required|string|max:20',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'equipment_type'   => 'required|string',
            'payment_type'     => 'required|string',
            'contract_details' => 'nullable|string',
        ]);

        // ✅ Save to database
        $contract = Contract::create($validated);

        // ✅ Redirect with success message
        return redirect()
            ->route('make-contract')
            ->with('success', 'Contract saved successfully!')
            ->with('new_contract', $contract);
    }
}
