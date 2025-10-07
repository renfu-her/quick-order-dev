# 🧪 Quick Order 測試指南

## 🚀 快速開始測試

### Step 1: 安裝系統

```bash
# 創建資料庫
mysql -u root -e "CREATE DATABASE quick_order;"

# 執行遷移與填充
php artisan migrate:fresh --seed

# 創建儲存連結
php artisan storage:link

# 啟動伺服器
php artisan serve
```

---

## ✅ 測試清單

### 測試 1: 前台會員註冊 ⭐ NEW

**步驟:**
1. 訪問 http://localhost:8000/auth
2. 點擊 "Register" tab
3. 填寫資料:
   - Name: Test User
   - Email: testuser@example.com
   - Phone: 0912345678
   - Password: password
   - Confirm Password: password
4. 點擊 "Create Account"

**預期結果:**
- ✅ 帳號創建成功
- ✅ 自動登入
- ✅ 重定向到首頁
- ✅ Header 顯示 "👤 Test User"
- ✅ 顯示成功訊息

---

### 測試 2: 前台會員登入 ⭐ NEW

**步驟:**
1. 如果已登入，先登出
2. 訪問 http://localhost:8000/auth
3. 填寫資料:
   - Email: member@test.com
   - Password: password
4. 勾選 "Remember Me"
5. 點擊 "Login"

**預期結果:**
- ✅ 登入成功
- ✅ 重定向到首頁
- ✅ Header 顯示 "👤 Test Member"
- ✅ 歡迎訊息

---

### 測試 3: 會員購物流程

**步驟:**
1. 以 member@test.com 登入
2. 瀏覽產品，點擊 "View Details"
3. 選擇溫度（如果有）
4. 調整數量
5. 點擊 "Add to Cart"
6. 前往購物車
7. 輸入優惠券: WELCOME10
8. 點擊 "Proceed to Checkout"

**預期結果:**
- ✅ 產品成功加入購物車
- ✅ 購物車顯示正確數量和金額
- ✅ 優惠券應用成功
- ✅ 折扣正確計算
- ✅ 結帳頁面**自動填入會員資訊** ⭐

---

### 測試 4: 會員完成訂單

**步驟:**
1. 在結帳頁面
2. 確認會員資訊已自動填入
3. 選擇付款方式
4. 填寫備註（選填）
5. 點擊 "Place Order"

**預期結果:**
- ✅ 訂單創建成功
- ✅ 顯示訂單確認頁面
- ✅ 訂單號碼生成
- ✅ 訂單詳情正確
- ✅ 購物車已清空

---

### 測試 5: 後台查看會員與訂單 ⭐ NEW

**步驟:**
1. 登入 Admin Panel
   - URL: http://localhost:8000/admin
   - Email: admin@quickorder.com
   - Password: password

2. 前往 "Customer Management" → "Members"
3. 確認看到新註冊的會員
4. 查看 "Orders" 欄位（訂單數量）

5. 前往 "Orders"
6. 點擊最新的訂單
7. 確認訂單詳情

**預期結果:**
- ✅ Members 列表顯示所有會員
- ✅ 訂單數量正確統計
- ✅ 可以編輯會員資訊
- ✅ 訂單關聯到正確的會員
- ✅ 訂單詳情完整顯示

---

### 測試 6: 後台產品管理

**步驟:**
1. 在 Admin Panel
2. 前往 "Products"
3. 點擊 "Create"
4. 填寫產品資訊
5. 上傳多張圖片
6. 添加配料
7. 設定不同溫度價格
8. 保存

**預期結果:**
- ✅ 產品創建成功
- ✅ 圖片上傳成功
- ✅ 配料保存正確
- ✅ 價格設定正確
- ✅ 列表顯示新產品

---

### 測試 7: 後台廣告管理

**步驟:**
1. 前往 "Ads"
2. 點擊 "Create"
3. 上傳廣告圖片
4. 關聯到產品
5. 設定排序
6. 設定開始/結束時間
7. 啟用

**預期結果:**
- ✅ 廣告創建成功
- ✅ 前台首頁顯示廣告
- ✅ 排序正確
- ✅ 連結到正確產品

---

### 測試 8: 後台優惠券管理

