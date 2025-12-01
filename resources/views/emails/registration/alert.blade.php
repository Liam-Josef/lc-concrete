@component('mail::message')
    # New Course Registration

    **User:** {{ $user->name }} ({{ $user->email }})
    **Lesson:** {{ $lesson->title }}
    **Invoice:** {{ $invoice->number }}
    **Total:** ${{ number_format($invoice->total,2) }}
    **Paid:** {{ $invoice->paid ? 'Yes' : 'No' }}
    @if($payment)
        **Payment Ref:** {{ $payment->reference }}
    @endif

    @component('mail::button', ['url' => route('lesson.view', $lesson->id)])
        View Lesson (Admin)
    @endcomponent

@endcomponent
