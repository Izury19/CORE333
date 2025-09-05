@component('mail::message')
# Hello {{ $invoice['client_name'] }},

Here is your invoice:

@component('mail::table')
| Description | Qty | Price | Total |
|-------------|-----|-------|-------|
@foreach ($invoice['items'] as $item)
| {{ $item['description'] }} | {{ $item['qty'] }} | ₱{{ number_format($item['price'], 2) }} | ₱{{ number_format($item['qty'] * $item['price'], 2) }} |
@endforeach
@endcomponent

**Subtotal:** ₱{{ number_format($invoice['subtotal'], 2) }}  
**Tax (15%):** ₱{{ number_format($invoice['tax'], 2) }}  
**Total:** ₱{{ number_format($invoice['total'], 2) }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
