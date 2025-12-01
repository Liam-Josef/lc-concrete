@component('mail::message')
    # Thanks for registering, {{ $user->name }}!

    You’re registered for {{ $lesson->title }}.

    @component('mail::panel')
        Invoice: {{ $invoice->number }}
        Total: ${{ number_format($invoice->total, 2) }}
        Status: {{ $invoice->paid ? 'Paid' : 'Unpaid' }}
        @if($payment)
            **Payment Ref:** {{ $payment->reference }}
            **Paid At:** {{ optional($payment->paid_at)->timezone(config('app.timezone'))->format('M j, Y g:ia') }}
        @endif
    @endcomponent

    @component('mail::button', ['url' => route('lessons.start', $lesson)])
        Start Lesson
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
