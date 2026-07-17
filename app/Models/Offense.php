<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offense extends Model
{
    protected $fillable = ['name', 'category'];
    public function penalties()
{
    // One offense has many possible penalty levels
    return $this->hasMany(OffensePenalty::class)->orderBy('level', 'asc');
}
}
