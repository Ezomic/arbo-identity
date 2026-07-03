<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogger
{
    public function __construct(private readonly Request $request) {}

    public function log(string $event, ?string $userId, array $payload = []): void
    {
        $data = [
            'user_id'    => $userId,
            'event'      => $event,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'payload'    => $payload ?: null,
        ];

        $data['checksum'] = hash_hmac('sha256', json_encode($data), config('app.key'));

        ActivityLog::create($data);
    }
}
