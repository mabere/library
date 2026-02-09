<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'request_type',
        'request_id',
        'from_status',
        'to_status',
        'note',
        'user_id',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
