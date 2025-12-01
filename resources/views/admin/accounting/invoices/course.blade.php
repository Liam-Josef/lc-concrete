<x-admin-master>
    @section('page-title')
        Invoice #{{ $invoice->number }} | MEX LMS Admin
    @endsection

    @section('styles')
        <style>
            .invoice-wrapper {
                max-width: 900px;
                margin: 0 auto 2rem auto;
                background: #fff;
                padding: 2rem 2.5rem;
                box-shadow: 0 0 20px rgba(0,0,0,0.08);
            }

            @media print {
                html, body {
                    background: #ffffff !important;
                    margin: 0;
                    padding: 0;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .no-print,
                .navbar,
                .sidebar,
                .footer,
                .page-footer {
                    display: none !important;
                }

                .container-fluid,
                .invoice-wrapper {
                    margin: 0 !important;
                    padding: 0.5in !important;
                    max-width: 100% !important;
                    width: 100% !important;
                    box-shadow: none !important;
                    border: none !important;
                }
            }
        </style>
    @endsection

    @section('content')
        <div class="container-fluid">

            {{-- Top bar: title + print --}}
            <div class="row no-print">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h3 mb-0">Invoice #{{ $invoice->number }}</h1>
                        <button type="button" class="btn btn-primary" onclick="window.print()">
                            <i class="fa fa-print me-1"></i> Print
                        </button>
                    </div>
                </div>
            </div>

            <div class="invoice-wrapper">

                {{-- Header: logo/app info + invoice meta --}}
                <div class="d-flex justify-content-between mb-4">
                    <div class="d-flex align-items-center">
                        @if(isset($app) && $app?->logo)
                            <img src="{{ asset('storage/'.$app->logo) }}"
                                 alt="{{ $app->app_name }}"
                                 style="height:60px;"
                                 class="me-3">
                        @endif
                        <div>
                            <div class="fw-bold">
                                {{ $app->app_name ?? 'Merchants Exchange Learning System' }}
                            </div>
                            @if($app?->company_email)
                                <div class="text-muted small">{{ $app->company_email }}</div>
                            @endif
                            @if($app?->company_phone)
                                <div class="text-muted small">{{ $app->company_phone }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="text-end">
                        <h5 class="mb-1">Invoice # {{ $invoice->number }}</h5>
                        <div>Date: {{ optional($invoice->date)->format('M d, Y') }}</div>

                        @if($invoice->paid)
                            <div class="mt-1">
                                <span class="badge bg-success">PAID</span>
                            </div>
                        @else
                            <div class="mt-1">
                                <span class="badge bg-warning text-dark">UNPAID</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Bill To / Course --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="fw-bold mb-1">Bill To</div>
                        <div>{{ optional($invoice->student)->first_name }} {{ optional($invoice->student)->last_name }}</div>
                        @if($invoice->student && $invoice->student->company)
                            <div class="text-muted">{{ $invoice->student->company }}</div>
                        @endif
                        @if($invoice->student && $invoice->student->email)
                            <div class="text-muted small">{{ $invoice->student->email }}</div>
                        @endif
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <div class="fw-bold mb-1">Course</div>
                        <div>{{ optional($invoice->lesson)->title ?? 'N/A' }}</div>
                    </div>
                </div>

                {{-- Line items --}}
                <table class="table table-sm mb-4">
                    <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-end">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ optional($invoice->lesson)->title ?? 'Course Fee' }}</td>
                        <td class="text-end">${{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                    </tbody>
                    <tfoot>
                    @if($invoice->tax > 0)
                        <tr>
                            <th class="text-end">Tax</th>
                            <th class="text-end">${{ number_format($invoice->tax, 2) }}</th>
                        </tr>
                    @endif
                    <tr>
                        <th class="text-end">Total</th>
                        <th class="text-end">${{ number_format($invoice->total, 2) }}</th>
                    </tr>
                    </tfoot>
                </table>

                {{-- Payments --}}
                <h6 class="mb-2">Payments</h6>
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Transaction ID</th>
                        <th class="text-end">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($invoice->payments as $payment)
                        <tr>
                            <td>{{ optional($payment->paid_at)->format('Y-m-d H:i') }}</td>
                            <td>{{ ucfirst($payment->method) }}</td>
                            <td>{{ $payment->reference }}</td>
                            <td class="text-end">${{ number_format($payment->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No payments recorded.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    @endsection
</x-admin-master>
