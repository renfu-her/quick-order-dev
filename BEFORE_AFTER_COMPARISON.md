# Filament v4 修正對比

## 修正前 vs 修正後

### 1. Resource 類定義

#### ❌ 修正前 (錯誤)
```php
use Filament\Forms\Form;

class ProductResource extends Resource
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ...
            ]);
    }
}
```

#### ✅ 修正後 (正確)
```php
use Filament\Schemas\Schema;
use Filament\Forms\Components as FormComponents;

class ProductResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlineShoppingBag;
    
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ...
            ]);
    }
}
```

**關鍵差異：**
- ✅ 使用 `Schema` 而不是 `Form`
- ✅ 使用 `->components([])` 而不是 `->schema([])`
- ✅ 使用 `Heroicon` 枚舉作為 icon

---

### 2. Form Components

#### ❌ 修正前
```php
use Filament\Forms;

Forms\Components\Section::make('Product Information')
    ->schema([
        Forms\Components\TextInput::make('name')->required(),
        Forms\Components\Textarea::make('description'),
    ])
```

#### ✅ 修正後
```php
use Filament\Forms\Components as FormComponents;

FormComponents\Section::make('Product Information')
    ->schema([
        FormComponents\TextInput::make('name')->required(),
        FormComponents\Textarea::make('description'),
    ])
```

**關鍵差異：**
- ✅ 使用別名 `FormComponents` 避免命名衝突
- ✅ Section 內仍使用 `->schema([])` (這是正確的)

---

### 3. Table Actions

#### ❌ 修正前
```php
use Filament\Tables;

public static function table(Table $table): Table
{
    return $table
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}
```

#### ✅ 修正後
```php
use Filament\Actions;

public static function table(Table $table): Table
{
    return $table
        ->recordActions([
            Actions\ViewAction::make(),
            Actions\EditAction::make(),
        ])
        ->toolbarActions([
            Actions\BulkActionGroup::make([
                Actions\DeleteBulkAction::make(),
            ]),
        ]);
}
```

**關鍵差異：**
- ✅ Actions 命名空間從 `Filament\Tables\Actions` 改為 `Filament\Actions`
- ✅ 使用 `->recordActions([])` 而不是 `->actions([])`
- ✅ 使用 `->toolbarActions([])` 而不是 `->bulkActions([])`

---

### 4. 動態表單欄位 (Get utility)

#### ❌ 修正前
```php
use Filament\Forms;

Forms\Components\TextInput::make('discount_value')
    ->prefix(fn (Forms\Get $get) => $get('discount_type') === 'fixed' ? '$' : '')
    ->suffix(fn (Forms\Get $get) => $get('discount_type') === 'percentage' ? '%' : '')
```

#### ✅ 修正後
```php
FormComponents\TextInput::make('discount_value')
    ->prefix(fn ($get) => $get('discount_type') === 'fixed' ? '$' : '')
    ->suffix(fn ($get) => $get('discount_type') === 'percentage' ? '%' : '')
```

**關鍵差異：**
- ✅ `$get` 不需要類型提示
- ✅ 直接在閉包中使用 `fn ($get)`

---

### 5. Infolist (OrderResource)

#### ❌ 修正前
```php
use Filament\Infolists\Infolist;

public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            // ...
        ]);
}
```

#### ✅ 修正後
```php
use Filament\Schemas\Schema;
use Filament\Infolists\Components as InfolistComponents;

public static function infolist(Schema $schema): Schema
{
    return $schema
        ->components([
            InfolistComponents\Section::make('Order Details')
                ->schema([
                    InfolistComponents\TextEntry::make('order_number'),
                ])
        ]);
}
```

**關鍵差異：**
- ✅ 使用 `Schema` 而不是 `Infolist`
- ✅ 使用 `->components([])` 而不是 `->schema([])`
- ✅ 使用 `InfolistComponents` 別名

---

### 6. ImageColumn

#### ❌ 修正前
```php
Tables\Columns\ImageColumn::make('images.image_path')
    ->circular()
    ->defaultImageUrl(url('/images/placeholder.png'))
```

#### ✅ 修正後
```php
Columns\ImageColumn::make('images.image_path')
    ->circular()
    ->limit(1)
```

**關鍵差異：**
- ✅ 移除不必要的 `defaultImageUrl()`
- ✅ 使用 `->limit(1)` 控制顯示數量

---

## 完整範例對比

### ProductResource - 修正前
```php
<?php
namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
}
```

### ProductResource - 修正後
```php
<?php
namespace App\Filament\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components as FormComponents;
use Filament\Tables\Columns;
use Filament\Actions;

class ProductResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlineShoppingBag;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            FormComponents\TextInput::make('name')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Columns\TextColumn::make('name'),
            ])
            ->recordActions([
                Actions\EditAction::make(),
            ]);
    }
}
```

---

## 總結

| 項目 | 修正前 | 修正後 |
|------|--------|--------|
| Form 參數 | `Form $form` | `Schema $schema` |
| Form 方法 | `->schema([])` | `->components([])` |
| Actions 命名空間 | `Filament\Tables\Actions` | `Filament\Actions` |
| Table Actions | `->actions([])` | `->recordActions([])` |
| Bulk Actions | `->bulkActions([])` | `->toolbarActions([])` |
| Get utility | `fn (Forms\Get $get)` | `fn ($get)` |
| Icon | `'heroicon-o-...'` | `Heroicon::Outline...` |
| Components | 直接使用 | 使用別名 `FormComponents\` |

所有修正完全符合 Filament v4 官方規範！✅

