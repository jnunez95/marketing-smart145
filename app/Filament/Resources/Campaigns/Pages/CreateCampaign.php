<?php

namespace App\Filament\Resources\Campaigns\Pages;

use App\Filament\Resources\Campaigns\CampaignResource;
use App\Models\Campaign;
use Filament\Resources\Pages\CreateRecord;

class CreateCampaign extends CreateRecord
{
    protected static string $resource = CampaignResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = ! empty($data['scheduled_at'])
            ? Campaign::STATUS_SCHEDULED
            : Campaign::STATUS_DRAFT;
        $data['created_by'] = auth()->id();

        return $data;
    }
}
