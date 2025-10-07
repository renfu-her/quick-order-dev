<?php

declare(strict_types=1);

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Store Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                        
                        TextInput::make('address')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(255)
                            ->regex('/^[\d+\-()#\s]+$/')
                            ->validationMessages([
                                'regex' => 'Phone number can only contain numbers, +, -, (), # and spaces.',
                            ]),
                        
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Location')
                    ->schema([
                        TextInput::make('latitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->placeholder('25.0330'),
                        
                        TextInput::make('longitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->placeholder('121.5654'),
                    ])
                    ->columns(2),

                Section::make('Operating Hours')
                    ->schema([
                        KeyValue::make('hours')
                            ->label('Business Hours')
                            ->keyLabel('Day')
                            ->valueLabel('Hours')
                            ->keyPlaceholder('Monday')
                            ->valuePlaceholder('09:00 - 21:00')
                            ->addActionLabel('Add Day')
                            ->columnSpanFull(),
                    ]),

                Section::make('Store Images')
                    ->schema([
                        Repeater::make('images')
                            ->relationship('images')
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Image')
                                    ->image()
                                    ->directory('stores')
                                    ->required()
                                    ->columnSpan(2),
                                
                                TextInput::make('display_order')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),
                                
                                Toggle::make('is_primary')
                                    ->label('Primary Image')
                                    ->default(false),
                            ])
                            ->columns(4)
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->defaultItems(0)
                            ->addActionLabel('Add Image'),
                    ]),
            ]);
    }
}

