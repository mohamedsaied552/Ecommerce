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
            <h1>Payment Received</h1>
        </div>
        <div class="content">
            <p>Hello Admin,</p>
            
            <p>A payment has been successfully received for the following invoice:</p>
            
            <div class="invoice-details">
                <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Amount:</strong> {{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}</p>
                @if($invoice->customer_name)
                <p><strong>Customer Name:</strong> {{ $invoice->customer_name }}</p>
                @endif
                @if($invoice->customer_email)
                <p><strong>Customer Email:</strong> {{ $invoice->customer_email }}</p>
                @endif
                @if($invoice->customer_phone)
                <p><strong>Customer Phone:</strong> {{ $invoice->customer_phone }}</p>
                @endif
                <p><strong>Paid At:</strong> {{ $invoice->paid_at->format('M d, Y H:i:s') }}</p>
                @if($payment->gateway_order_id)
                <p><strong>Gateway Order ID:</strong> {{ $payment->gateway_order_id }}</p>
                @endif
                @if($payment->gateway_transaction_id)
                <p><strong>Gateway Transaction ID:</strong> {{ $payment->gateway_transaction_id }}</p>
                @endif
            </div>
            
            <p><strong>Action Required:</strong> Please deliver the digital code to the customer via chat.</p>
        </div>
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>

