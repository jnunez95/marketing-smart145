<?php

namespace App\Filament\Resources\Stations\Tables;

use App\Models\Group;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class StationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('agency_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('city')
                    ->label('City, State')
                    ->formatStateUsing(fn (?string $state, \App\Models\Station $record): string => trim(implode(', ', array_filter([
                        $record->city,
                        $record->state_province,
                    ]))) ?: '—')
                    ->searchable(query: function (\Illuminate\Database\Eloquent\Builder $query, string $search): \Illuminate\Database\Eloquent\Builder {
                        return $query->where(function (\Illuminate\Database\Eloquent\Builder $q) use ($search): void {
                            $q->where('city', 'like', "%{$search}%")
                                ->orWhere('state_province', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(query: function (\Illuminate\Database\Eloquent\Builder $query, string $direction): \Illuminate\Database\Eloquent\Builder {
                        return $query->orderBy('city', $direction)->orderBy('state_province', $direction);
                    }),
                TextColumn::make('country')
                    ->label('Country')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cert_no')->searchable()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('group.name')
                    ->label('Group')
                    ->formatStateUsing(function (?string $state, \App\Models\Station $record): string|HtmlString {
                        $group = $record->group;
                        if (! $group) {
                            return $state ?? '—';
                        }
                        $color = $group->color;
                        if (! $color) {
                            return $group->name ?? $state ?? '—';
                        }
                        $hex = str_starts_with($color, '#') ? $color : '#'.$color;
                        $name = e($group->name ?? $state ?? '');

                        return new HtmlString(
                            '<span style="display:inline-block;padding:0.2em 0.5em;border-radius:0.25rem;background-color:'.e($hex).';color:#fff;font-size:0.875rem">'.$name.'</span>'
                        );
                    })
                    ->html()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('only_with_email')
                    ->form([
                        Checkbox::make('exclude_no_email')
                            ->label('Exclude contacts without email')
                            ->default(true),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['exclude_no_email'] ?? true) {
                            $query->whereNotNull('email')->where('email', '!=', '');
                        }

                        return $query;
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['exclude_no_email'] ?? true) {
                            return 'Only with email';
                        }

                        return null;
                    }),
                SelectFilter::make('group_id')
                    ->relationship('group', 'name')
                    ->label('Group')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('country')
                    ->label('Country')
                    ->options(fn () => \App\Models\Station::query()->distinct()->pluck('country', 'country')->filter()->toArray()),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('assignGroup')
                        ->label('Assign to group')
                        ->icon('heroicon-o-user-group')
                        ->extraAttributes(['class' => 'hover:!bg-gray-100 dark:hover:!bg-gray-700'])
                        ->form([
                            Select::make('group_id')
                                ->label('Group')
                                ->options(Group::pluck('name', 'id'))
                                ->required()
                                ->searchable(),
                        ])
                        ->action(function (\App\Models\Station $record, array $data): void {
                            $record->update(['group_id' => $data['group_id']]);
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
                    BulkAction::make('assignGroup')
                        ->label('Assign to group')
                        ->icon('heroicon-o-user-group')
                        ->form([
                            Select::make('group_id')
                                ->label('Group')
                                ->options(Group::pluck('name', 'id'))
                                ->required()
                                ->searchable(),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                            $records->each->update(['group_id' => $data['group_id']]);
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
