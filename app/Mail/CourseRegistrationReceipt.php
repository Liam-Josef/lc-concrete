<?php

namespace App\Mail;

use App\Models\Lesson;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // queueable
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CourseRegistrationReceipt extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Lesson $lesson,
        public Invoice $invoice,
        public ?Payment $payment = null
    ) {}

    public function build()
    {
        return $this->subject('Receipt: '.$this->lesson->title.' (Invoice '.$this->invoice->number.')')
            ->markdown('emails.registration.receipt');
    }
}
