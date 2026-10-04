<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OffensePenalty;
use App\Models\Appeal;

class Violation extends Model
{
    protected $fillable = [
        'student_id',
        'offense_id',
        'cso_id',
        'department_id',
        'penalty_id',
        'description',
        'evidence_image',
        'status',
        'resolution_notes',
        'resolved_at',
    ];

    public function incidents()
    {
        return $this->belongsTo(Offense::class);
    }

    public function offense()
    {
        return $this->belongsTo(Offense::class);
    }

    public function penalty()
    {
        return $this->belongsTo(OffensePenalty::class, 'penalty_id');
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

    public function appeals()
    {
        return $this->hasMany(Appeal::class);
    }
}
