<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Offense extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['name', 'category'];
    public function penalties()
{
    // One offense has many possible penalty levels
    return $this->hasMany(OffensePenalty::class, 'offense_id')->orderBy('level', 'asc');
}
}
