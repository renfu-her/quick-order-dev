# 會員認證系統完整文檔

## ✅ 系統架構

Quick Order 現在使用**雙重認證系統**：

### 1. Admin 認證 (User)
- **用途**: 後台管理員
- **Guard**: `web` (default)
- **Model**: `App\Models\User`
- **登入位置**: `/admin`

### 2. Member 認證 (Member)
- **用途**: 前台顧客
- **Guard**: `member`
- **Model**: `App\Models\Member`
- **登入位置**: `/auth`

---

## 📊 資料庫結構

### Members Table
```php
Schema::create('members', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('phone')->nullable();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 關聯更新
- **Carts**: `user_id` → `member_id`
- **Orders**: `user_id` → `member_id`

---

## 🔐 Auth 配置 (config/auth.php)

### Guards
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',      // Admin 使用
    ],
    
    'member' => [
        'driver' => 'session',
        'provider' => 'members',    // 前台會員使用
    ],
],
```

### Providers
```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    
    'members' => [
        'driver' => 'eloquent',
        'model' => App\Models\Member::class,
    ],
],
```

### Password Resets
```php
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
    ],
    
    'members' => [
        'provider' => 'members',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
    ],
],
```

---

## 🎨 前台登入/註冊頁面

### 統一頁面設計 (`frontend/auth.blade.php`)

**特點:**
- ✅ Login 和 Register 在同一頁
- ✅ Tab 切換介面
- ✅ 響應式設計
- ✅ 表單驗證
- ✅ 錯誤訊息顯示
- ✅ Remember Me 功能

**URL**: `/auth`

### 登入表單欄位
- Email (必填)
- Password (必填)
- Remember Me (選填)

### 註冊表單欄位
- Full Name (必填)
- Email (必填, 唯一)
- Phone (選填)
- Password (必填, 最少 8 字元)
- Confirm Password (必填)

---

## 🔧 Controllers

### MemberAuthController

**位置**: `app/Http/Controllers/MemberAuthController.php`

**方法:**

#### 1. `showAuth()` - 顯示登入/註冊頁面
```php
GET /auth
返回: frontend.auth view
```

#### 2. `login()` - 處理登入
```php
POST /login
驗證: email, password
使用: Auth::guard('member')->attempt()
成功: 重定向到首頁
失敗: 返回錯誤訊息
```

#### 3. `register()` - 處理註冊
```php
POST /register
驗證: name, email, phone, password, password_confirmation
創建: 新 Member 記錄
自動登入: Auth::guard('member')->login()
重定向: 首頁
```

#### 4. `logout()` - 處理登出
```php
POST /logout
清除: Session
重定向: 首頁
```

---

## 🛣️ 路由定義

```php
// Member Auth Routes
Route::get('/auth', [MemberAuthController::class, 'showAuth'])
    ->name('member.auth');

Route::post('/login', [MemberAuthController::class, 'login'])
    ->name('member.login');

Route::post('/register', [MemberAuthController::class, 'register'])
    ->name('member.register');

Route::post('/logout', [MemberAuthController::class, 'logout'])
    ->name('member.logout');
```

---

## 🎯 使用方式

### 在 Controllers 中使用

#### 檢查登入狀態
```php
if (Auth::guard('member')->check()) {
    $member = Auth::guard('member')->user();
}
```

#### 獲取當前會員
```php
$member = Auth::guard('member')->user();
$memberId = Auth::guard('member')->id();
```

#### 要求登入
```php
// 在路由中
Route::middleware('auth:member')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
});
```

### 在 Blade 中使用

#### 檢查登入
```blade
@auth('member')
    <p>Welcome, {{ Auth::guard('member')->user()->name }}!</p>
@else
    <a href="{{ route('member.auth') }}">Login</a>
@endauth
```

#### 獲取會員資訊
```blade
@auth('member')
    {{ Auth::guard('member')->user()->email }}
    {{ Auth::guard('member')->user()->phone }}
@endauth
```

---

## 🎨 Filament Resources

### 1. UserResource (Admin 管理)
**位置**: `app/Filament/Resources/Users/`

**功能:**
- 管理後台 Admin 帳號
- 創建/編輯/刪除 User
- 密碼加密
- Navigation Group: "System Management"

### 2. MemberResource (會員管理)
**位置**: `app/Filament/Resources/Members/`

**功能:**
- 管理前台會員
- 查看會員訂單數量
- 啟用/停用會員
- 編輯會員資訊
- Navigation Group: "Customer Management"

---

## 📝 更新的功能

### CheckoutController
**自動填入會員資訊:**
```php
// 結帳頁面自動帶入已登入會員的資料
value="{{ old('customer_name', Auth::guard('member')->user()->name ?? '') }}"
value="{{ old('customer_email', Auth::guard('member')->user()->email ?? '') }}"
value="{{ old('customer_phone', Auth::guard('member')->user()->phone ?? '') }}"
```

**訂單關聯:**
```php
'member_id' => Auth::guard('member')->id(),
```

