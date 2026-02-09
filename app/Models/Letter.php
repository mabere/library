<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    protected $fillable = [
        'student_id',
        'letter_type',
        'letter_number',
        'status',
        'file_path',
        'has_fine',
        'fine_note',
        'verified_by',
        'verified_at',
        'token'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
