<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Customer extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'customer_code',
        'full_name',
        'email',
        'phone',
        'company_name',
        'status',
        'assigned_user_id',
        'custom_fields',
    ];

    protected $casts = [
        'custom_fields' => 'array',
    ];

    // Relasi ke Kolom Fleksibel (Custom Fields)
    public function customFields(): HasMany
    {
        return $this->hasMany(CustomerCustomField::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    // Menggunakan satu fungsi ActivityLogs yang terintegrasi dengan Spatie sesuai framework tim lu
    public function activityLogs()
    {
        return $this->morphMany(
            \Spatie\Activitylog\Models\Activity::class,
            'subject'
        );
    }

    // Pengaturan Log Otomatis Spatie
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relasi Tags bawaan develop
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    // Relasi Attachments bawaan develop
    public function attachments(): HasMany
    {
        return $this->hasMany(CustomerAttachment::class);
    }
}
