<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsReportRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_definition_id',
        'requested_by',
        'schedule_id',
        'format',
        'status',
        'file_disk',
        'file_path',
        'completed_at',
        'error_message',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function reportDefinition(): BelongsTo
    {
        return $this->belongsTo(AnalyticsReportDefinition::class, 'report_definition_id');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(AnalyticsReportSchedule::class, 'schedule_id');
    }
}
