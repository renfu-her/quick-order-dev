# 前台設計說明

## 🎨 樣式架構

Quick Order 前台使用**內聯 CSS** 設計，無需編譯工具。

### 為什麼使用內聯 CSS？

✅ **優點:**
- 不需要 Vite/Webpack 編譯
- 部署簡單（無需 build 步驟）
- 樣式與組件在一起，易於維護
- 無需擔心 CSS 快取問題
- 載入速度快（無額外 CSS 文件請求）

⚠️ **注意:**
- 模板已包含所有必要樣式
- 不使用 `@vite()` 指令
- 不需要執行 `npm run build`

## 📁 模板結構

### Base Template (layouts/app.blade.php)
包含：
- Header 導航列
- Footer 資訊
- 基礎樣式
- Alert 訊息樣式
- 容器布局

### 頁面模板
每個頁面都 `@extends('layouts.app')` 並包含自己的內聯樣式：

1. **frontend/index.blade.php** - 首頁
   - Hero 區塊
   - 廣告網格
   - 產品網格
   - 卡片樣式

2. **frontend/product.blade.php** - 產品詳情
   - 圖片畫廊
   - 縮圖切換
   - 溫度選擇
   - 數量控制

3. **frontend/cart.blade.php** - 購物車
   - 購物車項目
   - 數量調整
   - 優惠券輸入
   - 摘要區塊

4. **frontend/checkout.blade.php** - 結帳
   - 客戶表單
   - 付款選項
   - 訂單摘要

5. **frontend/order-confirmation.blade.php** - 訂單確認
   - 成功圖示
   - 訂單資訊
   - 客戶資料

## 🎯 樣式指南

### 主色調
- 主要色: `#e63946` (紅色)
- 次要色: `#f77f00` (橙色)
- 成功色: `#28a745` (綠色)
- 危險色: `#dc3545` (紅色)
- 警告色: `#ffc107` (黃色)
- 資訊色: `#17a2b8` (藍色)

### 字體
- 主要字體: 'Figtree', sans-serif
- 來源: Google Fonts (fonts.bunny.net)

### 響應式斷點
```css
@media (max-width: 768px) {
    /* 手機版樣式 */
}

@media (max-width: 968px) {
    /* 平板版樣式 */
}
```

## 🔧 如果需要使用 Vite

如果未來要使用 Vite 編譯資產：

### 1. 安裝依賴
```bash
npm install
```

### 2. 開發模式
```bash
npm run dev
```

### 3. 生產編譯
```bash
npm run build
```

### 4. 在模板中使用
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

## 📝 當前配置

目前的配置為**無需編譯**：
- ✅ 所有 CSS 已內聯在模板中
- ✅ JavaScript 使用內聯 `<script>` 標籤
- ✅ 無需執行 npm 命令
- ✅ 直接可用

## 🚀 優勢

### 開發便利性
- 修改立即生效
- 無需等待編譯
- 無需 Node.js 環境

### 部署簡單性
- 只需部署 PHP 文件
- 無需 build 步驟
- 無需前端工具鏈

### 性能
- 減少 HTTP 請求
- CSS 直接在 HTML 中
- 無額外載入時間

## ⚠️ 已修正的問題

**問題:**
```
ViteManifestNotFoundException
Vite manifest not found at: public\build/manifest.json
```

**解決方案:**
已從 `layouts/app.blade.php` 中移除:
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**原因:**
前台使用內聯 CSS，不需要 Vite 編譯。

## 💡 最佳實踐

### 使用內聯樣式時
✅ 在 `<style>` 標籤中定義樣式  
✅ 使用 `@push('styles')` 添加頁面特定樣式  
✅ 使用 `@push('scripts')` 添加頁面特定腳本  
✅ 保持樣式模組化，每個頁面獨立  

### 範例
```blade
@extends('layouts.app')

@section('content')
<style>
    /* 頁面特定樣式 */
    .my-component {
        /* ... */
    }
</style>

<div class="my-component">
    <!-- 內容 -->
</div>
@endsection

@push('scripts')
<script>
    // 頁面特定 JavaScript
</script>
@endpush
```

## 🎉 結論

Quick Order 前台使用簡單高效的內聯 CSS 架構：
- ✅ 無需編譯工具
- ✅ 立即可用
- ✅ 易於維護
- ✅ 部署簡單

現在系統可以正常運行了！🚀

