<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationLog extends Model
{
    protected $fillable = [
        'letter_id',
        'token',
        'status',
        'method',
        'ip_address',
        'user_agent',
    ];

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }
}
