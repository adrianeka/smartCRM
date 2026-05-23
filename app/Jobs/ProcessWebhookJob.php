<?php

namespace App\Jobs;

use App\Models\WebhookLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessWebhookJob implements ShouldQueue
{
    use Queueable;

    public $webhookData;

    public function __construct($webhookData)
    {
        $this->webhookData = $webhookData;
    }

    public function handle(): void
    {
        WebhookLog::create([
            'event_type' => $this->webhookData['event_type'],
            'source_module' => $this->webhookData['source_module'],
            'payload' => json_encode($this->webhookData),
            'status' => 'success',
        ]);
    }
}
