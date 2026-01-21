<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'full_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Ensure admin full_name is saved in uppercase
    public function setFullNameAttribute($value)
    {
        $this->attributes['full_name'] = $value ? mb_strtoupper($value) : $value;
    }
}