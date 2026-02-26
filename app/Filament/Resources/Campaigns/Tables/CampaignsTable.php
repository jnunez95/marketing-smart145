<?php

namespace App\Filament\Resources\Campaigns\Tables;

use App\Jobs\SendCampaignJob;
use App\Models\Campaign;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CampaignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('emailTemplate.name')
                    ->label('Template')
                    ->sortable(),
                TextColumn::make('group.name')
                    ->label('Group')
                    ->placeholder('All')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Campaign::STATUS_DRAFT => 'gray',
                        Campaign::STATUS_SCHEDULED => 'warning',
                        Campaign::STATUS_SENDING => 'info',
                        Campaign::STATUS_SENT => 'success',
                        Campaign::STATUS_FAILED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Campaign::STATUS_DRAFT => 'Draft',
                        Campaign::STATUS_SCHEDULED => 'Scheduled',
                        Campaign::STATUS_SENDING => 'Sending',
                        Campaign::STATUS_SENT => 'Sent',
                        Campaign::STATUS_FAILED => 'Failed',
                        default => $state,
                    }),
                TextColumn::make('total_recipients')
                    ->label('Recipients')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_sent')
                    ->label('Sent')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_opened')
                    ->label('Opened')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_clicked')
                    ->label('Clicks')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('scheduled_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sent_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        Campaign::STATUS_DRAFT => 'Draft',
                        Campaign::STATUS_SCHEDULED => 'Scheduled',
                        Campaign::STATUS_SENT => 'Sent',
                        Campaign::STATUS_FAILED => 'Failed',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('sendNow')
                        ->label('Send now')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('gray')
                        ->extraAttributes(['class' => 'hover:!bg-gray-100 dark:hover:!bg-gray-700'])
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
                        }),
                    EditAction::make()
                        ->color('gray')
                        ->extraAttributes(['class' => 'hover:!bg-gray-100 dark:hover:!bg-gray-700']),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
