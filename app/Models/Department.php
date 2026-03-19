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
            1 => ['BSEE', 'BSME', 'BSCE', 'BSIE'], 
            2 => ['BEED', 'BSED'],                 
            3 => ['BSTM', 'BSHM'],                 
            4 => ['BSIT', 'BIT'],                
            default => [],
        };
    }

    public function getAcronymAttribute()
{
    return match($this->id) {
        1 => 'COE',  // College of Engineering
        2 => 'CEAS', // College of Education
        3 => 'CME',// College of ICT
        4 => 'COT', // College of Hospitality Management
        default => 'DEPT',
    };
}
}