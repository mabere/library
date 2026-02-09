<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use App\Models\RequestHistory;
use Illuminate\Http\Request;

class AuditHelper
{
    public static function logActivity(?object $user, string $action, ?object $subject = null, array $meta = [], ?Request $request = null): void
    {
        ActivityLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'meta' => $meta ?: null,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }

    public static function logRequestHistory(string $type, int $id, ?string $from, string $to, ?string $note, ?int $userId): void
    {
        RequestHistory::create([
            'request_type' => $type,
            'request_id' => $id,
            'from_status' => $from,
            'to_status' => $to,
            'note' => $note,
            'user_id' => $userId,
            'created_at' => now(),
        ]);
    }
}
