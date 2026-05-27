<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'source_module',
        'priority',
        'is_read',
        'read_at',
        'action_url'
    ];
}
