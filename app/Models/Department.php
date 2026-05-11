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

    public function violations()
    {
        return $this->hasManyThrough(Violation::class, User::class, 'department_id', 'student_id');
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

    public function getBadgeColorAttribute()
    {
        return match ($this->id) {
            1 => 'bg-red-100 text-red-700 border-red-200',   // COE
            2 => 'bg-blue-100 text-blue-700 border-blue-200', // CEAS
            3 => 'bg-green-100 text-green-700 border-green-200',  // CME
            4 => 'bg-yellow-100 text-yellow-700 border-yellow-200',  // College of Hospitality Management
            default => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    }

    public function getBarColorAttribute()
    {
        return match ($this->id) {
            1 => 'from-red-600 to-red-400',
            2 => 'from-blue-600 to-blue-400',
            3 => 'from-green-600 to-green-400',
            4 => 'from-yellow-500 to-yellow-300',
            default => 'from-gray-500 to-gray-300',
        };
    }

    public function getAcronymAttribute()
    {
        return match ($this->id) {
            1 => 'COE',  // College of Engineering
            2 => 'CEAS', // College of Education
            3 => 'CME',// College of ICT
            4 => 'COT', // College of Hospitality Management
            default => 'DEPT',
        };
    }
}