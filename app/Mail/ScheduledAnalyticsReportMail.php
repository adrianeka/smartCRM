<?php

namespace App\Mail;

use App\Models\AnalyticsReportDefinition;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduledAnalyticsReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly AnalyticsReportDefinition $report,
        public readonly string $path,
        public readonly string $format,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'SmartCRM Analytics: '.$this->report->name);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.scheduled-analytics-report',
            with: ['report' => $this->report],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk('local', $this->path)
                ->as(str($this->report->name)->slug().'.'.$this->format),
        ];
    }
}
