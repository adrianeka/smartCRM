<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Customer extends Model
{
    use HasFactory, LogsActivity;

    // Daftarkan kolom-kolom yang boleh diisi secara manual
protected $fillable = [
    'customer_code',
    'full_name',
    'email',
    'phone',
    'company_name',
    'status',
    'custom_fields',
];

protected $casts = [
    'custom_fields' => 'array',
];


public function activityLogs()
{
    return $this->morphMany(
        \Spatie\Activitylog\Models\Activity::class,
        'subject'
    );
}


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() // Otomatis mencatat semua kolom yang ada di $fillable
            ->logOnlyDirty() // Hanya mencatat kolom yang nilainya benar-benar berubah (biar hemat storage)
            ->dontSubmitEmptyLogs(); // Jangan simpan log kalau tidak ada perubahan data
    }
}
