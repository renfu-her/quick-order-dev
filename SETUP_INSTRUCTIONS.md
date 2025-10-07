# Quick Order - 完整安裝指南

## ✅ Filament v4 模組化架構已完成

所有 Resources 已成功重構為模組化結構，完全符合 Filament v4 規範。

## 📋 當前架構

```
app/Filament/Resources/
├── Products/      ✅ (ProductResource + Schemas + Tables + Pages)
├── Ads/           ✅ (AdResource + Schemas + Tables + Pages)
├── Orders/        ✅ (OrderResource + Schemas + Infolist + Tables + Pages)
└── Coupons/       ✅ (CouponResource + Schemas + Tables + Pages)
```

## 🚀 快速安裝步驟

### Step 1: 創建資料庫

```bash
# 使用 MySQL 命令列或 phpMyAdmin 創建資料庫
mysql -u root -e "CREATE DATABASE quick_order CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

或者使用 Laragon 的 MySQL:
- 打開 Laragon
- 點擊 "Database" → "Open"
- 執行 SQL: `CREATE DATABASE quick_order;`

### Step 2: 配置環境變數

複製 `.env.example` 為 `.env` (如果還沒有的話):

```bash
cp .env.example .env
```

確保 `.env` 文件中的資料庫配置為:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quick_order
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: 生成應用金鑰

```bash
php artisan key:generate
```

### Step 4: 執行遷移與填充資料

```bash
# 執行資料庫遷移
php artisan migrate

# 填充範例資料（產品、廣告、優惠券、管理員帳號）
php artisan db:seed
```

### Step 5: 創建儲存連結

```bash
php artisan storage:link
```

### Step 6: 啟動開發伺服器

```bash
php artisan serve
```

## 🎯 訪問系統

### 前台 (Frontend)
**URL:** http://localhost:8000

**功能:**
- 瀏覽產品
- 查看廣告優惠
- 加入購物車
- 使用優惠券
- 完成結帳

### 後台 (Admin Panel)
**URL:** http://localhost:8000/admin

**登入資訊:**
- Email: `admin@quickorder.com`
- Password: `password`

**功能:**
- 產品管理（圖片、配料、價格）
- 廣告管理（排程、排序）
- 訂單管理（狀態更新）
- 優惠券管理（折扣設定）

## 🎁 測試資料

### 範例產品 (8個)
- Classic Burger
- Chicken Wings
- Iced Coffee
- Caesar Salad
- Margherita Pizza
- Green Tea
- Fish and Chips
- Chocolate Cake

### 範例優惠券 (4個)
| 代碼 | 折扣 | 最低消費 |
|------|------|----------|
| WELCOME10 | 10% | $20 |
| SAVE5 | $5 | $30 |
| SUMMER20 | 20% | $50 |
| FREESHIP | $3 | $25 |

### 範例廣告 (3個)
- Summer Special - 20% Off Burgers
- New Pizza Flavors
- Happy Hour Drinks

## 🔧 疑難排解

### 問題 1: 資料庫連接錯誤

```
SQLSTATE[HY000] [1049] Unknown database 'quick_order'
```

**解決方案:**
```bash
# 創建資料庫
mysql -u root -e "CREATE DATABASE quick_order;"
```

### 問題 2: 無法訪問 Admin

```bash
# 創建新管理員
php artisan make:filament-user
```

### 問題 3: 圖片無法顯示

```bash
# 重新創建 storage 連結
php artisan storage:link
```

### 問題 4: Filament 樣式錯誤

```bash
# 清除所有快取
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 重新生成 autoload
composer dump-autoload
```

### 問題 5: Class not found 錯誤

```bash
# 重新生成 Composer autoload
composer dump-autoload -o
```

## 📊 資料庫結構

### 核心資料表
1. **products** - 產品主檔
2. **product_images** - 產品圖片
3. **product_ingredients** - 產品配料
4. **ads** - 廣告
5. **coupons** - 優惠券
6. **orders** - 訂單
7. **order_items** - 訂單明細
8. **carts** - 購物車
9. **cart_items** - 購物車項目

### 關鍵關聯
- Product → hasMany ProductImages
- Product → hasMany ProductIngredients
- Order → hasMany OrderItems
- Cart → hasMany CartItems
- Ad → belongsTo Product

## 🎨 Filament v4 架構特色

### ✅ 模組化設計
每個 Resource 分為:
- **Resource.php** - 主要配置
- **Schemas/** - Form 和 Infolist 配置
- **Tables/** - Table 配置
- **Pages/** - 頁面類別

### ✅ 正確的類型定義
```php
public static function form(Schema $schema): Schema
{
    return Schema::components([...]);
}
```

### ✅ 正確的 Actions 使用
```php
->recordActions([...])      // 記錄操作
->toolbarActions([...])     // 工具列操作
```

### ✅ 正確的 Icon 定義
```php
protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';
```

## 🎯 下一步

1. ✅ 瀏覽 Admin Panel
2. ✅ 創建新產品
3. ✅ 上傳產品圖片
4. ✅ 設定產品配料
5. ✅ 創建廣告
6. ✅ 測試前台購物流程
7. ✅ 使用優惠券結帳
8. ✅ 查看訂單管理

## 📚 相關文檔

- **README_SETUP.md** - 完整技術文檔
- **QUICK_START.md** - 5 分鐘快速開始
- **FILAMENT_MODULAR_ARCHITECTURE.md** - 架構詳解
- **BEFORE_AFTER_COMPARISON.md** - 修正對比
- **SETUP_INSTRUCTIONS.md** (本文件) - 安裝指南

## 🎉 準備就緒！

您的 Quick Order 系統現在擁有:
- ✅ 完整的 Filament v4 後台
- ✅ 響應式前台
- ✅ 模組化架構
- ✅ 企業級程式碼品質
- ✅ 完整的測試資料

開始使用吧！🚀

