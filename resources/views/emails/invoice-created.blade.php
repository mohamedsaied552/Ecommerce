<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .invoice-details { background: white; padding: 15px; margin: 20px 0; border-left: 4px solid #007bff; }
        .button { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Invoice {{ $invoice->invoice_number }}</h1>
        </div>
        <div class="content">
            <p>Hello{{ $invoice->customer_name ? ', ' . $invoice->customer_name : '' }},</p>
            
            <p>An invoice has been created for you. Please find the details below:</p>
            
            <div class="invoice-details">
                <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Amount:</strong> {{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}</p>
                @if($invoice->description)
                <p><strong>Description:</strong> {{ $invoice->description }}</p>
                @endif
            </div>
            
            <p>To pay this invoice, please click the button below:</p>
            
            <div style="text-align: center;">
                <a href="{{ $invoice->payment_link }}" class="button">Pay Now</a>
            </div>
            
            <p>Or copy and paste this link into your browser:</p>
            <p style="word-break: break-all; color: #007bff;">{{ $invoice->payment_link }}</p>
            
            <p><strong>Important:</strong> After payment, please contact us via chat to receive your digital code. Our team will assist you shortly.</p>
        </div>
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>

