<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Information')
                    ->schema([
                        TextEntry::make('store.name')
                            ->label('Store')
                            ->url(fn ($record) => route('filament.backend.resources.stores.view', $record->store))
                            ->color('primary'),
                        
                        TextEntry::make('name')
                            ->size('lg')
                            ->weight('bold'),
                        
                        TextEntry::make('description')
                            ->columnSpanFull()
                            ->markdown(),
                        
                        TextEntry::make('category')
                            ->badge()
                            ->color('info'),
                        
                        TextEntry::make('is_available')
                            ->label('Availability')
                            ->badge()
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Available' : 'Unavailable')
                            ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                    ])
                    ->columns(2),

                Section::make('Pricing')
                    ->schema([
                        TextEntry::make('price')
                            ->money('USD')
                            ->size('lg')
                            ->weight('bold')
                            ->color('success'),
                        
                        TextEntry::make('special_price')
                            ->money('USD')
                            ->color('warning')
                            ->placeholder('No special price'),
                        
                        TextEntry::make('hot_price')
                            ->label('Hot Price')
                            ->money('USD')
                            ->placeholder('Uses base price'),
                        
                        TextEntry::make('cold_price')
                            ->label('Cold Price')
                            ->money('USD')
                            ->placeholder('Uses base price'),
                    ])
                    ->columns(4),

                Section::make('Product Images')
                    ->schema([
                        ImageEntry::make('primary_image')
                            ->label('Primary Image')
                            ->getStateUsing(function ($record) {
                                $primaryImage = $record->images()->where('is_primary', true)->first();
                                return $primaryImage ? asset('storage/' . $primaryImage->image_path) : null;
                            })
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

                Section::make('Ingredients')
                    ->schema([
                        RepeatableEntry::make('ingredients')
                            ->schema([
                                TextEntry::make('ingredient_name')
                                    ->label('Name'),
                                
                                TextEntry::make('extra_price')
                                    ->money('USD')
                                    ->label('Extra Price'),
                                
                                TextEntry::make('is_available')
                                    ->label('Available')
                                    ->badge()
                                    ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No')
                                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Timestamps')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime('M j, Y g:i A'),
                        
                        TextEntry::make('updated_at')
                            ->dateTime('M j, Y g:i A'),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}


