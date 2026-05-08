<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffensePenalty extends Model
{
    protected $fillable = ['offense_id', 'level', 'penalty_description'];

    public function offense()
    {
        return $this->belongsTo(Offense::class);
    }
}
