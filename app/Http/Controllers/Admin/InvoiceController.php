<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Mail\InvoiceCreatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::query()->with('latestPayment');

        // Search by invoice number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(20);

        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.invoices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string|max:3',
            'description' => 'nullable|string|max:1000',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'amount_cents' => (int)($validated['amount'] * 100), // Convert to piastres
                'currency' => $validated['currency'] ?? 'EGP',
                'description' => $validated['description'] ?? null,
                'customer_name' => $validated['customer_name'] ?? null,
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'] ?? null,
                'status' => 'pending',
                'payment_link_token' => Invoice::generatePaymentLinkToken(),
            ]);

            // Send invoice email to customer if email provided
            if ($invoice->customer_email) {
                Mail::to($invoice->customer_email)->queue(new InvoiceCreatedMail($invoice));
            }

            DB::commit();

            return redirect()->route('admin.invoices.show', $invoice)
                ->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create invoice: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('payments');
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Resend invoice email to customer
     */
    public function resendEmail(Invoice $invoice)
    {
        if (!$invoice->customer_email) {
            return back()->with('error', 'Invoice does not have a customer email.');
        }

        try {
            Mail::to($invoice->customer_email)->queue(new InvoiceCreatedMail($invoice));
            return back()->with('success', 'Invoice email sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Mark invoice as expired
     */
    public function markExpired(Invoice $invoice)
    {
        if ($invoice->isPaid()) {
            return back()->with('error', 'Cannot expire a paid invoice.');
        }

        $invoice->update(['status' => 'expired']);
        return back()->with('success', 'Invoice marked as expired.');
    }
}
