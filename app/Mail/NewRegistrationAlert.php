<?php

namespace App\Mail;

use App\Models\Lesson;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRegistrationAlert extends Mailable implements ShouldQueue
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
        return $this->subject('New Course Registration: '.$this->lesson->title)
            ->markdown('emails.registration.alert');
    }
}
