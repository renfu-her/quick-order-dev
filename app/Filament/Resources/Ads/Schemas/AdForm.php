<?php

declare(strict_types=1);

namespace App\Filament\Resources\Ads\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AdForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ad Information')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        FileUpload::make('image')
                            ->image()
                            ->directory('ads')
                            ->columnSpanFull(),
                        
                        TextInput::make('link')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com'),
                        
                        Select::make('product_id')
                            ->label('Related Product')
                            ->options(Product::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])
                    ->columns(2),

                Section::make('Settings')
                    ->schema([
                        TextInput::make('display_order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Lower numbers appear first'),
                        
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        
                        DateTimePicker::make('starts_at')
                            ->label('Start Date & Time')
                            ->nullable(),
                        
                        DateTimePicker::make('ends_at')
                            ->label('End Date & Time')
                            ->nullable()
                            ->after('starts_at'),
                    ])
                    ->columns(2),
            ]);
    }
}

