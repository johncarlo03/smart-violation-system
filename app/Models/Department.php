<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    // This allows the Seeder to manually set the ID (1, 2, 3, 4)
    protected $fillable = ['id', 'name'];

    // If you want to link it to Users later
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public static function getCoursesFor($departmentId)
    {
        return match ((int) $departmentId) {
            1 => ['BSEE', 'BSME', 'BSCE', 'BSIE'], // Engineering
            2 => ['BEED', 'BSED'],                 // Education
            3 => ['BSTM', 'BSHM'],                 // ICT
            4 => ['BSIT', 'BIT'],                 // Hospitality
            default => [],
        };
    }
}