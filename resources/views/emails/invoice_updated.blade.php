@component('mail::message')
# 📝 Hello {{ $invoice['client_name'] }},

Your invoice has been successfully **updated**. Please find the updated details below.

---

### 🧾 Invoice Details

@component('mail::table')
| Description | Qty | Price | Total |
|-------------|-----|--------|--------|
@foreach ($invoice['items'] as $item)
| {{ $item['description'] }} | {{ $item['qty'] }} | ₱{{ number_format($item['price'], 2) }} | ₱{{ number_format($item['qty'] * $item['price'], 2) }} |
@endforeach
@endcomponent

**Invoice Date:** {{ \Carbon\Carbon::parse($invoice['invoice_date'])->format('F d, Y') }}  
**Due Date:** {{ \Carbon\Carbon::parse($invoice['due_date'])->format('F d, Y') }}

---

### 💵 Summary

- **Subtotal:** ₱{{ number_format($invoice['subtotal'], 2) }}  
- **Tax (15%):** ₱{{ number_format($invoice['tax'], 2) }}  
- **Total:** **₱{{ number_format($invoice['total'], 2) }}**

@component('mail::button', ['url' => route('invoices.edit', $invoice['invoice_id'])])
View Updated Invoice
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
