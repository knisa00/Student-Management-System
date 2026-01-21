<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'staff_no', 'department', 'full_name'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function courses() {
        return $this->hasMany(\App\Models\Course::class);
    }

    // Ensure lecturer full_name is saved in uppercase
    public function setFullNameAttribute($value)
    {
        $this->attributes['full_name'] = $value ? mb_strtoupper($value) : $value;
    }
}

