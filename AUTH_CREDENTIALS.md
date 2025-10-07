# 🔐 Quick Order 登入資訊

## 後台管理 (Admin Panel)

**URL**: http://localhost:8000/admin

### Admin 帳號
| 角色 | Email | Password |
|------|-------|----------|
| 管理員 | admin@quickorder.com | password |

**功能權限:**
- ✅ 產品管理
- ✅ 廣告管理
- ✅ 訂單管理
- ✅ 優惠券管理
- ✅ 會員管理
- ✅ 系統用戶管理

---

## 前台會員 (Customer Frontend)

**URL**: http://localhost:8000/auth

### 測試會員帳號

| 姓名 | Email | Password | 電話 |
|------|-------|----------|------|
| Test Member | member@test.com | password | +1 (555) 123-4567 |
| John Doe | john@example.com | password | +1 (555) 234-5678 |
| Jane Smith | jane@example.com | password | +1 (555) 345-6789 |

**功能權限:**
- ✅ 瀏覽產品
- ✅ 加入購物車
- ✅ 使用優惠券
- ✅ 下訂單
- ✅ 自動填入個人資訊

---

## 測試優惠券代碼

在結帳時輸入以下代碼測試:

| 代碼 | 折扣 | 最低消費 | 最大折扣 |
|------|------|----------|----------|
| WELCOME10 | 10% | $20 | $5 |
| SAVE5 | $5 固定 | $30 | - |
| SUMMER20 | 20% | $50 | $15 |
| FREESHIP | $3 固定 | $25 | - |

---

## 快速測試流程

### 測試 1: 前台會員註冊
```
1. 訪問 http://localhost:8000/auth
2. 點擊 "Register" tab
3. 填寫:
   Name: New User
   Email: newuser@test.com
   Password: password
   Confirm Password: password
4. 提交
5. 確認自動登入並重定向到首頁
```

### 測試 2: 前台會員登入
```
1. 訪問 http://localhost:8000/auth
2. 填寫:
   Email: member@test.com
   Password: password
3. 勾選 "Remember Me" (選填)
4. 提交
5. 確認登入成功
```

### 測試 3: 會員購物流程
```
1. 以 member@test.com 登入
2. 瀏覽產品並加入購物車
3. 前往結帳
4. 確認表單自動填入會員資訊
5. 輸入優惠券: WELCOME10
6. 選擇付款方式
7. 完成訂單
```

### 測試 4: 後台查看會員訂單
```
1. 登入 Admin Panel (admin@quickorder.com)
2. 前往 "Customer Management" → "Members"
3. 查看會員列表
4. 確認 "Orders" 欄位顯示訂單數量
5. 前往 "Orders" 查看訂單詳情
6. 確認訂單關聯到正確的會員
```

---

## 🔄 密碼重設

**預設密碼**: 所有測試帳號的密碼都是 `password`

**修改密碼 (後台)**:
1. 登入 Admin Panel
2. 前往 Users 或 Members
3. 編輯帳號
4. 輸入新密碼
5. 保存

---

## ⚠️ 注意事項

### 生產環境部署前

1. **修改所有預設密碼**
   ```bash
   # 不要在生產環境使用 'password'
   ```

2. **刪除測試帳號**
   ```bash
   # 移除 member@test.com, john@example.com 等
   ```

3. **限制 Admin 訪問**
   ```bash
   # 配置 IP 白名單或 VPN
   ```

4. **啟用 Email 驗證**
   ```php
   // 要求會員驗證 Email
   ```

---

## 🎯 記住這些

### 前台 Member
- Guard: `member`
- 登入頁: `/auth`
- 用於: 購物下單

### 後台 Admin
- Guard: `web` (default)
- 登入頁: `/admin/login`
- 用於: 系統管理

### 區分使用
```php
// 前台
Auth::guard('member')->user()

// 後台
Auth::user()  // 或 Auth::guard('web')->user()
```

---

**安全第一！** 🔒
**記得在生產環境修改所有預設密碼！** ⚠️

