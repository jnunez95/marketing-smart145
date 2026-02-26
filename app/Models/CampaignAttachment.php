<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
