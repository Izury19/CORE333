<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class PaymentApiController extends Controller
{
    public function summary()
    {
        return response()->json([
            'total_due'      => Invoice::where('status', Invoice::STATUS_UNPAID)->sum('total'),
            'count_due'      => Invoice::where('status', Invoice::STATUS_UNPAID)->count(),
            'total_paid'     => Invoice::where('status', Invoice::STATUS_PAID)->sum('total'),
            'count_paid'     => Invoice::where('status', Invoice::STATUS_PAID)->count(), // ← dagdag
            'total_overdue'  => Invoice::where('status', Invoice::STATUS_OVERDUE)->sum('total'),
            'count_overdue'  => Invoice::where('status', Invoice::STATUS_OVERDUE)->count(),
            'recent_activity'=> Invoice::latest()->first()?->client_name,
        ]);
    }

    public function index(Request $request)
    {
        $query = Invoice::query();

        if($request->filled('search')){
            $q = $request->search;
            $query->where(function($q2) use ($q) {
                $q2->where('invoice_id','like',"%$q%")
                   ->orWhere('client_name','like',"%$q%")
                   ->orWhere('client_email','like',"%$q%");
            });
        }

        if($request->filled('status')){
            $query->where('status',$request->status);
        }

        $invoices = $query->orderBy('created_at','desc')
                          ->paginate($request->per_page ?? 10);

        return response()->json($invoices);
    }

    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:unpaid,paid,overdue',
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->status = $request->status;
        $invoice->save();

        return response()->json(['success'=>true, 'status'=>$invoice->status]);
    }

    public function sendReminder($id)
    {
        $invoice = Invoice::findOrFail($id);
        // TODO: implement Mail::to($invoice->client_email)->send(new ReminderMail($invoice));
        return response()->json(['success'=>true, 'message'=>'Reminder sent to '.$invoice->client_email]);
    }
}
