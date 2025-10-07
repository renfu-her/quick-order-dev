<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Store;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Information')
                    ->schema([
                        Select::make('store_id')
                            ->label('Store')
                            ->relationship('store', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')->required(),
                                Textarea::make('address')->required(),
                                TextInput::make('phone'),
                                TextInput::make('email')->email(),
                            ])
                            ->columnSpanFull(),
                        
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
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('products')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(5120) // 5MB
                                    ->downloadable()
                                    ->openable()
                                    ->required()
                                    ->helperText('Image will be automatically converted to WebP format and optimized')
                                    ->columnSpanFull()
                                    ->getUploadedFileNameForStorageUsing(
                                        fn($file): string => (string) str(Str::uuid7() . '.webp')
                                    )
                                    ->saveUploadedFileUsing(function ($file) {
                                        $manager = new ImageManager(new Driver());
                                        $image = $manager->read($file);
                                        $image->scale(width: 1200, height: 900);
                                        $filename = Str::uuid7()->toString() . '.webp';
                                        
                                        if (!file_exists(storage_path('app/public/products'))) {
                                            mkdir(storage_path('app/public/products'), 0755, true);
                                        }
                                        $image->toWebp(80)->save(storage_path('app/public/products/' . $filename));
                                        return 'products/' . $filename;
                                    })
                                    ->deleteUploadedFileUsing(function ($file) {
                                        if ($file) {
                                            Storage::disk('public')->delete($file);
                                        }
                                    }),
                                
                                TextInput::make('display_order')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->helperText('Lower numbers appear first')
                                    ->columnSpanFull(),
                                
                                Toggle::make('is_primary')
                                    ->label('Primary Image')
                                    ->default(false)
                                    ->helperText('Only one image should be primary')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull()
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->defaultItems(0)
                            ->addActionLabel('Add Image'),
                    ])
                    ->columnSpanFull(),

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
