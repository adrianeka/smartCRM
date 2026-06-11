<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'full_name',
        'email',
        'phone',
        'company_name',
        'status',
    ];

    // Relasi ke Kolom Fleksibel
    public function customFields(): HasMany
    {
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
    }
}
