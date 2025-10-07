# ✅ Filament v4 Section 命名空間修正

## 🐛 問題

錯誤訊息:
```
Class "Filament\Forms\Components\Section" not found
Use of unknown class: 'Filament\Forms\Components\Section'
```

## 🔍 原因

在 **Filament v4** 中，`Section` 組件已經從各自的命名空間移到了統一的 Schema 命名空間。

### ❌ 錯誤的命名空間 (v3)

```php
use Filament\Forms\Components\Section;      // ❌ 錯誤
use Filament\Infolists\Components\Section;  // ❌ 錯誤
use Filament\Tables\Components\Section;     // ❌ 錯誤
```

### ✅ 正確的命名空間 (v4)

```php
use Filament\Schemas\Components\Section;    // ✅ 正確
```

---

## 🔧 修正的檔案

### Form Schemas (7 個檔案)

1. ✅ `app/Filament/Resources/Stores/Schemas/StoreForm.php`
2. ✅ `app/Filament/Resources/Products/Schemas/ProductForm.php`
3. ✅ `app/Filament/Resources/Members/Schemas/MemberForm.php`
4. ✅ `app/Filament/Resources/Users/Schemas/UserForm.php`
5. ✅ `app/Filament/Resources/Coupons/Schemas/CouponForm.php`
6. ✅ `app/Filament/Resources/Orders/Schemas/OrderForm.php`
7. ✅ `app/Filament/Resources/Ads/Schemas/AdForm.php`

### Infolist Schemas (2 個檔案)

8. ✅ `app/Filament/Resources/Stores/Schemas/StoreInfolist.php`
9. ✅ `app/Filament/Resources/Orders/Schemas/OrderInfolist.php`

**總計: 9 個檔案已修正**

---

## 📝 修正內容

### 修正前 (錯誤)

```php
<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;  // ❌ 錯誤
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Store Information')
                    ->schema([
                        TextInput::make('name'),
                    ]),
            ]);
    }
}
```

### 修正後 (正確)

```php
<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;  // ✅ 正確
use Filament\Schemas\Schema;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Store Information')
                    ->schema([
                        TextInput::make('name'),
                    ]),
            ]);
    }
}
```

---

## 🎯 Filament v4 組件命名空間規則

### Schema 組件 (統一在 Schemas\Components)

這些組件在 v4 中都移到了 `Filament\Schemas\Components`:

```php
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Split;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
```

### Form 組件 (保持在 Forms\Components)

這些組件仍然在 `Filament\Forms\Components`:

```php
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
```

### Infolist 組件 (保持在 Infolists\Components)

這些組件仍然在 `Filament\Infolists\Components`:

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\KeyValueEntry;
```

---

## 📚 完整的正確範例

### Form Schema 完整範例

```php
<?php

declare(strict_types=1);

namespace App\Filament\Resources\Stores\Schemas;

// ✅ Schema 組件 (統一命名空間)
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

// ✅ Form 組件 (原命名空間)
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Store Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        
                        Textarea::make('description')
                            ->rows(4),
                    ])
                    ->columns(2),
                
                Section::make('Location')
                    ->schema([
                        TextInput::make('latitude')
                            ->numeric(),
                        
                        TextInput::make('longitude')
                            ->numeric(),
                    ])
                    ->columns(2),
            ]);
    }
}
```

### Infolist Schema 完整範例

```php
<?php

declare(strict_types=1);

namespace App\Filament\Resources\Stores\Schemas;

// ✅ Schema 組件 (統一命名空間)
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

// ✅ Infolist 組件 (原命名空間)
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;

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
                        
                        TextEntry::make('description'),
                    ])
                    ->columns(2),
            ]);
    }
}
```

---

## 🔄 執行的修正步驟

1. ✅ 找出所有使用舊命名空間的檔案
2. ✅ 將 `use Filament\Forms\Components\Section;` 改為 `use Filament\Schemas\Components\Section;`
3. ✅ 將 `use Filament\Infolists\Components\Section;` 改為 `use Filament\Schemas\Components\Section;`
4. ✅ 清除所有快取: `php artisan optimize:clear`
5. ✅ 重新生成 autoload: `composer dump-autoload -o`

---

## ✅ 驗證修正

### 測試 Stores Resource

```bash
# 訪問 Stores 編輯頁面
https://quick-order-dev.test/backend/stores/1/edit

# 應該正常顯示，沒有錯誤
```

### 檢查所有 Resources

```bash
# 測試所有 Resources 的 Create/Edit 頁面
/backend/stores/create      ✅
/backend/products/create    ✅
/backend/members/create     ✅
/backend/users/create       ✅
/backend/orders/1/edit      ✅
/backend/coupons/create     ✅
/backend/ads/create         ✅
```

---

## 📖 參考資料

### Filament v4 Schema 系統

**新架構:**
- `Filament\Schemas\Schema` - 統一的 Schema 類別
- `Filament\Schemas\Components\*` - 佈局組件（Section, Tabs, Grid 等）
- `Filament\Forms\Components\*` - 表單輸入組件（TextInput, Select 等）
- `Filament\Infolists\Components\*` - 資訊顯示組件（TextEntry, ImageEntry 等）

### 為什麼要分離?

在 Filament v4 中，將 Schema 相關的組件（如 Section、Tabs）與具體功能的組件（如 TextInput）分離，是為了：

1. **統一佈局組件** - Section、Tabs、Grid 等在 Forms、Infolists、Tables 中都可以使用
2. **更清晰的架構** - 明確區分佈局組件與功能組件
3. **更好的代碼組織** - 避免命名空間混亂

---

## 🎯 重要提醒

### IDE 自動完成

如果您的 IDE 仍然顯示錯誤:

1. **重新索引專案**
   - PhpStorm: File → Invalidate Caches → Invalidate and Restart
   - VS Code: 重新啟動 PHP IntelliSense

2. **重新生成 IDE Helper**
   ```bash
   composer require --dev barryvdh/laravel-ide-helper
   php artisan ide-helper:generate
   php artisan ide-helper:models
   ```

3. **確認 Composer autoload**
   ```bash
   composer dump-autoload -o
   ```

---

## ✅ 修正完成！

所有 9 個檔案的 Section 命名空間已修正：

- ✅ 7 個 Form Schemas
- ✅ 2 個 Infolist Schemas

現在可以正常使用所有 Filament Resources 了！

**測試命令:**
```bash
# 清除快取
php artisan optimize:clear

# 訪問 Admin Panel
https://quick-order-dev.test/backend

# 測試所有 Resources
```

**現在所有 Filament Resources 都可以正常工作了！** 🎉

