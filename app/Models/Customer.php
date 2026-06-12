<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\HasMany;

=======
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\CustomerAttachment;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
>>>>>>> 92117236d2a356558d4a3ba2b67bdfc2a5dc0e2e
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
<<<<<<< HEAD
    ];
=======
        'custom_fields',
        'is_favorite',
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'is_favorite' => 'boolean',
    ];

    public function activityLogs()
    {
        return $this->morphMany(
            \Spatie\Activitylog\Models\Activity::class,
            'subject'
        );
    }
>>>>>>> 92117236d2a356558d4a3ba2b67bdfc2a5dc0e2e

    // Relasi ke Kolom Fleksibel
    public function customFields(): HasMany
    {
<<<<<<< HEAD
        return $this->hasMany(CustomerCustomField::class);
    }

    // Relasi ke Catatan Aktivitas
    public function activityLogs(): HasMany
    {
        return $this->hasMany(CustomerActivityLog::class);
    }

    // Otomatis mencatat riwayat kronologis saat ada aktivitas CRUD
    protected static function booted(): void
    {
        static::created(function ($customer) {
            $customer->activityLogs()->create([
                'activity_type' => 'Created',
                'description' => 'Pelanggan baru berhasil didaftarkan ke sistem.',
                'causer' => auth()->user()?->name ?? 'System',
            ]);
        });

        static::updated(function ($customer) {
            // Mencari tahu kolom apa saja yang diubah oleh user
            $dirtyFields = array_keys($customer->getDirty());
            $changedList = implode(', ', $dirtyFields);

            $customer->activityLogs()->create([
                'activity_type' => 'Updated',
                'description' => "Melakukan pembaruan pada kolom data: [{$changedList}].",
                'causer' => auth()->user()?->name ?? 'System',
            ]);
        });
=======
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function attachments()
    {
        return $this->hasMany(
            CustomerAttachment::class
        );
>>>>>>> 92117236d2a356558d4a3ba2b67bdfc2a5dc0e2e
    }
}
