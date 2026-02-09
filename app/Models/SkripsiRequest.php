<?php

namespace App\Models;

use App\Enums\SkripsiStatus;
use App\Models\RequestHistory;
use Illuminate\Database\Eloquent\Model;

class SkripsiRequest extends Model
{
    protected $fillable = [
        'user_id',
        'student_id',
        'letter_id',
        'nim',
        'nama',
        'prodi',
        'judul_skripsi',
        'tahun_lulus',
        'status',
        'submitted_at',
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
            ->where('request_type', 'skripsi')
            ->orderBy('created_at');
    }

    public function statusEnum(): SkripsiStatus
    {
        return SkripsiStatus::from($this->status);
    }

}
