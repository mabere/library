<?php

namespace App\Models;

use App\Models\RequestHistory;
use App\Enums\BebasPustakaStatus;
use Illuminate\Database\Eloquent\Model;

class BebasPustakaRequest extends Model
{
    protected $fillable = [
        'user_id',
        'student_id',
        'letter_id',
        'nim',
        'nama',
        'prodi',
        'status',
        'submitted_at',
        'has_fine',
        'fine_note',
        'rejection_note',
        'verified_by',
        'verified_at',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'has_fine' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function histories()
    {
        return $this->hasMany(RequestHistory::class, 'request_id')
            ->where('request_type', 'bebas_pustaka')
            ->orderBy('created_at');
    }

    public function statusEnum(): BebasPustakaStatus
    {
        return BebasPustakaStatus::from($this->status);
    }
}
