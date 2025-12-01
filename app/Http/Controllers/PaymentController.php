<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['invoice.student', 'invoice.lesson'])
            ->orderByDesc('paid_at')
            ->orderByDesc('created_at')
            ->paginate(50);

        return view('admin.accounting.transactions', compact('payments'));
    }
    public function unsettled()
    {
        $payments = Payment::with(['invoice.student', 'invoice.lesson'])
            ->orderByDesc('paid_at')
            ->orderByDesc('created_at')
            ->paginate(50);

        return view('admin.accounting.unsettled', compact('payments'));
    }


    public function showInvoice(Invoice $invoice)
    {
        $invoice->load(['student', 'lesson', 'payments']);

        $app = AppSetting::first();

        return view('admin.accounting.invoices.course', compact('invoice', 'app'));
    }
}
