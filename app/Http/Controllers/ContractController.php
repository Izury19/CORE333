<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ContractController extends Controller
{
    // For make-contract.blade.php
    public function index()
    {
        $contracts = Contract::orderBy('created_at', 'desc')->paginate(20);
        return view('Contract and Permit.make-contract', compact('contracts'));
    }

    // Store contract with auto-generated contract number
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_type' => 'required|string|max:255',
            'counterparty' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_number' => 'nullable|string|max:255',
            'effective_date' => 'required|date',
            'expiration_date' => 'nullable|date|after_or_equal:effective_date',
            'total_amount' => 'required|numeric|min:0',
            'payment_type' => 'required|string',
            'equipment_type' => 'nullable|string',
            'contract_details' => 'nullable|string',
        ]);

        // Generate UNIQUE contract number using timestamp + random
        $year = now()->format('Y');
        $timestamp = now()->format('His'); // HoursMinutesSeconds
        $random = rand(100, 999);
        $contractNumber = "CT-{$year}-{$timestamp}-{$random}";

        // Save locally
        $contract = Contract::create([
            'contract_number' => $contractNumber,
            'contract_type' => $validated['contract_type'],
            'company_name' => $validated['counterparty'],
            'client_email' => $validated['contact_email'] ?? 'N/A',
            'client_number' => $validated['contact_number'] ?? 'N/A',
            'start_date' => $validated['effective_date'],
            'end_date' => $validated['expiration_date'],
            'equipment_type' => $validated['equipment_type'] ?? 'N/A',
            'payment_type' => $validated['payment_type'] ?? 'N/A',
            'contract_details' => $validated['contract_details'] ?? 'No details provided.',
            'total_amount' => $validated['total_amount'] ?? 0.00,
            'status' => 'pending',
            'submitted_by' => auth()->id(),
        ]);

        // 🚀 Send to Legal Management Module via API
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://admin.cranecali-ms.com/contracts', [
                'title' => $validated['contract_type'] . ' Agreement with ' . $validated['counterparty'],
                'description' => $validated['contract_details'],
                'party_a' => 'Cranecali MS Inc.',
                'party_b' => $validated['counterparty'],
                'start_date' => $validated['effective_date'],
                'end_date' => $validated['expiration_date'],
                'status' => 'Pending',
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['contract']['id'])) {
                    $contract->update(['legal_reference' => $responseData['contract']['id']]);
                }
            } else {
                \Log::error('Legal API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Legal API Exception', ['error' => $e->getMessage()]);
        }

        return redirect()->route('contract.management')
            ->with('success', "✅ Contract {$contractNumber} submitted to Legal Management Module. Awaiting approval from Legal Team.");
    }

    // For manage-permits.blade.php
    public function permitsIndex()
    {
        // Show only permit records (filter by contract_type)
        $permits = Contract::whereIn('contract_type', [
            'construction',
            'oversize_vehicle', 
            'tollway_pass',
            'roadworthiness',
            'environmental'
        ])->orderBy('created_at', 'desc')->paginate(20);
        
        return view('Contract and Permit.manage-permits', compact('permits'));
    }

    public function storePermit(Request $request)
    {
        $validated = $request->validate([
            'permit_number' => 'required|string|max:255',
            'issuing_authority' => 'required|string|max:255',
            'permit_type' => 'required|string',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'permit_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $documentPath = null;
        if ($request->hasFile('permit_document')) {
            $documentPath = $request->file('permit_document')->store('permits', 'public');
        }

        $permitNumber = $validated['permit_number'];

        Contract::create([
            'contract_number' => $permitNumber,
            'contract_type' => $validated['permit_type'],
            'company_name' => $validated['issuing_authority'],
            'start_date' => $validated['issue_date'],
            'end_date' => $validated['expiry_date'],
            'contract_details' => 'Government permit for crane and trucking operations',
            'status' => 'pending',
            'document_path' => $documentPath,
        ]);

        return redirect()->route('manage-permits')
            ->with('success', '✅ Permit uploaded successfully for compliance tracking!');
    }

    // 🔥 NEW: Webhook receiver for contract status updates
    public function updateContractStatus(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|string',
            'status' => 'required|in:Approved,Rejected,Pending',
        ]);

        $contract = Contract::where('contract_number', $validated['contract_id'])->first();

        if (!$contract) {
            return response()->json(['error' => 'Contract not found'], 404);
        }

        $localStatus = match($validated['status']) {
            'Approved' => 'approved',
            'Rejected' => 'rejected',
            default => 'pending'
        };

        $contract->update(['status' => $localStatus]);

        return response()->json(['message' => 'Contract status updated successfully']);
    }

    // Show contract details
    public function show($id)
    {
        $contract = Contract::findOrFail($id);
        return response()->json($contract);
    }

    // Show edit form
    public function edit($id)
    {
        $contract = Contract::findOrFail($id);
        if ($contract->status !== 'pending') {
            return redirect()->route('contract.management')->with('error', 'Only pending contracts can be edited.');
        }
        return view('Contract and Permit.edit-contract', compact('contract'));
    }

    // Update contract
    public function update(Request $request, $id)
    {
        $contract = Contract::findOrFail($id);
        if ($contract->status !== 'pending') {
            return redirect()->route('contract.management')->with('error', 'Only pending contracts can be edited.');
        }

        $validated = $request->validate([
            'contract_type' => 'required|string|max:255',
            'counterparty' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_number' => 'nullable|string|max:255',
            'effective_date' => 'required|date',
            'expiration_date' => 'nullable|date|after_or_equal:effective_date',
            'total_amount' => 'required|numeric|min:0',
            'payment_type' => 'required|string',
            'equipment_type' => 'nullable|string',
            'contract_details' => 'nullable|string',
        ]);

        $contract->update([
            'contract_type' => $validated['contract_type'],
            'company_name' => $validated['counterparty'],
            'client_email' => $validated['contact_email'] ?? 'N/A',
            'client_number' => $validated['contact_number'] ?? 'N/A',
            'start_date' => $validated['effective_date'],
            'end_date' => $validated['expiration_date'],
            'equipment_type' => $validated['equipment_type'] ?? 'N/A',
            'payment_type' => $validated['payment_type'] ?? 'N/A',
            'contract_details' => $validated['contract_details'] ?? 'No details provided.',
            'total_amount' => $validated['total_amount'] ?? 0.00,
        ]);

        return redirect()->route('contract.management')->with('success', 'Contract updated successfully!');
    }

    // Delete contract
    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
        if ($contract->status !== 'pending') {
            return redirect()->route('contract.management')->with('error', 'Only pending contracts can be deleted.');
        }
        
        $contract->delete();
        return redirect()->route('contract.management')->with('success', 'Contract deleted successfully!');
    }

    // Manual status refresh
    public function refreshStatus(Request $request, $id)
    {
        $contract = Contract::findOrFail($id);
        
        try {
            $response = Http::get("https://admin.cranecali-ms.com/contracts/{$contract->contract_number}");
            
            if ($response->successful()) {
                $legalStatus = $response->json()['status'] ?? 'Pending';
                $localStatus = match($legalStatus) {
                    'Approved' => 'approved',
                    'Rejected' => 'rejected',
                    default => 'pending'
                };
                
                $contract->update(['status' => $localStatus]);
                return redirect()->back()->with('success', 'Contract status refreshed successfully!');
            }
        } catch (\Exception $e) {
            \Log::error('Failed to refresh contract status', ['error' => $e->getMessage()]);
        }
        
        return redirect()->back()->with('error', 'Failed to refresh contract status. Please try again later.');
    }

    public function view($id)
    {
        $contract = Contract::findOrFail($id);
        return view('Contract and Permit.view-contract', compact('contract'));
    }

    // View Permit
    public function viewPermit($id)
    {
        $permit = Contract::findOrFail($id);
        return view('Contract and Permit.view-permit', compact('permit'));
    }

    // Edit Permit Form
    public function editPermit($id)
    {
        $permit = Contract::findOrFail($id);
        if ($permit->status !== 'pending') {
            return redirect()->route('manage-permits')->with('error', 'Only pending permits can be edited.');
        }
        return view('Contract and Permit.edit-permit', compact('permit'));
    }

    // Update Permit
    public function updatePermit(Request $request, $id)
    {
        $permit = Contract::findOrFail($id);
        if ($permit->status !== 'pending') {
            return redirect()->route('manage-permits')->with('error', 'Only pending permits can be edited.');
        }

        $validated = $request->validate([
            'permit_number' => 'required|string|max:255',
            'issuing_authority' => 'required|string|max:255',
            'permit_type' => 'required|string',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'permit_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Handle file upload
        $updateData = [
            'contract_number' => $validated['permit_number'],
            'contract_type' => $validated['permit_type'],
            'company_name' => $validated['issuing_authority'],
            'start_date' => $validated['issue_date'],
            'end_date' => $validated['expiry_date'],
        ];

        if ($request->hasFile('permit_document')) {
            $documentPath = $request->file('permit_document')->store('permits', 'public');
            $updateData['document_path'] = $documentPath;
        }

        $permit->update($updateData);

        return redirect()->route('manage-permits')->with('success', 'Permit updated successfully!');
    }

    // Delete Permit
    public function destroyPermit($id)
    {
        $permit = Contract::findOrFail($id);
        if ($permit->status !== 'pending') {
            return redirect()->route('manage-permits')->with('error', 'Only pending permits can be deleted.');
        }
        
        $permit->delete();
        return redirect()->route('manage-permits')->with('success', 'Permit deleted successfully!');
    }
}