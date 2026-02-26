<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampaignLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'station_id',
        'email',
        'status',
        'opened_at',
        'clicked_at',
        'error_message',
        'tracking_token',
    ];

    protected function casts(): array
    {
        return [
            'opened_at'  => 'datetime',
            'clicked_at' => 'datetime',
        ];
    }

    public const STATUS_PENDING = 'pending';

    public const STATUS_SENT = 'sent';

    public const STATUS_FAILED = 'failed';

    public const STATUS_OPENED = 'opened';

    public const STATUS_CLICKED = 'clicked';

    public const STATUS_BOUNCED = 'bounced';

    public const STATUS_SPAM_COMPLAINT = 'spam_complaint';

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'station_id');
    }

    public function emailEvents(): HasMany
    {
        return $this->hasMany(EmailEvent::class);
    }
}
