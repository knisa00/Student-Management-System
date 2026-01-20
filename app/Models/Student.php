<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'matric_no', 'programme', 'year', 'full_name', 'semester'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function registrations() {
        return $this->hasMany(\App\Models\Registration::class);
    }

    public function courses() {
        return $this->belongsToMany(\App\Models\Course::class, 'registrations');
    }
}

