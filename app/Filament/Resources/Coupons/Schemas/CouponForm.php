<?php

declare(strict_types=1);

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Coupon Information')
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->uppercase()
                            ->placeholder('SUMMER2024'),
                        
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Discount Settings')
                    ->schema([
                        Select::make('discount_type')
                            ->options([
                                'fixed' => 'Fixed Amount',
                                'percentage' => 'Percentage',
                            ])
                            ->required()
                            ->default('fixed')
                            ->live(),
                        
                        TextInput::make('discount_value')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix(fn ($get) => $get('discount_type') === 'fixed' ? '$' : '')
                            ->suffix(fn ($get) => $get('discount_type') === 'percentage' ? '%' : '')
                            ->step(0.01),
                        
                        TextInput::make('min_purchase_amount')
                            ->label('Minimum Purchase Amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->step(0.01)
                            ->helperText('Minimum cart value to use this coupon'),
                        
                        TextInput::make('max_discount_amount')
                            ->label('Maximum Discount Amount')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->helperText('Leave empty for no maximum limit'),
                    ])
                    ->columns(2),

                Section::make('Usage Limits')
                    ->schema([
                        TextInput::make('usage_limit')
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Leave empty for unlimited usage'),
                        
                        TextInput::make('used_count')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false),
                        
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

