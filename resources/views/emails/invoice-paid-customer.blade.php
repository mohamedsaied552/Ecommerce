<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #28a745; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .invoice-details { background: white; padding: 15px; margin: 20px 0; border-left: 4px solid #28a745; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payment Confirmed</h1>
        </div>
        <div class="content">
            <p>Hello{{ $invoice->customer_name ? ', ' . $invoice->customer_name : '' }},</p>
            
            <p>Your payment has been successfully confirmed!</p>
            
            <div class="invoice-details">
                <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Amount Paid:</strong> {{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}</p>
                <p><strong>Payment Date:</strong> {{ $invoice->paid_at->format('M d, Y H:i:s') }}</p>
            </div>
            
            <p><strong>Next Steps:</strong></p>
            <p>Please contact us via chat to receive your digital code. Our team will assist you shortly.</p>
            
            <p>Thank you for your business!</p>
        </div>
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>

