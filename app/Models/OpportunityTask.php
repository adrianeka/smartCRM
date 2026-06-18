<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpportunityTask extends Model
{
    protected $fillable = [
        'opportunity_id',
        'title',
        'due_date',
        'status'
    ];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }


}