**步驟:**
1. 前往 "Coupons"
2. 點擊 "Create"
3. 創建優惠券:
   - Code: TEST20
   - Type: Percentage
   - Value: 20
   - Min Purchase: $30
4. 保存

5. 前台測試:
   - 購物車加入 $30 以上商品
   - 輸入 TEST20
   - 確認折扣

**預期結果:**
- ✅ 優惠券創建成功
- ✅ 前台可以使用
- ✅ 折扣計算正確
- ✅ 使用次數增加
- ✅ 最低金額驗證

---

### 測試 9: 會員登出

**步驟:**
1. 在已登入狀態
2. 點擊 Header 的 "Logout" 按鈕
3. 確認登出

**預期結果:**
- ✅ Session 清除
- ✅ 重定向到首頁
- ✅ Header 顯示 "Login / Register"
- ✅ 顯示登出成功訊息

---

### 測試 10: 訪客購物流程

**步驟:**
1. 確保未登入
2. 瀏覽產品
3. 加入購物車
4. 前往結帳
5. 手動填寫資訊
6. 完成訂單

**預期結果:**
- ✅ 訪客可以購物
- ✅ 表單空白（需手動填寫）
- ✅ 訂單 member_id 為 null
- ✅ 訂單創建成功

---

## 🔍 檢查點

### 資料庫檢查

```sql
-- 檢查 Members 表
SELECT * FROM members;

-- 檢查 Orders 有 member_id
SELECT id, order_number, member_id, customer_name FROM orders;

-- 檢查會員的訂單
SELECT m.name, COUNT(o.id) as order_count
FROM members m
LEFT JOIN orders o ON m.id = o.member_id
GROUP BY m.id, m.name;
```

### Filament 檢查

#### Navigation 結構
```
Dashboard

Customer Management
└── Members

Shop Management  
├── Products
├── Ads
├── Orders
└── Coupons

System Management
└── Users
```

### 前台檢查

#### Header 狀態
- 未登入: 顯示 "Login / Register" 連結
- 已登入: 顯示 "👤 會員名稱" 和 "Logout" 按鈕

---

## ⚠️ 常見問題排解

### 問題 1: 無法登入

**檢查:**
```bash
# 確認 Member 記錄存在
php artisan tinker
>>> App\Models\Member::where('email', 'member@test.com')->first()

# 確認密碼
>>> App\Models\Member::first()->password  // 應該是 hashed
```

### 問題 2: 訂單沒有關聯到會員

**檢查:**
- 確保已登入狀態下訂單
- 檢查 `CheckoutController` 使用 `Auth::guard('member')->id()`

### 問題 3: 結帳頁面沒有自動填入

**檢查:**
- 確保已登入
- 檢查 checkout.blade.php 使用正確的 guard

---

## 📝 測試報告範本

完成測試後，記錄結果:

```
日期: ____________
測試人員: ____________

[ ] 會員註冊 - 通過/失敗
[ ] 會員登入 - 通過/失敗
[ ] 會員購物 - 通過/失敗
[ ] 訂單創建 - 通過/失敗
[ ] 後台會員管理 - 通過/失敗
[ ] 後台產品管理 - 通過/失敗
[ ] 優惠券功能 - 通過/失敗
[ ] 廣告顯示 - 通過/失敗
[ ] 會員登出 - 通過/失敗
[ ] 訪客購物 - 通過/失敗

問題記錄:
______________________________
______________________________
```

---

## 🎯 完整測試腳本

```bash
# 1. 重置資料庫
php artisan migrate:fresh --seed

# 2. 清除所有快取
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 3. 重新生成 autoload
composer dump-autoload

# 4. 啟動伺服器
php artisan serve

# 5. 在瀏覽器測試所有功能
```

---

## 🎊 測試成功標準

系統正常運作的標誌:

- ✅ 會員可以註冊並自動登入
- ✅ 會員可以登入登出
- ✅ 已登入會員結帳時資訊自動填入
- ✅ 訂單正確關聯到會員
- ✅ 後台可以查看和管理會員
- ✅ 後台可以看到會員的訂單數量
- ✅ 所有 Filament Resources 正常運作
- ✅ 前台所有頁面無錯誤

**祝測試順利！** 🚀

