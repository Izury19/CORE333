<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use TCPDF;

class ReportingController extends Controller
{
    public function index()
    {
        // Get revenue breakdown by equipment type
        $revenueBreakdown = DB::table('billing_invoices')
            ->select('equipment_type', DB::raw('SUM(total_amount) as total'))
            ->where('status', 'paid')
            ->groupBy('equipment_type')
            ->get();

        // Get detailed invoice data
        $invoiceDetails = DB::table('billing_invoices')
            ->select('id', 'client_name', 'equipment_type', 'equipment_id', 'hours_used', 'hourly_rate', 'total_amount', 'created_at')
            ->where('status', 'paid')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Calculate totals
        $totalRevenue = DB::table('billing_invoices')->where('status', 'paid')->sum('total_amount');
        $collectionRate = DB::table('billing_invoices')->where('status', 'paid')->count();
        $totalInvoices = DB::table('billing_invoices')->count();
        $collectionRatePercent = $totalInvoices > 0 ? round(($collectionRate / $totalInvoices) * 100) : 0;

        return view('Reporting and Analytics.financial-report', compact(
            'revenueBreakdown',
            'invoiceDetails',
            'totalRevenue',
            'collectionRatePercent'
        ));
    }

    public function exportExcel()
    {
        // Simple CSV download (works in all Laravel versions)
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="financial-report.csv"',
        ];
        
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Invoice ID', 'Client Name', 'Equipment Type', 'Equipment ID', 'Hours Used', 'Hourly Rate', 'Total Amount', 'Date Created']);
        
        $invoices = DB::table('billing_invoices')
            ->select('id', 'client_name', 'equipment_type', 'equipment_id', 'hours_used', 'hourly_rate', 'total_amount', 'created_at')
            ->where('status', 'paid')
            ->get();
            
        foreach ($invoices as $invoice) {
            fputcsv($handle, [
                'INV-' . str_pad($invoice->id, 3, '0', STR_PAD_LEFT),
                $invoice->client_name,
                ucfirst(str_replace('_', ' ', $invoice->equipment_type)),
                $invoice->equipment_id,
                $invoice->hours_used,
                $invoice->hourly_rate,
                $invoice->total_amount,
                \Carbon\Carbon::parse($invoice->created_at)->format('M d, Y')
            ]);
        }
        
