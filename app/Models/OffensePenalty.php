<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class OffensePenalty extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['offense_id', 'level', 'penalty_description'];

    public function offense()
    {
        return $this->belongsTo(Offense::class);
    }
}
