<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailEvent extends Model
{
    protected $fillable = [
        'message_id',
        'record_type',
        'recipient',
        'tag',
        'campaign_log_id',
        'event_at',
        'metadata',
        'geo_country',
        'geo_country_code',
        'geo_region',
        'geo_city',
        'geo_ip',
        'bounce_type',
        'bounce_type_code',
        'original_link',
        'platform',
        'user_agent',
        'os_name',
        'client_name',
        'raw_payload',
    ];

    protected function casts(): array
    {
        return [
            'event_at'    => 'datetime',
            'metadata'    => 'array',
            'raw_payload' => 'array',
        ];
    }

    public const RECORD_DELIVERY = 'Delivery';

    public const RECORD_BOUNCE = 'Bounce';

    public const RECORD_OPEN = 'Open';

    public const RECORD_CLICK = 'Click';

    public const RECORD_SPAM_COMPLAINT = 'SpamComplaint';

    public function campaignLog(): BelongsTo
    {
        return $this->belongsTo(CampaignLog::class);
    }

    public function scopeDeliveries($query)
    {
        return $query->where('record_type', self::RECORD_DELIVERY);
    }

    public function scopeOpens($query)
    {
        return $query->where('record_type', self::RECORD_OPEN);
    }

    public function scopeClicks($query)
    {
        return $query->where('record_type', self::RECORD_CLICK);
    }

    public function scopeBounces($query)
    {
        return $query->where('record_type', self::RECORD_BOUNCE);
    }

    public function scopeSpamComplaints($query)
    {
        return $query->where('record_type', self::RECORD_SPAM_COMPLAINT);
    }

    public function scopeWithGeo($query)
    {
        return $query->whereNotNull('geo_country');
    }
}
