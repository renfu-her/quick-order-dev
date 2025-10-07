# 🌱 測試資料概覽

## 📊 完整的 Seed Data 結構

### 🏪 Stores (5 個)

| 名稱 | 地址 | 電話 | 狀態 | 座標 |
|------|------|------|------|------|
| Quick Order Main Branch | 123 Main Street, Downtown | +1 (555) 100-0001 | ✅ Active | 25.0330, 121.5654 |
| Quick Order North Branch | 456 North Avenue, North District | +1 (555) 200-0002 | ✅ Active | 25.0520, 121.5430 |
| Quick Order Express (East) | 789 East Road, East District | +1 (555) 300-0003 | ✅ Active | 25.0419, 121.5819 |
| Quick Order West Branch | 321 West Boulevard, West District | +1 (555) 400-0004 | ✅ Active | 25.0251, 121.5134 |
| Quick Order Airport | 999 Airport Terminal | +1 (555) 500-0005 | ❌ Inactive | 25.0777, 121.2328 |

**特點:**
- ✅ 每個商店都有完整的營業時間設定
- ✅ 啟用的商店自動創建 2 張圖片
- ✅ 包含經緯度座標（可用於地圖顯示）
- ✅ 包含描述、聯絡資訊

---

### 👥 Members (5 個)

| 姓名 | Email | 密碼 | 電話 | 訂單 | 購物車 |
|------|-------|------|------|------|--------|
| Test Member | member@test.com | password | +1 (555) 123-4567 | ✅ 1 筆 | ❌ |
| John Doe | john@example.com | password | +1 (555) 234-5678 | ✅ 1 筆 | ❌ |
| Jane Smith | jane@example.com | password | +1 (555) 345-6789 | ❌ | ✅ 活動中 |
| Michael Johnson | michael@example.com | password | +1 (555) 456-7890 | ❌ | ❌ |
| Sarah Williams | sarah@example.com | password | +1 (555) 567-8901 | ❌ | ❌ |

---

### 🔗 Member 連結關係

#### Member #1: Test Member (member@test.com)
**訂單:**
- ✅ 1 筆範例訂單
- 包含 3 個隨機產品
- 隨機狀態（pending/confirmed/completed）
- 隨機付款方式
- 付款狀態: Paid

**連結:**
```php
Member → hasMany Orders
Member → hasMany Carts
Order → hasMany OrderItems
OrderItem → belongsTo Product
```

#### Member #2: John Doe (john@example.com)
**訂單:**
- ✅ 1 筆範例訂單
- 包含 3 個隨機產品
- 可在後台查看

#### Member #3: Jane Smith (jane@example.com)
**購物車:**
- ✅ 1 個活動購物車
- 包含 2 個產品
- 隨機數量 (1-2)
- 隨機溫度選擇

**連結:**
```php
Member → Cart → CartItems → Products
```

#### Members #4-5: Michael & Sarah
**狀態:**
- ❌ 無訂單記錄
- ❌ 無購物車
- ✅ 可用於新訂單測試

---

### 🛍️ Products (8 個)

| 產品 | 價格 | 特價 | 分類 |
|------|------|------|------|
| Classic Burger | $12.99 | - | Burgers |
| Chicken Wings | $9.99 | $7.99 | Appetizers |
| Iced Coffee | $4.99 | - | Beverages |
| Caesar Salad | $8.99 | - | Salads |
| Margherita Pizza | $14.99 | $12.99 | Pizza |
| Green Tea | $3.99 | - | Beverages |
| Fish and Chips | $13.99 | - | Main Course |
| Chocolate Cake | $6.99 | - | Desserts |

**每個產品包含:**
- ✅ 配料列表（有些含額外價格）
- ✅ 溫度價格變化（飲料類）

---

### 🎟️ Coupons (4 個)

| 代碼 | 折扣 | 最低消費 | 限制 |
|------|------|----------|------|
| WELCOME10 | 10% | $20 | 最大折 $5 |
| SAVE5 | $5 固定 | $30 | 無限制 |
| SUMMER20 | 20% | $50 | 最大折 $15 |
| FREESHIP | $3 固定 | $25 | 無限制 |

---

### 📢 Ads (3 個)

| 標題 | 關聯產品 | 狀態 |
|------|----------|------|
| Summer Special - 20% Off Burgers | Classic Burger | ✅ Active |
| New Pizza Flavors | Margherita Pizza | ✅ Active |
| Happy Hour Drinks | Iced Coffee | ✅ Active |

---

## 🔍 資料庫關聯圖

