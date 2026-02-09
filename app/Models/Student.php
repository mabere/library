<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nim',
        'nama',
        'department_id',
        'judul_skripsi',
        'tahun_lulus',
        'user_id',
    ];

    public function letters()
    {
        return $this->hasMany(Letter::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
