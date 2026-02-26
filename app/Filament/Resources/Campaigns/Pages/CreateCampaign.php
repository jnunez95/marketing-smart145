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
        $hasSchedules = !empty($data['campaignSchedules']) && is_array($data['campaignSchedules']);
        $data['status'] = $hasSchedules
            ? Campaign::STATUS_SCHEDULED
            : Campaign::STATUS_DRAFT;
        $data['created_by'] = auth()->id();

        return $data;
    }
}
