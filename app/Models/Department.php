<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role_in_department')
            ->withTimestamps();
    }
}
