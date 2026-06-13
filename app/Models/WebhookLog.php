<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    protected $fillable = [
        'event_type',
        'source_module',
        'target_url',
        'status_code',
        'payload',
        'response',
        'status',
    ];
}
