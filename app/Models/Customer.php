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
        'job_title',
        'email',
        'website',
        'phone',
        'whatsapp',
        'company_name',
        'industry',
        'identity_number',
        'tax_number',
        'gender',
        'birth_date',
        'address',
        'city',
        'province',
        'postal_code',
        'country',
        'status',
        'customer_type',
        'source',
        'lead_score',
        'preferred_contact_method',
        'last_contacted_at',
        'next_follow_up_at',
        'notes',
        'is_favorite',
        'assigned_user_id',
        'custom_fields',
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'birth_date' => 'date',
        'last_contacted_at' => 'datetime',
        'next_follow_up_at' => 'datetime',
        'is_favorite' => 'boolean',
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

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class);
    }

    public function notes()
{
    return $this->hasMany(CustomerNote::class);
}
}
