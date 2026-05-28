<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Daftarkan kolom-kolom yang boleh diisi secara manual
    protected $fillable = [
        'name', 
        'email', 
        'phone',
       
    ];
}