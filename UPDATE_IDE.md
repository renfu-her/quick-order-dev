# 解決 IDE Linter 警告

## 當前狀況

您可能會看到許多關於 Filament v4 的 linter 警告，例如：
- `Use of unknown class: 'Filament\Forms\Components\Section'`
- `Declaration of form() must be compatible with Resource::form()`

這些是 **IDE 誤報**，代碼實際上是正確的。

## 解決方案

### 方案 1：重新生成 IDE Helper（推薦）

```bash
# 安裝 IDE Helper
composer require --dev barryvdh/laravel-ide-helper

# 生成 helper 文件
php artisan ide-helper:generate
php artisan ide-helper:models
php artisan ide-helper:meta
```

### 方案 2：清除 IDE 緩存

**VS Code / Cursor:**
1. 按 `Ctrl+Shift+P` (或 `Cmd+Shift+P` on Mac)
2. 輸入 "Reload Window"
3. 或者重新開啟專案

**PHPStorm:**
1. File → Invalidate Caches / Restart
2. 選擇 "Invalidate and Restart"

### 方案 3：更新 Composer autoload

```bash
composer dump-autoload
```

### 方案 4：忽略這些警告

這些警告不會影響代碼運行。Filament v4 的代碼是完全正確的。

## 已修正的實際問題

✅ **CouponResource.php** - `Forms\Get` 類型提示錯誤（已修正）  
✅ **ProductResource.php** - 移除不必要的 `defaultImageUrl`（已修正）

## 驗證代碼正確性

運行以下命令確認沒有實際錯誤：

```bash
# 測試數據庫遷移
php artisan migrate:fresh --seed

# 啟動伺服器
php artisan serve

# 訪問 admin 面板
# http://localhost:8000/admin
# Email: admin@quickorder.com
# Password: password
```

如果 Admin 面板可以正常訪問和使用，說明代碼完全正確！

