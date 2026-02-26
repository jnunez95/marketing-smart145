<?php

namespace App\Filament\Resources\Groups\Tables;

use App\Filament\Resources\Stations\StationResource;
use App\Models\Group;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class GroupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stations_count')
                    ->label('Stations')
                    ->counts('stations')
                    ->sortable(),
                TextColumn::make('color')
                    ->formatStateUsing(function (?string $state): HtmlString {
                        if (! $state) {
                            return new HtmlString('<span class="text-gray-400">—</span>');
                        }
                        $hex = str_starts_with($state, '#') ? $state : '#' . $state;
                        $bg = e($hex);
                        return new HtmlString(
                            '<span style="display:inline-block;padding:0.2em 0.5em;border-radius:0.25rem;background-color:'.$bg.';color:#fff;font-size:0.875rem">'.e($hex).'</span>'
                        );
                    })
                    ->html()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->color('gray')
                        ->extraAttributes(['class' => 'hover:!bg-gray-100 dark:hover:!bg-gray-700']),
                    Action::make('viewStations')
                        ->label('View stations')
                        ->icon('heroicon-o-building-office-2')
                        ->extraAttributes(['class' => 'hover:!bg-gray-100 dark:hover:!bg-gray-700'])
                        ->url(fn (Group $record): string => StationResource::getUrl('index') . '?group_id=' . $record->id),
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
