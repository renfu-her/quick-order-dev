# 🏪 Stores 前台顯示說明

## ✅ 已完成

Store 資訊現在已在首頁顯示！

---

## 📍 顯示位置

**URL:** http://localhost:8000 (首頁)

**Section 順序:**
1. Hero Section (歡迎標語)
2. **Our Locations (商店列表)** ⭐ 新增
3. Special Offers (廣告)
4. Our Menu (產品列表)

---

## 🎨 顯示內容

### 每個 Store 卡片包含:

✅ **商店圖片**
- 顯示主圖片 (is_primary = true)
- 如果無圖片，顯示漸層背景

✅ **商店名稱**
- 大標題顯示

✅ **商店描述**
- 限制 100 字元
- 可選顯示

✅ **地址資訊**
- 📍 圖示 + 完整地址

✅ **電話號碼**
- 📞 圖示 + 可點擊電話連結
- 點擊直接撥打

✅ **今日營業時間**
- 🕐 圖示 + 自動顯示今天的營業時間
- 根據系統日期自動判斷

✅ **Google 地圖連結**
- 🗺️ 按鈕
- 使用經緯度座標
- 新視窗開啟 Google Maps

---

## 🎯 功能特點

### 1. 響應式設計
```css
grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
```
- 自動適應螢幕寬度
- 手機版: 1 欄
- 平板版: 2 欄
- 桌面版: 3-4 欄

### 2. 動態營業時間
```php
$today = now()->format('l');  // 'Monday', 'Tuesday', etc.
$todayHours = $store->hours[$today] ?? 'Closed';
```
- 自動顯示今天的營業時間
- 如果今天未設定，顯示 "Closed"

### 3. 互動效果
- ✅ Hover 卡片上升效果
- ✅ 陰影加深
- ✅ 地圖按鈕變色

### 4. 只顯示啟用商店
```php
Store::where('is_active', true)->get();
```
- 停用的商店不會顯示在前台
- Airport 分店（已停用）不會出現

---

## 📊 測試資料顯示

訪問 http://localhost:8000 應該看到:

### 商店 1: Quick Order Main Branch
```
[商店圖片]

Quick Order Main Branch

Our flagship store in downtown. Offering the full menu with dine-in, 
takeout, and delivery...

📍 123 Main Street, Downtown, City
📞 +1 (555) 100-0001
🕐 Today: 09:00 - 22:00 (或根據今天)

[🗺️ View on Map]
```

### 商店 2: Quick Order North Branch
```
[商店圖片]

Quick Order North Branch

Convenient location in the north district. Perfect for quick 
takeout and delivery.

📍 456 North Avenue, North District, City
📞 +1 (555) 200-0002
🕐 Today: 10:00 - 21:00

[🗺️ View on Map]
```

以此類推，共 4 個啟用的商店會顯示。

---

## 🔧 自訂選項

### 修改顯示數量

如果想限制顯示數量：

```php
// FrontendController.php
$stores = Store::where('is_active', true)
    ->with(['images'])
    ->orderBy('name')
    ->limit(3)  // 只顯示 3 個
    ->get();
```

### 修改排序

```php
// 按創建時間
->latest()

// 按名稱
->orderBy('name')

// 按自訂排序欄位（需添加到 migration）
->orderBy('display_order')
```

### 添加 "View All Stores" 按鈕

在 stores section 底部添加：

```blade
<div style="text-align: center; margin-top: 2rem;">
    <a href="{{ route('stores.index') }}" class="btn btn-primary">
        View All Locations
    </a>
</div>
```

---

## 🗺️ Google Maps 整合

### 當前功能
點擊 "View on Map" 會開啟 Google Maps：
```
https://www.google.com/maps?q=25.0330,121.5654
```

### 進階選項

#### 1. 嵌入地圖
```blade
<iframe 
    width="100%" 
    height="200" 
    frameborder="0" 
    style="border:0; border-radius: 5px;" 
    src="https://www.google.com/maps?q={{ $store->latitude }},{{ $store->longitude }}&output=embed">
</iframe>
```

#### 2. 使用 Google Maps API
```blade
<div id="map-{{ $store->id }}" style="height: 200px;"></div>
<script>
    // Google Maps JavaScript API
</script>
```

---

## 📱 響應式效果

### 桌面版 (1200px+)
```
┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐
│Store 1 │ │Store 2 │ │Store 3 │ │Store 4 │
└────────┘ └────────┘ └────────┘ └────────┘
```

### 平板版 (768px - 1199px)
```
┌────────┐ ┌────────┐
│Store 1 │ │Store 2 │
└────────┘ └────────┘
┌────────┐ ┌────────┐
│Store 3 │ │Store 4 │
└────────┘ └────────┘
```

### 手機版 (< 768px)
```
┌────────┐
│Store 1 │
└────────┘
┌────────┐
│Store 2 │
└────────┘
┌────────┐
│Store 3 │
└────────┘
```

---

## 🎯 測試檢查清單

訪問 http://localhost:8000 並確認:

- [ ] Hero Section 正常顯示
- [ ] "Our Locations" 標題出現
- [ ] 看到 4 個商店卡片（不包含 Airport）
- [ ] 每個商店有圖片或漸層背景
- [ ] 商店名稱正確
- [ ] 描述文字顯示
- [ ] 地址、電話、營業時間顯示
- [ ] "View on Map" 按鈕正常
- [ ] 點擊地圖按鈕開啟 Google Maps
- [ ] 點擊電話可以撥打
- [ ] 卡片 hover 效果正常
- [ ] Special Offers section 在下方
- [ ] Our Menu section 在最下方

---

## 🎨 頁面結構

```
┌─────────────────────────────┐
│      Hero Section           │
│  Welcome to Quick Order     │
└─────────────────────────────┘

┌─────────────────────────────┐
│    Our Locations (新)        │
├─────────┬─────────┬─────────┤
│ Store 1 │ Store 2 │ Store 3 │
├─────────┼─────────┼─────────┤
│ Store 4 │         │         │
└─────────┴─────────┴─────────┘

┌─────────────────────────────┐
│    Special Offers           │
├─────────┬─────────┬─────────┤
│  Ad 1   │  Ad 2   │  Ad 3   │
└─────────┴─────────┴─────────┘

┌─────────────────────────────┐
│      Our Menu               │
├─────────┬─────────┬─────────┤
│Product 1│Product 2│Product 3│
├─────────┼─────────┼─────────┤
│Product 4│Product 5│Product 6│
└─────────┴─────────┴─────────┘
```

---

## ✅ 完成！

**Stores 現在已顯示在首頁！**

**功能:**
- ✅ 顯示所有啟用商店
- ✅ 商店圖片
- ✅ 完整資訊（地址、電話、營業時間）
- ✅ Google Maps 整合
- ✅ 響應式設計
- ✅ 互動效果

**立即查看:** http://localhost:8000 🚀

