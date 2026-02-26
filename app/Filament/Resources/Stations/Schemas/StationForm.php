<?php

namespace App\Filament\Resources\Stations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class StationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('General Information')
                            ->schema([
                                TextInput::make('agency_name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                TextInput::make('dsgn_code')->maxLength(255),
                                TextInput::make('dba')->maxLength(255),
                                TextInput::make('cert_no')->required()->maxLength(255),
                            ]),
                        Tab::make('Address')
                            ->schema([
                                TextInput::make('address_line_1')->maxLength(255),
                                TextInput::make('address_line_2')->maxLength(255),
                                TextInput::make('address_line_3')->maxLength(255),
                                TextInput::make('city')->maxLength(255),
                                TextInput::make('state_province')->maxLength(255),
                                TextInput::make('country')->maxLength(255),
                                TextInput::make('postal_code')->maxLength(255),
                            ]),
                        Tab::make('Contacts')
                            ->schema([
                                TextInput::make('phone')->tel()->maxLength(255),
                                TextInput::make('email')->email()->maxLength(255),
                                TextInput::make('accountable_manager')->maxLength(255),
                                TextInput::make('accountable_manager_phone')->tel()->maxLength(255),
                                TextInput::make('accountable_manager_email')->email()->maxLength(255),
                                TextInput::make('liaison')->maxLength(255),
                                TextInput::make('liaison_phone')->tel()->maxLength(255),
                                TextInput::make('liaison_email')->email()->maxLength(255),
                            ]),
                        Tab::make('Ratings & Certifications')
                            ->schema([
                                TextInput::make('rating_accessory')->maxLength(255),
                                TextInput::make('rating_airframe')->maxLength(255),
                                TextInput::make('rating_instrument')->maxLength(255),
                                TextInput::make('rating_limited')->maxLength(255),
                                TextInput::make('rating_powerplant')->maxLength(255),
                                TextInput::make('rating_propeller')->maxLength(255),
                                TextInput::make('rating_radio')->maxLength(255),
                                TextInput::make('bilateral_agreements')->maxLength(255),
                            ]),
                        Tab::make('Location')
                            ->schema([
                                FileUpload::make('image_path')
                                    ->image()
                                    ->directory('stations')
                                    ->visibility('private'),
                                TextInput::make('latitude')
                                    ->numeric()
                                    ->step(0.0000001),
                                TextInput::make('longitude')
                                    ->numeric()
                                    ->step(0.0000001),
                                DatePicker::make('updated_at_source'),
                                Select::make('group_id')
                                    ->relationship('group', 'name')
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),
            ]);
    }
}
