<?php

declare(strict_types=1);

namespace App\Filament\Resources\Coupons\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                
                TextColumn::make('discount_type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'fixed' => 'Fixed',
                        'percentage' => 'Percentage',
                        default => $state,
                    }),
                
                TextColumn::make('discount_value')
                    ->formatStateUsing(fn (string $state, $record): string => 
                        $record->discount_type === 'percentage' ? "{$state}%" : "\${$state}"
                    )
                    ->sortable(),
                
                TextColumn::make('min_purchase_amount')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('used_count')
                    ->label('Usage')
                    ->formatStateUsing(fn (string $state, $record): string => 
                        $record->usage_limit 
                            ? "{$state} / {$record->usage_limit}" 
                            : "{$state} / ∞"
                    )
                    ->sortable(),
                
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                
                TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All coupons')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
                
                SelectFilter::make('discount_type')
                    ->options([
                        'fixed' => 'Fixed Amount',
                        'percentage' => 'Percentage',
                    ]),
            ])
            ->recordActions([
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

