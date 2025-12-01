<x-admin-master>

    @section('page-title')
        All Transactions | MEX LMS Admin
    @endsection

    @section('content')
        <div class="row">
            <div class="col-sm-12">
                <h1 class="m-3">All Transactions</h1>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableTransactions" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Invoice #</th>
                            <th>Transaction ID</th>
                            <th>Method</th>
                            <th class="text-end">Amount</th>
                            <th>Paid At</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment)
                            @php
                                $invoice = $payment->invoice;
                                $student = optional($invoice)->student;
                                $lesson  = optional($invoice)->lesson;
                            @endphp
                            <tr>
                                <td>
                                    @if($student)
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td>{{ $lesson?->title ?? '—' }}</td>
                                <td>
                                    @if($invoice)
                                        <a href="{{ route('admin.invoices.show', $invoice->id) }}">
                                            {{ $invoice->number }}
                                        </a>
                                        @else
                                            &mdash;
                                    @endif
                                </td>
                                <td>{{ $payment->transaction_id ?? '—' }}</td>
                                <td>{{ ucfirst($payment->method) }}</td>
                                <td class="text-end">
                                    ${{ number_format($payment->amount, 2) }}
                                </td>
                                <td>
                                    {{ optional($payment->paid_at)->format('Y-m-d H:i') ?? '—' }}
                                </td>
                                <td class="text-center">
                                    @if($invoice)
                                        <a href="{{ route('admin.invoices.show', $invoice->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            View Invoice
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{-- If you want pagination links instead of DataTables only --}}
                    <div class="mt-3">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
        {{-- If you’re already initializing DataTables elsewhere you can keep that.
             Otherwise, simple init: --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (window.$ && $.fn.DataTable) {
                    $('#dataTableTransactions').DataTable();
                }
            });
        </script>
    @endsection

</x-admin-master>
