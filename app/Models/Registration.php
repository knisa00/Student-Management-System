<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'course_id', 'status'];

    public function student() {
        return $this->belongsTo(\App\Models\Student::class);
    }

    public function course() {
        return $this->belongsTo(\App\Models\Course::class);
    }
}