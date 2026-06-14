<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnalyticsReportSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_definition_id',
        'created_by',
        'frequency',
        'format',
        'recipients',
        'next_run_at',
        'last_run_at',
        'is_active',
    ];

    protected $casts = [
        'recipients' => 'array',
        'next_run_at' => 'datetime',
        'last_run_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function reportDefinition(): BelongsTo
    {
        return $this->belongsTo(AnalyticsReportDefinition::class, 'report_definition_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function runs(): HasMany
    {
        return $this->hasMany(AnalyticsReportRun::class, 'schedule_id');
    }

    public function nextRunAfter(CarbonInterface $date): CarbonInterface
    {
        return $this->frequency === 'monthly'
            ? $date->copy()->addMonth()
            : $date->copy()->addWeek();
    }
}
