<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['course_code', 'section', 'title', 'credit_hours', 'max_students', 'semester', 'lecturer_id'];

    public function lecturer() {
        return $this->belongsTo(\App\Models\Lecturer::class);
    }

    public function registrations() {
        return $this->hasMany(\App\Models\Registration::class);
    }

    public function students() {
        return $this->belongsToMany(\App\Models\Student::class, 'registrations')
                    ->wherePivot('status', 'approved');
    }
}
