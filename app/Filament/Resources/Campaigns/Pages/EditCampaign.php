<?php

namespace App\Filament\Resources\Campaigns\Pages;

use App\Filament\Resources\Campaigns\CampaignResource;
use App\Jobs\SendCampaignJob;
use App\Models\Campaign;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCampaign extends EditRecord
{
    protected static string $resource = CampaignResource::class;

    protected function afterSave(): void
    {
        $record = $this->getRecord();
        $hasPendingSchedules = $record->campaignSchedules()->whereNull('sent_at')->exists();
        if (!$hasPendingSchedules && $record->status === Campaign::STATUS_SCHEDULED) {
            $record->update(['status' => Campaign::STATUS_DRAFT]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendNow')
                ->label('Send now')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->visible(fn (Campaign $record): bool => in_array($record->status, [Campaign::STATUS_DRAFT, Campaign::STATUS_SCHEDULED], true))
                ->requiresConfirmation()
                ->modalHeading('Send campaign')
                ->modalDescription('Send this campaign now to all recipients?')
                ->action(function (Campaign $record): void {
                    SendCampaignJob::dispatch($record);
                    $driver = config('queue.default');
                    Notification::make()
                        ->title('Campaign queued')
                        ->body($driver === 'sync'
                            ? 'The campaign is being sent now.'
                            : 'The campaign is queued. Run "php artisan queue:work" so emails are sent.')
                        ->success()
                        ->send();
                    $this->redirect(CampaignResource::getUrl('index'));
                }),
            DeleteAction::make(),
        ];
    }
}
