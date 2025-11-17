<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     private $otp;
    public function __construct($otp)
    {
        //
        $this->otp = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@prakashprabhaw.com')->subject('Password Reset Request - Amrit Bharat')->view('email.send-otp',['otp'=>$this->otp]);
    }
}
