<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Daftarkan kolom-kolom yang boleh diisi secara manual
    protected $fillable = [
    'customer_code',
    'full_name', 
    'email', 
    'phone',
    'company_name',
    'status',
];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() // Otomatis mencatat semua kolom yang ada di $fillable
            ->logOnlyDirty() // Hanya mencatat kolom yang nilainya benar-benar berubah (biar hemat storage)
            ->dontSubmitEmptyLogs(); // Jangan simpan log kalau tidak ada perubahan data
    }
}