<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnalyticsReportDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'dataset',
        'columns',
        'filters',
        'branding',
        'visibility',
    ];

    protected $casts = [
        'columns' => 'array',
        'filters' => 'array',
        'branding' => 'array',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(AnalyticsReportSchedule::class, 'report_definition_id');
    }

    public function runs(): HasMany
    {
        return $this->hasMany(AnalyticsReportRun::class, 'report_definition_id');
    }
}
