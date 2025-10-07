# Toast Notification System - 使用指南

## 概述

全局 Toast 通知系统已經實現，特點：
- ✅ 從右側彈出動畫
- ✅ 5 秒後自動消失
- ✅ 可點擊 X 關閉
- ✅ 支持多種類型（成功、錯誤、警告、資訊）
- ✅ 全域可用
- ✅ 響應式設計

## 文件結構

```
public/
├── css/custom/toast.css    # Toast 樣式
└── js/toast.js              # Toast 邏輯

resources/views/layouts/app.blade.php  # 已整合 Toast 系統
```

## 使用方法

### 1. JavaScript 中使用

#### 基本用法
```javascript
// 成功訊息
successToast('Item added to cart successfully!');

// 錯誤訊息
errorToast('Failed to process your request');

// 警告訊息
warningToast('Please check your input');

// 資訊訊息
infoToast('Processing your request...');
```

#### 自定義標題
```javascript
successToast('Order placed successfully!', 'Success');
errorToast('Payment failed', 'Error');
warningToast('Stock running low', 'Warning');
infoToast('New update available', 'Information');
```

#### 使用 Toast 物件（完整控制）
```javascript
// 基本用法
Toast.show('Message here', 'success');

// 自定義標題和持續時間
Toast.show('Message here', 'error', 'Custom Title', 3000); // 3 秒消失

// 永不自動消失（需手動關閉）
Toast.show('Important message', 'warning', null, 0);

// 快捷方法
Toast.success('Success message');
Toast.error('Error message');
Toast.warning('Warning message');
Toast.info('Info message');
```

### 2. Laravel Blade 中使用

#### Session Flash Messages（自動轉換為 Toast）
```php
// Controller 中
return redirect()->back()->with('success', 'Order created successfully!');
return redirect()->back()->with('error', 'Something went wrong');
return redirect()->back()->with('warning', 'Please verify your email');
return redirect()->back()->with('info', 'Your request is being processed');
```

這些 session flash messages 會自動轉換為 Toast 通知。

#### 直接在 Blade 中顯示
```blade
@push('scripts')
<script>
    successToast('Welcome back, {{ auth()->user()->name }}!');
</script>
@endpush
```

### 3. AJAX 請求中使用

```javascript
fetch('/api/endpoint', {
    method: 'POST',
    body: formData,
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        successToast(data.message);
    } else {
        errorToast(data.message || 'Operation failed');
    }
})
.catch(error => {
    console.error('Error:', error);
    errorToast('An unexpected error occurred');
});
```

## Toast 類型

### Success (成功)
- 圖示: ✅
- 顏色: 綠色
- 用途: 操作成功

```javascript
successToast('Order placed successfully!');
```

### Error (錯誤)
- 圖示: ❌
- 顏色: 紅色
- 用途: 操作失敗或錯誤

```javascript
errorToast('Payment processing failed');
```

### Warning (警告)
- 圖示: ⚠️
- 顏色: 橙色
- 用途: 警告訊息

```javascript
warningToast('Low stock alert');
```

### Info (資訊)
- 圖示: ℹ️
- 顏色: 藍色
- 用途: 一般資訊

```javascript
infoToast('Processing your request...');
```

## 配置選項

在 `public/js/toast.js` 中可以修改默認配置：

```javascript
const TOAST_CONFIG = {
    duration: 5000, // 持續時間（毫秒）
    icons: {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️'
    },
    titles: {
        success: 'Success',
        error: 'Error',
        warning: 'Warning',
        info: 'Information'
    }
};
```

## 樣式自定義

在 `public/css/custom/toast.css` 中可以修改樣式：

```css
/* 修改位置 */
#toast-container {
    top: 80px;    /* 距離頂部 */
    right: 20px;  /* 距離右側 */
}

/* 修改動畫時間 */
.toast {
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

/* 修改進度條時間 */
.toast-progress {
    transition: width 5s linear;  /* 5 秒 */
}
```

## 響應式設計

Toast 系統已針對移動設備優化：

- 桌面：固定在右上角，寬度 300-400px
- 移動設備：橫跨整個螢幕寬度，頂部顯示

## 已整合的頁面

以下頁面已替換為 Toast 系統：

1. ✅ `store-detail.blade.php` - 加入購物車通知
2. ✅ `layouts/app.blade.php` - Session flash messages
3. ✅ 所有頁面的全域通知

## 範例場景

### 加入購物車
```javascript
fetch('/cart/add', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        successToast('Item added to cart successfully!');
        // 更新購物車數量
        if (data.cart_count) {
            updateCartBadge(data.cart_count);
        }
    } else {
        errorToast(data.message || 'Failed to add item');
    }
});
```

### 表單驗證
```javascript
if (!email.value) {
    errorToast('Please enter your email address', 'Validation Error');
    return false;
}

if (!isValidEmail(email.value)) {
    warningToast('Please enter a valid email address');
    return false;
}

successToast('Form submitted successfully!');
```

### 登入成功
```php
// LoginController.php
return redirect()->route('home')
    ->with('success', 'Welcome back, ' . auth()->user()->name . '!');
```

## 進階用法

### 多個 Toast 同時顯示
系統支持同時顯示多個 Toast，它們會自動堆疊：

```javascript
successToast('Order placed');
infoToast('Confirmation email sent');
warningToast('Low stock on item XYZ');
```

### 手動關閉 Toast
```javascript
const toast = Toast.show('This message stays until closed', 'info', null, 0);

// 稍後關閉
setTimeout(() => {
    Toast.hide(toast);
}, 10000);
```

## 故障排除

### Toast 沒有顯示
1. 檢查瀏覽器控制台是否有 JavaScript 錯誤
2. 確認 `toast.js` 和 `toast.css` 已正確載入
3. 檢查是否有 CSS 衝突

### Toast 位置不正確
- 檢查 `#toast-container` 的 z-index 值
- 確認沒有其他 CSS 覆蓋位置樣式

### Toast 不會自動消失
- 檢查 `TOAST_CONFIG.duration` 設置
- 確認沒有傳入 `duration: 0`

## 最佳實踐

1. ✅ 使用適當的類型（success/error/warning/info）
2. ✅ 訊息簡短明確（建議 1-2 行）
3. ✅ 對重要操作使用成功通知
4. ✅ 對錯誤提供有幫助的訊息
5. ✅ 避免過度使用（不要同時顯示太多 Toast）
6. ✅ 對長時間操作使用資訊類型提示
7. ❌ 不要在 Toast 中包含過長的文字
8. ❌ 不要用 Toast 代替所有確認對話框

## 瀏覽器支援

- ✅ Chrome (最新版)
- ✅ Firefox (最新版)
- ✅ Safari (最新版)
- ✅ Edge (最新版)
- ✅ Mobile Safari (iOS 12+)
- ✅ Chrome Mobile (Android 8+)

## 未來改進

可能的增強功能：
- [ ] 加入音效選項
- [ ] 支援 HTML 內容
- [ ] 可點擊的操作按鈕
- [ ] 更多動畫效果
- [ ] Toast 歷史記錄
- [ ] 批量關閉功能

## 相關文件

- 舊的 alert 系統已被替換
- Session flash messages 自動轉換為 Toast
- 保留 confirm() 用於需要確認的操作
