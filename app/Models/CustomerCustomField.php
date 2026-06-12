<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerCustomField extends Model
{
    protected $fillable = [
        'customer_id',
        'field_key',
        'field_type',
        'field_options',
        'field_value',
        'field_date',
        'field_boolean',
        'file_path',
    ];

    protected $casts = [
        'field_options' => 'array',
        'field_date' => 'date',
        'field_boolean' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
