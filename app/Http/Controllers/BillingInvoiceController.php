<?php

namespace App\Http\Controllers;

use App\Models\BillingInvoice;
use App\Models\Record; 
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use TCPDF;

class BillingInvoiceController extends Controller
{
    public function index()
    {
        try {
            $invoices = BillingInvoice::latest()->get();
            
            // AI Predictions
            $duplicateCount = $invoices->where('ai_duplicate_flag', true)->count();
            $last30DaysRevenue = $invoices->where('created_at', '>=', now()->subDays(30))
                ->where('status', 'billed')
                ->sum('total_amount');
                
            $revenueForecast = $last30DaysRevenue > 0 ? $last30DaysRevenue * 1.15 : 185000;
            
            $equipmentUsage = [];
            foreach ($invoices as $invoice) {
                $type = $invoice->equipment_type ?? 'crane';
                if (!isset($equipmentUsage[$type])) {
                    $equipmentUsage[$type] = 0;
                }
                $equipmentUsage[$type] += $invoice->hours_used ?? 0;
            }
            
            arsort($equipmentUsage);
            $topEquipment = !empty($equipmentUsage) ? key($equipmentUsage) : 'crane';
            
            $rateRecommendations = [
                'crane' => '₱2,200/hr',
                'truck' => '₱1,500/hr',
                'water_pump' => '₱800/hr',
                'air_compressor' => '₱950/hr'
            ];
            
            $recommendedRate = $rateRecommendations[$topEquipment] ?? '₱1,800/hr';

            $aiPredictions = [
                'duplicate_alerts' => $duplicateCount,
                'recommended_rate' => $recommendedRate,
                'verified_invoices' => $invoices->count() - $duplicateCount,
                'revenue_forecast' => $revenueForecast,
                'top_equipment' => ucfirst(str_replace('_', ' ', $topEquipment)),
                'ai_confidence' => $invoices->count() > 10 ? 'high' : 'medium'
            ];
            
        } catch (\Exception $e) {
            $invoices = collect();
            $aiPredictions = [
                'duplicate_alerts' => 0,
                'recommended_rate' => '₱1,800/hr',
                'verified_invoices' => 0,
                'revenue_forecast' => 0,
                'top_equipment' => 'Crane',
                'ai_confidence' => 'low'
            ];
        }
        
        return view('Billing and Invoicing.invoice-list', compact('invoices', 'aiPredictions'));
    }

    public function show($id)
    {
        try {
            $invoice = BillingInvoice::findOrFail($id);
            return view('Billing and Invoicing.show', compact('invoice'));
        } catch (\Exception $e) {
            return redirect()->route('billing.invoices.index')->withErrors([
                'not_found' => 'Invoice not found.'
            ]);
        }
    }
    
    public function scanDuplicates(Request $request)
    {
        $invoices = BillingInvoice::all();
        
        // Reset all flags first
        foreach ($invoices as $invoice) {
            $invoice->update(['ai_duplicate_flag' => false]);
        }
        
        // Check each invoice against others
        foreach ($invoices as $invoice1) {
            foreach ($invoices as $invoice2) {
                if ($invoice1->id === $invoice2->id) continue; // Skip self
                
                // Check within 24 hours
                $hoursDiff = abs($invoice1->created_at->diffInHours($invoice2->created_at));
                if ($hoursDiff > 24) continue;
                
                // Normalize values for comparison
                $client1 = strtolower(trim($invoice1->client_name ?? ''));
                $client2 = strtolower(trim($invoice2->client_name ?? ''));
                $equip1 = strtolower(trim($invoice1->equipment_type ?? ''));
                $equip2 = strtolower(trim($invoice2->equipment_type ?? ''));
                $amount1 = round($invoice1->total_amount ?? 0, 2);
                $amount2 = round($invoice2->total_amount ?? 0, 2);
                
                // Check for match
                if ($client1 === $client2 && $equip1 === $equip2 && $amount1 === $amount2) {
                    $invoice1->update(['ai_duplicate_flag' => true]);
                    break;
                }
            }
        }
        
        return redirect()->route('billing.invoices.index')
            ->with('success', 'AI scan completed! Potential duplicates flagged for review.');
    }

