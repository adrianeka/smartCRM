<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAttachment extends Model
{
    protected $fillable = [
        'customer_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
