<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportGenerated extends Mailable
{
    use Queueable, SerializesModels;

    public $reportFields;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reportFields)
    {
        $this->reportFields = $reportFields;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.report-generated');
    }
}