   public function downloadPdf($id)
{
    try {
        $invoice = BillingInvoice::findOrFail($id);
        
        // Generate password hint for user
        $passwordHint = str_replace(' ', '', strtolower($invoice->client_name)) . $invoice->id;
        
        // Store in session to show on page (optional)
        session(['pdf_password' => $passwordHint]);
        
        $pdf = Pdf::loadView('Billing and Invoicing.invoice-pdf', compact('invoice'))
            ->setPaper('a4')
            ->setOption('default-header', false)
            ->setOption('default-footer', false);

        return $pdf->download("invoice-{$id}.pdf");
        
    } catch (\Exception $e) {
        return redirect()->route('billing.invoices.index')->withErrors([
            'pdf_error' => 'Failed to generate PDF.'
        ]);
    }
}

    public function demoStore(Request $request)
{
    $request->validate([
        'client_name' => 'required',
        'equipment_type' => 'required|in:mobile_crane,tower_crane,dump_truck,concrete_mixer',
        'hours_used' => 'required|numeric|min:1'
    ]);

    $rates = [
        'mobile_crane' => 2500,
        'tower_crane' => 2500,
        'dump_truck' => 1800,
        'concrete_mixer' => 2000,
    ];

    $hourlyRate = $rates[$request->equipment_type];
    $subtotal = $request->hours_used * $hourlyRate;
    $vat = $subtotal * 0.12;
    $totalAmount = $subtotal + $vat;
    
    $equipmentId = strtoupper(substr($request->equipment_type, 0, 3)) . '-' . rand(1000, 9999);

    // Create invoice
    $invoice = BillingInvoice::create([
        'invoice_uid' => 'INV-' . now()->format('ymd') . '-' . rand(1000, 9999),
        'client_name' => $request->client_name,
        'equipment_type' => $request->equipment_type,
        'equipment_id' => $equipmentId,
        'hours_used' => $request->hours_used,
        'hourly_rate' => $hourlyRate,
        'total_amount' => $totalAmount,
        'billing_period_start' => now()->subDays(2),
        'billing_period_end' => now(),
        'status' => 'issued',
        'sent_to_record_payment' => true
    ]);

   // 🔥 FORCE INSERT — bypass foreign key issue
\DB::statement('SET FOREIGN_KEY_CHECKS=0;');

\DB::table('records')->insert([
    'invoice_id' => $invoice->id,
    'payment_uid' => 'PAY-' . now()->format('Y') . '-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT),
    'payment_method' => 'pending',
    'reference_number' => $invoice->invoice_uid,
    'status' => 'pending',
    'total' => 0,
    'client_name' => $request->client_name, // ✅ MUST BE PRESENT
    'created_at' => now(),
    'updated_at' => now()
]);

\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    // ✅ REDIRECT (not JSON) for simple form submission
    return redirect()->route('billing.invoices.index')
        ->with('success', '✅ Demo invoice generated and synced to Record & Payment!');
}
    
