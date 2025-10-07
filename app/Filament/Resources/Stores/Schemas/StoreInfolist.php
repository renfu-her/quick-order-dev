<?php

declare(strict_types=1);

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StoreInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Store Information')
                    ->schema([
                        TextEntry::make('name')
                            ->size('lg')
                            ->weight('bold'),
                        
                        TextEntry::make('description')
                            ->columnSpanFull(),
                        
                        TextEntry::make('address'),
                        
                        TextEntry::make('phone')
                            ->url(fn ($state) => "tel:{$state}"),
                        
                        TextEntry::make('email')
                            ->copyable(),
                        
                        TextEntry::make('is_active')
                            ->badge()
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive')
                            ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                    ])
                    ->columns(2),

                Section::make('Location')
                    ->schema([
                        TextEntry::make('latitude'),
                        TextEntry::make('longitude'),
                    ])
                    ->columns(2),

                Section::make('Operating Hours')
                    ->schema([
                        KeyValueEntry::make('hours')
                            ->label('Business Hours')
                            ->columnSpanFull(),
                    ]),

                Section::make('Store Images')
                    ->schema([
                        ImageEntry::make('primary_image')
                            ->label('Primary Image')
                            ->circular(false)
                            ->size(200),
                        
                        ImageEntry::make('images')
                            ->label('All Images')
                            ->getStateUsing(function ($record) {
                                return $record->images->map(function ($image) {
                                    return asset('storage/' . $image->image_path);
                                })->toArray();
                            })
                            ->circular(false)
                            ->size(100),
                    ]),
            ]);
    }
}

