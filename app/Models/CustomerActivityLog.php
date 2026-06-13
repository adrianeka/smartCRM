<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerActivityLog extends Model
{
    protected $fillable = ['customer_id', 'activity_type', 'description', 'causer'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
