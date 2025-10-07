<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                        
                        TextInput::make('category')
                            ->maxLength(255),
                        
                        Toggle::make('is_available')
                            ->label('Available')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Pricing')
                    ->schema([
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01),
                        
                        TextInput::make('special_price')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01)
                            ->helperText('Leave empty if no special price'),
                        
                        TextInput::make('hot_price')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01)
                            ->helperText('Price for hot variant, falls back to base price'),
                        
                        TextInput::make('cold_price')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01)
                            ->helperText('Price for cold variant, falls back to base price'),
                    ])
                    ->columns(2),

                Section::make('Product Images')
                    ->schema([
                        Repeater::make('images')
                            ->relationship('images')
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Image')
                                    ->image()
                                    ->directory('products')
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

                Section::make('Ingredients')
                    ->schema([
                        Repeater::make('ingredients')
                            ->relationship('ingredients')
                            ->schema([
                                TextInput::make('ingredient_name')
                                    ->label('Ingredient Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(2),
                                
                                TextInput::make('extra_price')
                                    ->label('Extra Price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0)
                                    ->step(0.01),
                                
                                Toggle::make('is_available')
                                    ->label('Available')
                                    ->default(true),
                            ])
                            ->columns(4)
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->defaultItems(0)
                            ->addActionLabel('Add Ingredient'),
                    ]),
            ]);
    }
}
