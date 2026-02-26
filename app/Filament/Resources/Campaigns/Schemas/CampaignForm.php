<?php

namespace App\Filament\Resources\Campaigns\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('email_template_id')
                            ->relationship('emailTemplate', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('group_id')
                            ->relationship('group', 'name')
                            ->label('Group')
                            ->helperText('Leave empty to send to all stations.')
                            ->searchable()
                            ->preload(),
                    ]),
                Section::make('Schedule')
                    ->schema([
                        DateTimePicker::make('scheduled_at')
                            ->label('Schedule send for')
                            ->native(false),
                    ])
                    ->collapsed(),
            ]);
    }
}
