<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'name',
        'nim',
        'department_id',
        'purpose',
        'visit_at',
        'isntitution',
        'visitor_type',
    ];

    protected $casts = [
        'visit_at' => 'datetime',
        'purpose' => 'array',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
