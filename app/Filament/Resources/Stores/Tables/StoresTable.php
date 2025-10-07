<?php

declare(strict_types=1);

namespace App\Filament\Resources\Stores\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StoresTable
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
                
                TextColumn::make('phone')
                    ->searchable()
                    ->copyable(),
                
                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),
                
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All stores')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
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