```
┌─────────┐
│ Stores  │
└─────────┘
     │
     ├──> Store Images (hasMany)
     │
     └──> Products (hasMany) [未實作]

┌─────────┐
│ Members │
└─────────┘
     │
     ├──> Orders (hasMany)
     │      │
     │      └──> Order Items (hasMany)
     │             │
     │             └──> Products (belongsTo)
     │
     └──> Carts (hasMany)
            │
            └──> Cart Items (hasMany)
                   │
                   └──> Products (belongsTo)

┌──────────┐
│ Products │
└──────────┘
     │
     ├──> Product Images (hasMany)
     ├──> Product Ingredients (hasMany)
     └──> Ads (hasMany)

┌──────────┐
│ Coupons  │
└──────────┘
     │
     ├──> Orders (hasMany)
     └──> Carts (hasMany)
```

---

## 🎯 測試場景

### 場景 1: 會員瀏覽商店
```php
// 顯示所有啟用的商店
Store::where('is_active', true)->get();  // 4 個商店

// 查看商店詳情
$store = Store::find(1);
$store->name;  // "Quick Order Main Branch"
$store->hours; // 營業時間 JSON
$store->getPrimaryImage(); // 主圖片
```

### 場景 2: 會員有歷史訂單
```php
// Test Member 登入
$member = Member::where('email', 'member@test.com')->first();

// 查看訂單歷史
$member->orders; // 1 筆訂單
$order = $member->orders->first();
$order->items; // 3 個產品
$order->total_amount; // 自動計算
```

### 場景 3: 會員有活動購物車
```php
// Jane Smith 登入
$member = Member::where('email', 'jane@example.com')->first();

// 查看購物車
$cart = $member->getActiveCart();
$cart->items; // 2 個產品
$cart->getTotal(); // 自動計算含優惠券
```

### 場景 4: 後台查看會員統計
```php
// Admin 查看會員
$members = Member::withCount('orders')->get();

// member@test.com → orders_count: 1
// john@example.com → orders_count: 1
// jane@example.com → orders_count: 0
// michael@example.com → orders_count: 0
// sarah@example.com → orders_count: 0
```

---

## 📝 Seeder 執行順序

```
1. StoreSeeder        ← 創建 5 個商店 + 圖片
2. ProductSeeder      ← 創建 8 個產品 + 配料
3. MemberSeeder       ← 創建 5 個會員 + 訂單 + 購物車
4. AdSeeder           ← 創建 3 個廣告
5. CouponSeeder       ← 創建 4 個優惠券
```

**重要:** MemberSeeder 必須在 ProductSeeder 之後執行，因為要創建訂單項目需要產品資料。

---

## 🧪 測試用例

### 用例 1: 有訂單歷史的會員
**帳號:** member@test.com  
**測試:**
1. 登入前台
2. 查看個人資料（未來功能）
3. 應顯示 1 筆歷史訂單
4. 後台可以看到此會員的訂單數量 = 1

### 用例 2: 有購物車的會員
**帳號:** jane@example.com  
**測試:**
1. 登入前台
2. 購物車已有 2 個商品
3. 可以繼續購物
4. 可以修改數量
5. 可以直接結帳

### 用例 3: 新會員
**帳號:** michael@example.com 或 sarah@example.com  
**測試:**
1. 登入前台
2. 購物車為空
3. 無訂單歷史
4. 完整的購物流程測試

### 用例 4: 後台商店管理
**測試:**
1. 登入 Admin
2. 前往 Stores
3. 看到 5 個商店（4 active, 1 inactive）
4. 查看商店詳情
5. 編輯營業時間
6. 管理商店圖片

---

## 📦 資料統計

### 創建的記錄數

| 資料表 | 記錄數 | 說明 |
|--------|--------|------|
| stores | 5 | 4 啟用, 1 停用 |
| store_images | 8 | 每個啟用商店 2 張圖 |
| members | 5 | 所有啟用 |
| orders | 2 | 2 個會員各 1 筆 |
| order_items | 6 | 每筆訂單 3 個產品 |
| carts | 1 | 1 個活動購物車 |
| cart_items | 2 | 購物車有 2 個產品 |
| products | 8 | 所有啟用 |
| product_ingredients | ~40 | 平均每產品 5 個配料 |
| ads | 3 | 所有啟用 |
| coupons | 4 | 所有啟用 |
| users | 1 | Admin 帳號 |

**總記錄數:** 80+ 條

---

## 🔗 關聯連結範例

### Member → Orders
```php
// Test Member 的訂單
$member = Member::where('email', 'member@test.com')->first();
$orders = $member->orders; // Collection of Orders

// 訂單詳情
$order = $orders->first();
$order->order_number; // ORD20251007XXXXXX
$order->status;       // pending/confirmed/completed
$order->items;        // Collection of OrderItems
```

