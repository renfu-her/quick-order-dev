<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Details')
                    ->schema([
                        TextEntry::make('order_number')
                            ->copyable(),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'gray',
                                'confirmed' => 'info',
                                'preparing' => 'warning',
                                'ready' => 'success',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('payment_status')
                            ->badge(),
                        TextEntry::make('payment_method'),
                        TextEntry::make('created_at')
                            ->dateTime(),
                    ])
                    ->columns(3),

                Section::make('Customer Information')
                    ->schema([
                        TextEntry::make('customer_name'),
                        TextEntry::make('customer_phone'),
                        TextEntry::make('customer_email'),
                        TextEntry::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('Order Summary')
                    ->schema([
                        TextEntry::make('subtotal')
                            ->money('USD'),
                        TextEntry::make('discount_amount')
                            ->money('USD'),
                        TextEntry::make('total_amount')
                            ->money('USD')
                            ->weight('bold'),
                        TextEntry::make('coupon.code')
                            ->label('Coupon Used')
                            ->placeholder('No coupon'),
                    ])
                    ->columns(4),

                Section::make('Order Items')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                TextEntry::make('product_name'),
                                TextEntry::make('quantity'),
                                TextEntry::make('temperature')
                                    ->badge(),
                                TextEntry::make('unit_price')
                                    ->money('USD'),
                                TextEntry::make('subtotal')
                                    ->money('USD'),
                            ])
                            ->columns(5),
                    ]),
            ]);
    }
}

