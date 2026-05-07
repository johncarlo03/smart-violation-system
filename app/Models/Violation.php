<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    protected $fillable = [
        'student_id',
        'offense_id', // Add this
        'cso_id',
        'department_id',
        'description',
        'evidence_image',
        'status'
    ];

    public function incidents()
    {
        return $this->belongsTo(Offense::class);
    }

    public function offense()
    {
        return $this->belongsTo(Offense::class);
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // 2. The Officer who reported it
    public function cso()
    {
        return $this->belongsTo(User::class, 'cso_id');
    }

    // 3. The Department it belongs to
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
