<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewReportNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function build()
    {
        return $this->subject('Laporan Kerusakan Baru Diterima')
                    ->view('emails.report_notification')
                    ->with(['report' => $this->report]);
    }
}