   // Add this method at the bottom of your BillingInvoiceController class
public function forwardIssuedBill(Request $request, $invoiceId)
{
    $invoice = BillingInvoice::findOrFail($invoiceId);
    
    if (!in_array($invoice->status, ['issued', 'billed'])) {
        return back()->with('error', 'Only issued/billed invoices can be forwarded to Financials System.');
    }

    try {
        // PREPARE DATA IN EXACT FORMAT THEY EXPECT
        $data = [
            'action' => 'transfer_to_collections',
            'invoice_number' => 'INV-' . str_pad($invoice->id, 3, '0', STR_PAD_LEFT),
            'client_name' => $invoice->client_name,
            'billing_date' => \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d'),
            'due_date' => \Carbon\Carbon::parse($invoice->due_date ?? now()->addDays(30))->format('Y-m-d'),
            'amount_base' => $invoice->total_amount,
            'vat_applied' => 'No',
            'notes' => 'Auto-transferred from Billing System'
        ];

        // SEND TO THEIR EXACT ENDPOINT
        $response = Http::withOptions(['verify' => false, 'timeout' => 30])
            ->post('https://financials.cranecali-ms.com/collections_api.php', $data);

        if ($response->successful()) {
            return back()->with('success', '✅ Invoice successfully forwarded to Financials System!');
        } else {
            \Log::error('Financials API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'invoice_id' => $invoice->id
            ]);
            
            return back()->with('error', '❌ Failed to send. Status: ' . $response->status());
        }

    } catch (\Exception $e) {
        return back()->with('error', '❌ Server error: ' . $e->getMessage());
    }
}

private function generateInvoicePdf($invoice)
{
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator('Billing System');
    $pdf->SetAuthor('Admin');
    $pdf->SetTitle('Invoice: ' . $invoice->invoice_uid);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);
    
    $html = '<h2>INVOICE</h2>';
    $html .= '<p><strong>Client:</strong> ' . $invoice->client_name . '</p>';
    $html .= '<p><strong>Amount:</strong> ₱' . number_format($invoice->total_amount, 2) . '</p>';
    $html .= '<p><strong>Date:</strong> ' . $invoice->created_at . '</p>';
    
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->SetProtection(['print', 'copy'], 'document', 'admin');
    
    return $pdf->Output('invoice_' . $invoice->id . '.pdf', 'S');
}

public function bulkForwardToFinancials(Request $request)
{
    \Log::info('=== BULK FORWARD TO FINANCIALS ===', ['all_request' => $request->all()]);
    
    $request->validate([
        'invoice_ids_json' => 'required|string'
    ]);
    
    $invoiceIds = json_decode($request->invoice_ids_json, true);
    
    if (!is_array($invoiceIds) || empty($invoiceIds)) {
        return back()->with('error', '❌ Invalid invoice selection.');
    }
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($invoiceIds as $invoiceId) {
        $invoice = BillingInvoice::find($invoiceId);
        
        if (!$invoice || !in_array(strtolower($invoice->status), ['issued', 'billed'])) {
            $errorCount++;
            continue;
        }
        
        try {
            // PREPARE DATA IN EXACT FORMAT THEY EXPECT
            $data = [
                'action' => 'transfer_to_collections',
                'invoice_number' => 'INV-' . str_pad($invoice->id, 3, '0', STR_PAD_LEFT),
                'client_name' => $invoice->client_name,
                'billing_date' => \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d'),
                'due_date' => \Carbon\Carbon::parse($invoice->due_date ?? now()->addDays(30))->format('Y-m-d'),
                'amount_base' => $invoice->total_amount,
                'vat_applied' => 'No', // Based on their system
                'notes' => 'Auto-transferred from Billing System'
            ];
            
            // SEND TO THEIR EXACT ENDPOINT
            $response = Http::withOptions(['verify' => false, 'timeout' => 30])
                ->post('https://financials.cranecali-ms.com/collections_api.php', $data);
                
            if ($response->successful()) {
                $successCount++;
                \Log::info('✅ Invoice forwarded successfully', [
                    'invoice_id' => $invoiceId,
                    'response' => $response->json()
                ]);
            } else {
                $errorCount++;
                \Log::error('API Error', [
                    'invoice_id' => $invoiceId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
            
        } catch (\Exception $e) {
            $errorCount++;
            \Log::error('Forward Exception', [
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    if ($successCount > 0) {
        $message = "✅ Successfully forwarded {$successCount} invoice(s) to Financials System!";
        if ($errorCount > 0) {
            $message .= " ❌ {$errorCount} failed.";
        }
        return back()->with('success', $message);
    } else {
        return back()->with('error', '❌ All selected invoices failed to forward.');
    }
}


}       