### Member → Cart → CartItems → Products
```php
// Jane Smith 的購物車
$member = Member::where('email', 'jane@example.com')->first();
$cart = $member->getActiveCart(); // Cart model
$items = $cart->items;            // Collection of CartItems

foreach ($items as $item) {
    echo $item->product->name;     // Product name
    echo $item->quantity;          // Quantity
    echo $item->temperature;       // hot/cold/none
    echo $item->unit_price;        // Price
}
```

### Store → Images
```php
// 商店圖片
$store = Store::find(1);
$images = $store->images;        // Collection of StoreImages
$primaryImage = $store->getPrimaryImage(); // Primary image

foreach ($images as $image) {
    echo $image->image_path;     // stores/store-1-1.jpg
    echo $image->is_primary;     // true/false
}
```

---

## 🚀 執行 Seeder

```bash
# 執行所有 Seeders
php artisan db:seed

# 或重新開始（清除所有資料）
php artisan migrate:fresh --seed
```

---

## 🎯 後台測試場景

### 場景 A: 查看會員訂單統計
1. 登入 Admin Panel
2. 前往 "Customer Management" → "Members"
3. 查看 "Orders" 欄位

**預期結果:**
- Test Member → 1 order
- John Doe → 1 order
- Jane Smith → 0 orders (但有活動購物車)
- Michael Johnson → 0 orders
- Sarah Williams → 0 orders

### 場景 B: 查看商店列表
1. 前往 "Stores"
2. 看到 5 個商店
3. 4 個顯示綠色勾號（Active）
4. 1 個顯示紅色叉號（Inactive）

### 場景 C: 查看訂單詳情
1. 前往 "Orders"
2. 看到 2 筆訂單
3. 點擊查看詳情
4. 可以看到關聯的會員資訊
5. 可以看到訂單項目明細

---

## 🎨 前台測試場景

### 場景 D: 有歷史訂單的會員登入
1. 登入 member@test.com
2. 前往購物（未來可添加訂單歷史頁面）
3. 完成新訂單
4. 後台應顯示 orders_count = 2

### 場景 E: 有購物車的會員登入
1. 登入 jane@example.com
2. 購物車已有 2 個商品
3. 可以調整數量
4. 可以加入更多商品
5. 直接結帳

### 場景 F: 新會員購物
1. 登入 michael@example.com
2. 空購物車
3. 瀏覽產品
4. 加入購物車
5. 使用優惠券
6. 完成訂單

---

## 💡 進階測試建議

### 測試 1: 商店圖片管理
```
1. 登入 Admin
2. 前往 Stores → Edit "Main Branch"
3. 查看現有圖片（2 張）
4. 上傳新圖片
5. 設定新的主圖片
6. 調整排序
```

### 測試 2: 會員購物完整流程
```
1. 以 member@test.com 登入
2. 瀏覽產品
3. 加入購物車
4. 應用優惠券 WELCOME10
5. 結帳（資訊自動填入）
6. 完成訂單
7. 後台查看 member 訂單數 → 變為 2
```

### 測試 3: 商店營業時間
```
1. 前往 Stores → View "Main Branch"
2. 查看營業時間
3. Edit → 修改營業時間
4. 使用 KeyValue 組件添加/編輯
```

---

## 🎁 額外測試資料

### 可用的測試帳號

**有訂單記錄:**
- member@test.com (1 筆訂單)
- john@example.com (1 筆訂單)

**有購物車:**
- jane@example.com (2 個產品在購物車)

**全新帳號:**
- michael@example.com
- sarah@example.com

**或註冊新帳號:**
- 訪問 `/auth` 註冊

---

## 📋 驗證清單

執行 seeder 後，檢查：

- [ ] 5 個商店已創建
- [ ] 啟用商店有圖片（每個 2 張）
- [ ] 5 個會員已創建
- [ ] member@test.com 有 1 筆訂單
- [ ] john@example.com 有 1 筆訂單
- [ ] jane@example.com 有 1 個購物車
- [ ] 購物車有 2 個商品
- [ ] 訂單有 3 個項目
- [ ] 8 個產品已創建
- [ ] 3 個廣告已創建
- [ ] 4 個優惠券已創建

---

## 🎊 完成！

現在您有：
- ✅ 5 個商店（含圖片、營業時間、位置）
- ✅ 5 個會員（含訂單和購物車）
- ✅ 完整的測試資料連結
- ✅ 即時可測試的場景

**立即執行:**
```bash
php artisan migrate:fresh --seed
```

**然後測試所有功能！** 🚀

