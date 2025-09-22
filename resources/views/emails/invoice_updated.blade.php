@component('mail::message')
# 📝 Hello {{ $invoice['client_name'] }},

Your invoice has been successfully **updated**. Please find the updated details below.

---

### 🧾 Invoice Details

**📅 Invoice Date:** {{ \Carbon\Carbon::parse($invoice['invoice_date'])->format('F d, Y') }}  
**📆 Due Date:**  
@if(\Carbon\Carbon::parse($invoice['due_date'])->isPast())
⚠️ <span style="color:red;">{{ \Carbon\Carbon::parse($invoice['due_date'])->format('F d, Y') }} (Overdue)</span>  
@else
{{ \Carbon\Carbon::parse($invoice['due_date'])->format('F d, Y') }}
@endif  

**🏠 Address:** {{ $invoice['client_address'] ?? 'Not provided' }} 

**💳 Payment Method:** {{ $invoice['terms_of_payment'] ?? 'N/A' }}  
**📄 Payment Details:**  
@switch($invoice['terms_of_payment'])
    @case('Bank Transfer')
        🏦 BPI - Account Name: XYZ Corporation, Account No: 1234-5678-9012
        @break

    @case('GCash')
        📱 GCash - Account Name: XYZ Corp, GCash Number: 0917-123-4567
        @break

    @case('Paypal')
        🌐 Paypal - Email: payments@xyzcorp.com
        @break

    @case('Cash')
        💵 Cash payment must be settled at our office: 134 Magsaysay Ext, Quezon City
        @break

    @default
        Please contact us for payment details.
@endswitch 


---

@component('mail::table')
| Description | Qty | Price | Total |
|-------------|-----|--------|--------|
@foreach ($invoice['items'] as $item)
| {{ $item['description'] }} | {{ $item['qty'] }} | ₱{{ number_format($item['price'], 2) }} | ₱{{ number_format($item['qty'] * $item['price'], 2) }} |
@endforeach
@endcomponent

---

### 💵 Summary
- **Subtotal:** ₱{{ number_format($invoice['subtotal'], 2) }}  
- **Tax (15%):** ₱{{ number_format($invoice['tax'], 2) }}  
- **Total:** **₱{{ number_format($invoice['total'], 2) }}**  

@if (!empty($invoice['status']))
- **📌 Status:**  
  @if($invoice['status'] === 'paid')
  ✅ <span style="color:green;">Paid</span>  
  @elseif($invoice['status'] === 'pending')
  ⏳ <span style="color:orange;">Pending</span>  
  @elseif($invoice['status'] === 'overdue')
  ⚠️ <span style="color:red;">Overdue</span>  
  @else
  {{ ucfirst($invoice['status']) }}
  @endif
@endif

@if (!empty($invoice['note']))
---
### 📝 Note
{{ $invoice['note'] }}
@endif

@component('mail::button', ['url' => route('invoices.edit', $invoice['invoice_id'])])
🔗 View Updated Invoice
@endcomponent

Thanks again,<br>
**{{ config('app.name') }}**
@endcomponent
