# 🌱 如何執行 Seeders

## 📋 必要條件檢查

### ✅ 已具備的檔案

#### Migrations (19 個)
- ✅ `create_stores_table.php`
- ✅ `create_store_images_table.php`
- ✅ `add_store_id_to_products_table.php`
- ✅ `create_members_table.php`
- ✅ `update_carts_use_member.php`
- ✅ `update_orders_use_member.php`
- ✅ `create_products_table.php`
- ✅ `create_orders_table.php`
- ✅ `create_carts_table.php`
- ✅ 其他所有表...

#### Models (12 個)
- ✅ Store
- ✅ StoreImage
- ✅ Member
- ✅ Product
- ✅ Order
- ✅ OrderItem
- ✅ Cart
- ✅ CartItem
- ✅ 其他...

#### Seeders (6 個)
- ✅ DatabaseSeeder
- ✅ StoreSeeder ⭐
- ✅ MemberSeeder ⭐ (更新)
- ✅ ProductSeeder
- ✅ AdSeeder
- ✅ CouponSeeder

---

## 🚀 執行步驟

### 方法 1: 完整重置（推薦）

這會清除所有資料並重新建立：

```bash
# Step 1: 確保資料庫存在
mysql -u root -e "CREATE DATABASE IF NOT EXISTS quick_order;"

# Step 2: 執行遷移與填充
php artisan migrate:fresh --seed
```

**這個命令會:**
1. 刪除所有資料表
2. 重新執行所有 migrations
3. 執行所有 seeders（按順序）

---

### 方法 2: 只執行 Seeders

如果資料表已存在，只想重新填充資料：

```bash
# 清空所有資料表並重新填充
php artisan db:seed --class=DatabaseSeeder
```

---

### 方法 3: 執行特定 Seeder

只執行特定的 Seeder：

```bash
# 只執行 StoreSeeder
php artisan db:seed --class=StoreSeeder

# 只執行 MemberSeeder
php artisan db:seed --class=MemberSeeder
```

⚠️ **注意:** MemberSeeder 需要 ProductSeeder 先執行，因為要創建訂單項目。

---

## 📝 執行順序（自動）

DatabaseSeeder 會按以下順序執行：

```
1. StoreSeeder        ← 創建商店和圖片
2. ProductSeeder      ← 創建產品和配料
3. MemberSeeder       ← 創建會員、訂單、購物車
4. AdSeeder           ← 創建廣告
5. CouponSeeder       ← 創建優惠券
```

---

## ✅ 完整執行腳本

### Windows (推薦使用)

```batch
@echo off
echo 🌱 執行 Quick Order Seeders
echo =============================
echo.

echo 📦 Step 1: 清除快取...
call php artisan cache:clear
call php artisan config:clear

echo.
echo 🗄️ Step 2: 創建資料庫（如果不存在）...
mysql -u root -e "CREATE DATABASE IF NOT EXISTS quick_order;"

echo.
echo 🔄 Step 3: 執行遷移與填充...
call php artisan migrate:fresh --seed

echo.
echo ✅ 完成！
echo.
echo 測試資料已創建:
echo - Stores: 5 個 (4 active, 1 inactive)
echo - Members: 5 個 (含訂單和購物車)
echo - Products: 8 個
echo - Orders: 2 筆 (關聯到 Member)
echo - Carts: 1 個 (Jane Smith)
echo.
pause
```

### Linux/Mac

```bash
#!/bin/bash
echo "🌱 執行 Quick Order Seeders"
echo "============================="
echo ""

echo "📦 Step 1: 清除快取..."
php artisan cache:clear
php artisan config:clear

echo ""
echo "🗄️ Step 2: 創建資料庫（如果不存在）..."
mysql -u root -e "CREATE DATABASE IF NOT EXISTS quick_order;"

echo ""
echo "🔄 Step 3: 執行遷移與填充..."
php artisan migrate:fresh --seed

echo ""
echo "✅ 完成！"
echo ""
echo "測試資料已創建:"
echo "- Stores: 5 個 (4 active, 1 inactive)"
echo "- Members: 5 個 (含訂單和購物車)"
echo "- Products: 8 個"
echo "- Orders: 2 筆 (關聯到 Member)"
echo "- Carts: 1 個 (Jane Smith)"
```

---

## 🔍 驗證執行結果

### 檢查資料庫

```sql
-- 檢查 Stores
SELECT id, name, is_active FROM stores;
-- 應該有 5 筆記錄

-- 檢查 Store Images
SELECT store_id, image_path, is_primary FROM store_images;
-- 應該有 8 筆記錄（4 個商店 × 2 張圖）

-- 檢查 Members
SELECT id, name, email FROM members;
-- 應該有 5 筆記錄

-- 檢查 Orders 與 Member 關聯
SELECT o.id, o.order_number, m.name as member_name 
FROM orders o 
LEFT JOIN members m ON o.member_id = m.id;
-- 應該有 2 筆記錄，都有 member_name

-- 檢查 Carts
SELECT c.id, m.name as member_name, COUNT(ci.id) as items_count
FROM carts c
LEFT JOIN members m ON c.member_id = m.id
LEFT JOIN cart_items ci ON c.id = ci.cart_id
GROUP BY c.id, m.name;
-- 應該有 1 筆（Jane Smith 的購物車，2 個商品）
```

