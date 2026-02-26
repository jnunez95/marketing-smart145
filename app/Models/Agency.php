<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Agency extends Model
{
    use HasFactory, HasSlug;

    protected $table = 'stations';

    protected $fillable = [
        'slug',
        'agency_name',
        'dsgn_code',
        'dba',
        'cert_no',
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'city',
        'state_province',
        'country',
        'postal_code',
        'phone',
        'email',
        'accountable_manager',
        'accountable_manager_phone',
        'accountable_manager_email',
        'liaison',
        'liaison_phone',
        'liaison_email',
        'rating_accessory',
        'rating_airframe',
        'rating_instrument',
        'rating_limited',
        'rating_powerplant',
        'rating_propeller',
        'rating_radio',
        'bilateral_agreements',
        'image_path',
        'latitude',
        'longitude',
        'updated_at_source',
        'group_id',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'updated_at_source' => 'date',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('agency_name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function campaignLogs(): HasMany
    {
        return $this->hasMany(CampaignLog::class);
    }
}
