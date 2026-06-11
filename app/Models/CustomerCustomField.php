<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerCustomField extends Model
{
    protected $fillable = ['customer_id', 'field_key', 'field_value'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
