<?php

namespace App\Http\Controllers;

use App\Models\BillingInvoice;
use App\Models\Record; 
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'equipment_type' => 'required|in:crane,truck',
            'hours_used' => 'required|integer|min:1'
        ]);

        $hourlyRate = $request->equipment_type === 'crane' ? 2200 : 1500;
        $totalAmount = $request->hours_used * $hourlyRate;
        $uid = BillingInvoice::generateUid($request->equipment_type);

        // Create invoice
        $invoice = BillingInvoice::create([
            'invoice_uid' => $uid,
            'client_name' => $request->client_name,
            'equipment_type' => $request->equipment_type,
            'equipment_id' => 'DEMO-' . strtoupper($request->equipment_type) . '-001',
            'hours_used' => $request->hours_used,
            'hourly_rate' => $hourlyRate,
            'total_amount' => $totalAmount,
            'billing_period_start' => now()->subDays(2),
            'billing_period_end' => now(),
            'status' => 'billed',
            'sent_to_record_payment' => true
        ]);

        // Auto-create payment record placeholder (USE create() INSTEAD OF updateOrCreate())
        Record::create([
            'invoice_id' => $invoice->id,
            'payment_uid' => 'PAY-' . now()->format('Y') . '-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT),
            'payment_type' => 'client',
            'client_name' => $request->client_name,
            'total' => 0,
            'payment_method' => 'pending',
            'reference_number' => 'PENDING',
            'status' => 'pending'
        ]);

        return redirect()->route('billing.invoices.index')
            ->with('success', 'Demo invoice generated and sent to Record & Payment!');
    }
}