<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use App\Models\Store;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('primary_image')
                    ->label('Image')
                    ->circular()
                    ->size(60),
                
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('store.name')
                    ->label('Store')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                
                TextColumn::make('category')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                
                TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                
                TextColumn::make('special_price')
                    ->money('USD')
                    ->sortable()
                    ->placeholder('—'),
                
                IconColumn::make('is_available')
                    ->boolean()
                    ->sortable(),
                
                TextColumn::make('ingredient_limit')
                    ->label('Ingredient Limit')
                    ->formatStateUsing(fn (int $state): string => $state === 0 ? 'Unlimited' : (string) $state)
                    ->badge()
                    ->color(fn (int $state): string => $state === 0 ? 'warning' : 'primary'),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('store_id')
                    ->options(Store::whereNotNull('name')
                        ->where('name', '!=', '')
                        ->whereNull('deleted_at')
                        ->pluck('name', 'id')
                        ->map(fn($name) => $name ?: 'Unknown Store')
                        ->toArray())
                    ->searchable(),
                
                SelectFilter::make('category')
                    ->options(fn () => Product::whereNotNull('category')
                        ->where('category', '!=', '')
                        ->pluck('category', 'category')
                        ->unique()
                        ->map(fn($category) => $category ?: 'Uncategorized')
                        ->toArray()),
                
                TernaryFilter::make('is_available')
                    ->label('Availability')
                    ->placeholder('All products')
                    ->trueLabel('Available only')
                    ->falseLabel('Unavailable only'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