### Layout (app.blade.php)
**Header 顯示:**
```blade
@auth('member')
    <span>👤 {{ Auth::guard('member')->user()->name }}</span>
    <form action="{{ route('member.logout') }}" method="POST">
        @csrf
        <button>Logout</button>
    </form>
@else
    <a href="{{ route('member.auth') }}">Login / Register</a>
@endauth
```

---

## 🌱 測試資料

### 範例會員 (3個)

| Name | Email | Password | Phone |
|------|-------|----------|-------|
| Test Member | member@test.com | password | +1 (555) 123-4567 |
| John Doe | john@example.com | password | +1 (555) 234-5678 |
| Jane Smith | jane@example.com | password | +1 (555) 345-6789 |

### 測試登入
```
URL: http://localhost:8000/auth
Email: member@test.com
Password: password
```

---

## 🚀 使用流程

### 新用戶註冊流程
1. 訪問 `/auth`
2. 點擊 "Register" tab
3. 填寫表單
4. 提交
5. 自動登入並重定向到首頁
6. 顯示歡迎訊息

### 現有用戶登入流程
1. 訪問 `/auth`
2. 填寫 Email 和 Password
3. (選填) 勾選 Remember Me
4. 提交
5. 重定向到首頁
6. Header 顯示會員名稱

### 購物流程
1. **未登入**: 可以瀏覽、加入購物車
2. **結帳時**: 可選擇登入或以訪客身份結帳
3. **已登入**: 自動帶入會員資訊

### 登出流程
1. 點擊 Header 的 "Logout" 按鈕
2. Session 清除
3. 重定向到首頁
4. 顯示登出成功訊息

---

## 🔒 安全特性

### 密碼處理
```php
// 註冊時
'password' => Hash::make($validated['password'])

// 更新時 (Filament)
->dehydrateStateUsing(fn ($state) => Hash::make($state))
```

### Session 安全
```php
// 登入成功後
$request->session()->regenerate();

// 登出時
$request->session()->invalidate();
$request->session()->regenerateToken();
```

### 表單驗證
```php
// 註冊
'email' => 'required|email|unique:members,email|max:255',
'password' => 'required|string|min:8|confirmed',

// 登入
'email' => 'required|email',
'password' => 'required|string',
```

---

## 📋 Migration 順序

執行順序很重要：

1. `create_members_table.php` (創建 members 表)
2. `update_carts_use_member.php` (更新 carts)
3. `update_orders_use_member.php` (更新 orders)

**重要**: 必須先執行 Member table 創建，再更新關聯

---

## 🎯 後台管理

### Admin Panel

#### Users (系統管理員)
- 位置: System Management → Users
- 用途: 管理後台 Admin 帳號
- 功能: CRUD 操作

#### Members (前台會員)
- 位置: Customer Management → Members
- 用途: 管理前台顧客
- 功能:
  - 查看所有會員
  - 編輯會員資訊
  - 啟用/停用帳號
  - 查看訂單數量
  - 搜尋會員

---

## 🧪 測試步驟

### 1. 執行遷移
```bash
php artisan migrate:fresh --seed
```

### 2. 測試前台註冊
- 訪問 `/auth`
- 填寫註冊表單
- 確認自動登入
- 檢查 Header 顯示名稱

### 3. 測試前台登入
- 登出
- 使用測試帳號登入
- 確認成功

### 4. 測試購物流程
- 登入會員
- 加入商品到購物車
- 前往結帳
- 確認會員資訊自動填入
- 完成訂單

### 5. 測試後台管理
- 登入 Admin Panel
- 查看 Members 列表
- 確認新註冊的會員出現
- 查看會員的訂單數量

---

## 💡 進階功能建議

### 可選的擴展功能

1. **會員個人資料頁面**
   - 編輯個人資訊
   - 查看訂單歷史
   - 收藏清單

2. **Email 驗證**
   - 註冊後發送驗證郵件
   - 必須驗證才能下單

3. **忘記密碼**
   - 密碼重設功能
   - Email 發送重設連結

4. **社交登入**
   - Google 登入
   - Facebook 登入

5. **會員等級**
   - VIP 會員
   - 積分系統
   - 專屬優惠

---

## 📚 相關文檔

- **SETUP_INSTRUCTIONS.md** - 完整安裝指南
- **DATABASE_SETUP.md** - 資料庫設定
- **README_FINAL.md** - 專案總覽

---

## 🎉 完成清單

- ✅ Member Model 和 Migration
- ✅ 雙重 Auth Guards (web + member)
- ✅ 雙重 Providers (users + members)
- ✅ Cart 和 Order 使用 member_id
- ✅ 前台登入/註冊統一頁面
- ✅ MemberAuthController
- ✅ UserResource (Filament)
- ✅ MemberResource (Filament)
- ✅ Member Seeder
- ✅ Header 登入/登出顯示
- ✅ Checkout 自動填入會員資訊

---

## 🚀 立即測試

```bash
# 1. 執行遷移（包含 member 相關）
php artisan migrate:fresh --seed

# 2. 啟動伺服器
php artisan serve

# 3. 測試前台登入
http://localhost:8000/auth
Email: member@test.com
Password: password

# 4. 測試後台 Admin
http://localhost:8000/admin
Email: admin@quickorder.com
Password: password
```

**完成！** 🎊

