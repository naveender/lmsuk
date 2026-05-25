<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperAssignment extends Model
{
    protected $fillable = [
        'paper_id',
        'assign_type',
        'assign_mode',
        'target_id',
        'target_value',
    ];

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }
}
