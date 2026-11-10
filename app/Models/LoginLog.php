<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $fillable = ['user_id', 'email', 'role', 'event', 'ip_address', 'user_agent'];

    public function user() { return $this->belongsTo(User::class); }
}
