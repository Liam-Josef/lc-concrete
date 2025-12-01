<x-home-fullscreen-master>

    @section('page-title')
        Invoice: {{ $invoice->number }} | {{ $app->app_name ?? 'MEX LMS' }}
    @endsection

    @section('style')
        <style>
            .invoice-wrapper {
                max-width: 900px;
                margin: 0 auto;
                background: #fff;
                padding: 2rem 2.5rem;
                box-shadow: 0 0 20px rgba(0,0,0,0.08);
            }
            @media print {
                body {
                    background: #fff !important;
                }
                .no-print {
                    display: none !important;
                }
                .invoice-wrapper {
                    max-width: 100%;
                    box-shadow: none !important;
                    margin: 0 !important;
                    padding: 0.5in;
                }
                .white-back {
                    background: #fff !important;
                    padding: 0;
                }
            }
        </style>
    @endsection

    @section('content')
        <div class="white-back py-4">

            {{-- Top bar with back + print --}}
            <div class="d-flex justify-content-between align-items-center mb-3 no-print">
                <a href="{{ route('user.account', $user->id) }}" class="btn btn-sm btn-outline-secondary">
                    &laquo; Back to My Account
                </a>
                <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-print me-1"></i> Print
                </button>
            </div>

            <div class="invoice-wrapper">

                {{-- Header: logo + app info + invoice meta --}}
                <div class="d-flex justify-content-between mb-4">
                    <div class="d-flex align-items-center">
                        @if($app?->logo)
                            <img src="{{ asset('storage/'.$app->logo) }}"
                                 alt="{{ $app->app_name }}"
                                 style="height:60px;"
                                 class="me-3">
                        @endif
                        <div>
                            <div class="fw-bold">
                                Merchants Exchange
                            </div>
                            <div class="">
                                200 SW Market St, Suite 190 <br>
                                Portland, OR 97201
                            </div>
                            @if($app?->company_email)
                                <div class="text-muted small">{{ $app->company_email }}</div>
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
                        @endif
                    </div>
                </div>

                {{-- Bill to + course --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="fw-bold mb-1">Bill To</div>
                        <div>{{ $student->first_name }} {{ $student->last_name }}</div>
                        @if($student->company)
                            <div class="text-muted">{{ $student->company }}</div>
                        @endif
                        @if($student->email)
                            <div class="text-muted small">{{ $student->email }}</div>
                        @endif
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <div class="fw-bold mb-1">Course</div>
                        <div>{{ optional($invoice->lesson)->title ?? 'N/A' }}</div>
                    </div>
                </div>

                {{-- Line items (single course line for now) --}}
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
                    @forelse($invoice->payments as $pmt)
                        <tr>
                            <td>{{ optional($pmt->paid_at)->format('Y-m-d H:i') }}</td>
                            <td>{{ ucfirst($pmt->method) }}</td>
                            <td>{{ $pmt->reference }}</td>
                            <td class="text-end">${{ number_format($pmt->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted small">
                                No payments recorded for this invoice.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    @endsection

</x-home-fullscreen-master>
