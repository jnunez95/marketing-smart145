<?php

namespace App\Filament\Resources\EmailTemplates\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmailTemplateForm
{
    public static function getVariablePlaceholders(): array
    {
        return [
            'agency_name',
            'email',
            'accountable_manager',
            'accountable_manager_email',
            'liaison',
            'liaison_email',
            'cert_no',
            'city',
            'country',
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        $variables = implode(', ', array_map(fn ($v) => '{'.$v.'}', self::getVariablePlaceholders()));

        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('subject')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Section::make('Available variables')
                    ->description('Use these variables in the content: '.$variables)
                    ->collapsed(),
                RichEditor::make('body_html')
                    ->required()
                    ->label('Body (HTML)')
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'link',
                        'bulletList',
                        'orderedList',
                        'h2',
                        'h3',
                        'blockquote',
                    ]),
                Textarea::make('body_plain')
                    ->label('Body (plain text)')
                    ->columnSpanFull()
                    ->rows(4),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