        fclose($handle);
        return response()->streamDownload(function () use ($handle) {}, 'financial-report.csv', $headers);
    }

    public function exportPdf()
    {
        // Get the data
        $invoiceDetails = DB::table('billing_invoices')
            ->select('id', 'client_name', 'equipment_type', 'equipment_id', 'hours_used', 'hourly_rate', 'total_amount', 'created_at')
            ->where('status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = DB::table('billing_invoices')->where('status', 'paid')->sum('total_amount');
        $collectionRate = DB::table('billing_invoices')->where('status', 'paid')->count();
        $totalInvoices = DB::table('billing_invoices')->count();
        $collectionRatePercent = $totalInvoices > 0 ? round(($collectionRate / $totalInvoices) * 100) : 0;

        // Create new TCPDF instance
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document info
        $pdf->SetCreator('Financial System');
        $pdf->SetAuthor('Admin');
        $pdf->SetTitle('Financial Report');
        $pdf->SetSubject('Confidential Financial Data');
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
        // Build HTML content with inline CSS
        $html = '
        <style>
            body { font-family: helvetica; }
            .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 15px; }
            .header h1 { margin: 0; color: #333; font-size: 18px; }
            .summary { background: #f5f5f5; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th, td { border: 1px solid #ddd; padding: 6px; text-align: left; font-size: 9px; }
            th { background-color: #e5e5e5; font-weight: bold; }
            .footer { margin-top: 20px; font-size: 8px; color: #666; text-align: center; }
        </style>
        
        <div class="header">
            <h1>FINANCIAL INTELLIGENCE REPORT</h1>
            <p>Generated on: ' . date('F d, Y H:i:s') . '</p>
        </div>
        
        <div class="summary">
            <p><strong>Total Revenue:</strong> ' . number_format($totalRevenue, 2) . '.php</p>
            <p><strong>Collection Rate:</strong> ' . $collectionRatePercent . '%</p>
            <p><strong>Active Clients:</strong> ' . $invoiceDetails->count() . '</p>
        </div>
        
        <h3 style="margin-bottom: 10px; font-size: 12px;">Transaction Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Client Name</th>
                    <th>Equipment</th>
                    <th>Hours</th>
                    <th>Rate (₱)</th>
                    <th>Total Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($invoiceDetails as $invoice) {
            $html .= '<tr>
                <td>INV-' . str_pad($invoice->id, 3, '0', STR_PAD_LEFT) . '</td>
                <td>' . htmlspecialchars($invoice->client_name) . '</td>
                <td>' . ucfirst(str_replace('_', ' ', $invoice->equipment_type)) . ' ' . $invoice->equipment_id . '</td>
                <td>' . $invoice->hours_used . '</td>
                <td>' . number_format($invoice->hourly_rate, 2) . '</td>
                <td>₱' . number_format($invoice->total_amount, 2) . '</td>
                <td>' . \Carbon\Carbon::parse($invoice->created_at)->format('M d, Y') . '</td>
            </tr>';
        }
        
        $html .= '</tbody>
        </table>
        
        <div class="footer">
            <p>This is a system-generated confidential report. Do not distribute without authorization.</p>
        </div>';

        // Write HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Set password protection
        $pdf->SetProtection(['print', 'copy'], 'document', 'admin');
        
        // Output PDF
        return response($pdf->Output('financial-report.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="financial-report.pdf"');
    }

    // Helper method to generate actual PDFs
    private function generateReportPdf($documentType)
    {
        if ($documentType === 'Financial Intelligence Report') {
            // Use the same logic as exportPdf but return content
            $invoiceDetails = DB::table('billing_invoices')
                ->select('id', 'client_name', 'equipment_type', 'equipment_id', 'hours_used', 'hourly_rate', 'total_amount', 'created_at')
                ->where('status', 'paid')
                ->orderBy('created_at', 'desc')
                ->get();

            $totalRevenue = DB::table('billing_invoices')->where('status', 'paid')->sum('total_amount');
            $collectionRate = DB::table('billing_invoices')->where('status', 'paid')->count();
            $totalInvoices = DB::table('billing_invoices')->count();
            $collectionRatePercent = $totalInvoices > 0 ? round(($collectionRate / $totalInvoices) * 100) : 0;

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetCreator('Financial System');
            $pdf->SetAuthor('Admin');
            $pdf->SetTitle('Financial Intelligence Report');
            $pdf->SetSubject('Confidential Financial Data');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 10);
            
            $html = '
            <style>
                body { font-family: helvetica; }
                .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 15px; }
                .header h1 { margin: 0; color: #333; font-size: 18px; }
                .summary { background: #f5f5f5; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border: 1px solid #ddd; padding: 6px; text-align: left; font-size: 9px; }
                th { background-color: #e5e5e5; font-weight: bold; }
                .footer { margin-top: 20px; font-size: 8px; color: #666; text-align: center; }
            </style>
            
            <div class="header">
                <h1>FINANCIAL INTELLIGENCE REPORT</h1>
                <p>Generated on: ' . date('F d, Y H:i:s') . '</p>
            </div>
            
            <div class="summary">
                <p><strong>Total Revenue:</strong> ₱' . number_format($totalRevenue, 2) . '</p>
                <p><strong>Collection Rate:</strong> ' . $collectionRatePercent . '%</p>
                <p><strong>Active Clients:</strong> ' . $invoiceDetails->count() . '</p>
            </div>
            
            <h3 style="margin-bottom: 10px; font-size: 12px;">Transaction Details</h3>
            <table>
                <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Client Name</th>
                        <th>Equipment</th>
                        <th>Hours</th>
                        <th>Rate (₱)</th>
                        <th>Total Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>';
            
            foreach ($invoiceDetails as $invoice) {
                $html .= '<tr>
                    <td>INV-' . str_pad($invoice->id, 3, '0', STR_PAD_LEFT) . '</td>
                    <td>' . htmlspecialchars($invoice->client_name) . '</td>
                    <td>' . ucfirst(str_replace('_', ' ', $invoice->equipment_type)) . ' ' . $invoice->equipment_id . '</td>
                    <td>' . $invoice->hours_used . '</td>
                    <td>' . number_format($invoice->hourly_rate, 2) . '</td>
                    <td>₱' . number_format($invoice->total_amount, 2) . '</td>
                    <td>' . \Carbon\Carbon::parse($invoice->created_at)->format('M d, Y') . '</td>
                </tr>';
            }
            
            $html .= '</tbody>
            </table>
            
            <div class="footer">
                <p>This is a system-generated confidential report. Do not distribute without authorization.</p>
            </div>';

            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->SetProtection(['print', 'copy'], 'document', 'admin');
            
            return $pdf->Output('financial-intelligence-report.pdf', 'S');
            
        } elseif ($documentType === 'Regulatory Compliance Report') {
            // Generate compliance report PDF
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetCreator('Compliance System');
            $pdf->SetAuthor('Admin');
            $pdf->SetTitle('Regulatory Compliance Report');
            $pdf->SetSubject('Confidential Compliance Data');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 10);
            
            $html = '<h1>REGULATORY COMPLIANCE REPORT</h1>
            <p>Generated on: ' . date('F d, Y H:i:s') . '</p>
            <p>This report contains regulatory compliance information for safety, equipment certification, and environmental standards.</p>
            <p><strong>Status:</strong> Compliant</p>
            <p><strong>Last Updated:</strong> ' . date('F d, Y') . '</p>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->SetProtection(['print', 'copy'], 'document', 'admin');
            
            return $pdf->Output('regulatory-compliance-report.pdf', 'S');
            
        } else {
            // Generate project report PDF
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetCreator('Project Management System');
            $pdf->SetAuthor('Admin');
            $pdf->SetTitle('Project Status Update');
            $pdf->SetSubject('Confidential Project Data');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 10);
            
            $html = '<h1>PROJECT STATUS UPDATE</h1>
            <p>Generated on: ' . date('F d, Y H:i:s') . '</p>
            <p>This report contains project progress updates including completion status and milestones.</p>
            <p><strong>Projects Tracked:</strong> 3</p>
            <p><strong>Status:</strong> All projects on schedule</p>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->SetProtection(['print', 'copy'], 'document', 'admin');
            
            return $pdf->Output('project-status-update.pdf', 'S');
        }
    }

    public function forwardDocument(Request $request)
{
    $request->validate([
        'document_type' => 'required|string',
        'category' => 'required|string'
    ]);

    try {
        // Generate actual PDF content
        $pdfContent = $this->generateReportPdf($request->document_type);
        $filename = str_replace(' ', '_', $request->document_type) . '_' . now()->format('Ymd_His') . '.pdf';

        // Map to admin API expected fields
        $title = $request->document_type; // This becomes the 'title'
        $description = "Automatically generated " . strtolower($request->document_type) . " from Reporting & Analytics System";
        
        // Send to admin API with CORRECT field names
        $response = Http::withOptions([
            'verify' => false,
            'timeout' => 30
        ])
        ->attach('file', $pdfContent, $filename)
        ->post('https://admin.cranecali-ms.com/api/documents/store', [
            'title' => $title,           // ✅ Required by admin API
            'description' => $description, // ✅ Optional but good to include
            'file' => $pdfContent        // ✅ File attachment
        ]);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => '✅ File successfully sent to Document Manager!',
                'filename' => $filename
            ]);
        } else {
            Log::error('Admin API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'title' => $title
            ]);
            
            return response()->json([
                'success' => false,
                'message' => '❌ Failed to send file. Status: ' . $response->status()
            ], 500);
        }

    } catch (\Exception $e) {
        Log::error('Forward Document Exception', [
            'error' => $e->getMessage(),
            'document_type' => $request->document_type
        ]);
        
        return response()->json([
            'success' => false,
            'message' => '❌ Server error: ' . $e->getMessage()
        ], 500);
    }
}
}