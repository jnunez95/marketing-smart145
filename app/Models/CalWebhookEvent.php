<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalWebhookEvent extends Model
{
    protected $fillable = [
        'trigger_event',
        'booking_uid',
        'payload',
        'status',
        'event_time',
    ];

    protected $casts = [
        'payload'    => 'array',
        'event_time' => 'datetime',
    ];

    // Status constants
    const STATUS_RECEIVED  = 'received';
    const STATUS_PROCESSED = 'processed';
    const STATUS_FAILED    = 'failed';

    // Payload helpers
    public function getOrganizerAttribute(): ?array
    {
        return $this->payload['organizer'] ?? null;
    }

    public function getAttendeesAttribute(): array
    {
        return $this->payload['attendees'] ?? [];
    }

    public function isPing(): bool
    {
        return $this->trigger_event === 'PING';
    }
}