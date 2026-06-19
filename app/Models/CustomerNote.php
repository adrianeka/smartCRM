<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CustomerNote extends Model
{
   protected $fillable = [
    'customer_id',
    'user_id',
    'parent_id',
    'note'
];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
    return $this->belongsTo(CustomerNote::class, 'parent_id');
    }

    public function replies()
    {
    return $this->hasMany(CustomerNote::class, 'parent_id');
    }

}