### 檢查 Filament Admin

1. 訪問 http://localhost:8000/admin
2. 登入 admin@quickorder.com / password
3. 檢查各個 Resource:

**Stores:**
- 應該看到 5 個商店
- 4 個顯示綠色圖示（Active）
- 1 個顯示紅色圖示（Inactive）
- 點擊 View 可以看到圖片、營業時間

**Members:**
- 應該看到 5 個會員
- Orders 欄位顯示:
  - Test Member: 1
  - John Doe: 1
  - Jane Smith: 0
  - Michael Johnson: 0
  - Sarah Williams: 0

**Orders:**
- 應該看到 2 筆訂單
- 都有關聯的會員（customer_name）
- 每筆訂單有 3 個項目
- 點擊 View 可以看到詳細資訊

---

## ⚠️ 常見問題與解決

### 問題 1: Class "StoreSeeder" not found

**原因:** Composer autoload 未更新

**解決:**
```bash
composer dump-autoload
php artisan db:seed
```

### 問題 2: SQLSTATE[42S02]: Base table or view not found: 'stores'

**原因:** Migration 未執行

**解決:**
```bash
php artisan migrate
# 然後再執行
php artisan db:seed
```

### 問題 3: Integrity constraint violation (Foreign key)

**原因:** Seeder 執行順序錯誤

**解決:** 使用完整重置
```bash
php artisan migrate:fresh --seed
```

### 問題 4: Call to undefined method Product::getEffectivePrice()

**原因:** Product Model 缺少方法

**檢查:** app/Models/Product.php 應該有這個方法
```php
public function getEffectivePrice(): float
{
    return (float) ($this->special_price ?? $this->price);
}
```

---

## 🎯 建議的執行流程

### 首次安裝

```bash
# 1. 創建資料庫
mysql -u root -e "CREATE DATABASE quick_order;"

# 2. 更新環境變數（如果需要）
# 編輯 .env 確認 DB_DATABASE=quick_order

# 3. 清除所有快取
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# 4. 重新生成 autoload
composer dump-autoload

# 5. 執行遷移與填充（一次完成）
php artisan migrate:fresh --seed

# 6. 創建儲存連結
php artisan storage:link

# 7. 啟動伺服器
php artisan serve
```

---

### 重新填充資料（保留資料表結構）

```bash
# 1. 清空所有資料表
php artisan db:wipe

# 2. 重新執行遷移
php artisan migrate

# 3. 執行 seeders
php artisan db:seed
```

---

### 只更新特定資料

```bash
# 只重新創建 Members（需先清空相關表）
php artisan db:seed --class=MemberSeeder

# 只重新創建 Stores
php artisan db:seed --class=StoreSeeder
```

---

## 📊 執行後應該看到

### 終端輸出

```
Database seeded successfully!

=== Admin Credentials (Backend) ===
Email: admin@quickorder.com
Password: password

=== Member Credentials (Frontend) ===
Email: member@test.com
Password: password

=== Seeded Data Summary ===
Stores: 5 (4 active, 1 inactive)
Members: 5 (with sample orders and carts)
Products: 8
Ads: 3
Coupons: 4
```

### 資料庫記錄數

```bash
# 使用 tinker 檢查
php artisan tinker

>>> App\Models\Store::count()
=> 5

>>> App\Models\StoreImage::count()
=> 8

>>> App\Models\Member::count()
=> 5

>>> App\Models\Order::whereNotNull('member_id')->count()
=> 2

>>> App\Models\Cart::whereNotNull('member_id')->count()
=> 1
```

---

## 🎉 快速測試

執行完成後立即測試：

### 1. 測試後台 Stores
```
http://localhost:8000/admin
→ Stores
→ 應該看到 5 個商店
→ 點擊 View 查看詳情
```

### 2. 測試後台 Members
```
http://localhost:8000/admin
→ Customer Management → Members
→ 應該看到 5 個會員
→ Orders 欄位顯示訂單數量
```

### 3. 測試前台會員登入
```
http://localhost:8000/auth
→ 登入 jane@example.com
→ 前往購物車
→ 應該已有 2 個商品
```

---

## 🚀 一鍵執行命令

```bash
# 全部重新開始（推薦）
php artisan migrate:fresh --seed && php artisan storage:link && echo "✅ 完成！"
```

這個命令會：
1. 刪除所有資料表
2. 重新執行所有 migrations
3. 執行所有 seeders
4. 創建 storage 連結
5. 顯示完成訊息

---

**現在執行 `php artisan migrate:fresh --seed` 就可以讓所有 Seeders 跑起來！** 🎉

