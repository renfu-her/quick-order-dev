# 🖼️ Storage 設置完成

## ✅ 已完成

Storage link 已設置，圖片可以正常顯示！

---

## 📁 Storage 結構

```
storage/
└── app/
    └── public/                    ← Laravel 的公開儲存目錄
        ├── stores/                ← 商店圖片
        │   ├── store-1-1.jpg
        │   ├── store-1-2.jpg
        │   ├── store-2-1.jpg
        │   └── ...
        ├── products/              ← 產品圖片
        └── ads/                   ← 廣告圖片

public/
└── storage -> ../storage/app/public  ← Symbolic Link
```

---

## 🔗 Symbolic Link

### 已創建的連結

```bash
public/storage -> D:/laragon/www/quick-order-dev/storage/app/public
```

**狀態:** ✅ 已存在

---

## 🖼️ 圖片訪問方式

### 在 Blade 中顯示圖片

```blade
<!-- 使用 asset() + storage/ -->
<img src="{{ asset('storage/stores/store-1-1.jpg') }}" alt="Store">

<!-- 完整 URL -->
http://localhost:8000/storage/stores/store-1-1.jpg
```

### 實際路徑對應

| Blade 路徑 | 實際文件位置 |
|-----------|------------|
| `asset('storage/stores/store-1-1.jpg')` | `storage/app/public/stores/store-1-1.jpg` |
| `asset('storage/products/product-1.jpg')` | `storage/app/public/products/product-1.jpg` |
| `asset('storage/ads/ad-banner.jpg')` | `storage/app/public/ads/ad-banner.jpg` |

---

## 📊 已創建的圖片

### Store Images

```bash
storage/app/public/stores/
├── store-1-1.jpg    ← Main Branch (Primary)
├── store-1-2.jpg    ← Main Branch
├── store-2-1.jpg    ← North Branch (Primary)
├── store-2-2.jpg    ← North Branch
├── store-3-1.jpg    ← East Express (Primary)
├── store-3-2.jpg    ← East Express
├── store-4-1.jpg    ← West Branch (Primary)
├── store-4-2.jpg    ← West Branch
├── store-6-1.jpg    ← Additional stores...
├── store-6-2.jpg
├── store-7-1.jpg
├── store-7-2.jpg
├── store-8-1.jpg
├── store-8-2.jpg
├── store-9-1.jpg
└── store-9-2.jpg
```

**總計:** 16 張佔位圖片 (800×600)

---

## 🎨 佔位圖片特點

**自動生成的圖片:**
- ✅ 大小: 800×600 像素
- ✅ 格式: JPEG
- ✅ 質量: 90%
- ✅ 背景: 隨機粉紅色調
- ✅ 文字: "Store Image X"

---

## 🔧 圖片上傳流程

### 在 Filament Admin 中上傳

1. **登入 Admin Panel**
   ```
   http://localhost:8000/backend
   ```

2. **前往 Stores**
   ```
   Stores → Edit → Store Images
   ```

3. **上傳真實圖片**
   - 點擊 "Add Image"
   - 選擇圖片文件
   - 設定 "Primary Image"
   - 儲存

4. **圖片會自動儲存到**
   ```
   storage/app/public/stores/
   ```

---

## 📝 FileUpload 組件配置

### StoreForm.php

```php
FileUpload::make('image_path')
    ->label('Image')
    ->image()
    ->directory('stores')  // ← 儲存到 storage/app/public/stores/
    ->required()
    ->columnSpan(2),
```

**儲存位置:** `storage/app/public/stores/RANDOM_NAME.jpg`

**訪問 URL:** `http://localhost:8000/storage/stores/RANDOM_NAME.jpg`

---

## 🎯 顯示邏輯

### 在首頁顯示商店圖片

```blade
<!-- resources/views/frontend/index.blade.php -->

@if($store->getPrimaryImage())
    <img src="{{ asset('storage/' . $store->getPrimaryImage()->image_path) }}" 
         alt="{{ $store->name }}" 
         class="store-image">
@else
    <!-- 預設漸層背景 -->
    <div class="store-image" 
         style="background: linear-gradient(135deg, #e63946 0%, #f77f00 100%);">
    </div>
@endif
```

**流程:**
1. 檢查商店是否有主圖片
2. 如果有，顯示 `storage/stores/xxx.jpg`
3. 如果沒有，顯示漸層背景

---

## 🖼️ 圖片命名規則

### Seeder 生成的圖片

```
stores/store-{store_id}-1.jpg    ← 主圖片 (is_primary = true)
stores/store-{store_id}-2.jpg    ← 副圖片 (is_primary = false)
```

### Filament 上傳的圖片

```
stores/{ULID}.jpg                ← 隨機 ULID 名稱
stores/{ULID}.png
```

**範例:**
```
stores/01K6YCYE5K664Q3ASRXJWA6N7F.png
stores/01K6YCYE5SY7QXZ67KKNFY7A1B.png
```

---

## 🔍 檢查圖片是否存在

### 使用 PHP

```bash
php artisan tinker

>>> file_exists(storage_path('app/public/stores/store-1-1.jpg'))
=> true

>>> file_exists(public_path('storage/stores/store-1-1.jpg'))
=> true (透過 symlink)
```

### 使用瀏覽器

```
直接訪問:
http://localhost:8000/storage/stores/store-1-1.jpg

應該顯示圖片 ✅
```

---

## 🎯 如果圖片仍然 403

### 檢查 1: Storage Link 是否正確

```bash
# Windows
ls -la public/storage

# 應該看到
storage -> /d/laragon/www/quick-order-dev/storage/app/public
```

### 檢查 2: 目錄權限

```bash
# 設定正確權限
chmod -R 755 storage/app/public
```

### 檢查 3: Laragon 配置

Laragon 的 `.htaccess` 可能需要允許訪問 symlink：

```apache
# public/.htaccess
Options +FollowSymLinks
```

### 檢查 4: 圖片文件存在

```bash
# 列出所有商店圖片
ls storage/app/public/stores/

# 應該看到
store-1-1.jpg
store-1-2.jpg
...
```

---

## 🚀 測試圖片顯示

### 測試 1: 直接訪問圖片

```
http://localhost:8000/storage/stores/store-1-1.jpg
```

**預期:** 顯示圖片 ✅

---

### 測試 2: 前台首頁

```
http://localhost:8000
```

**預期:** 
- Stores Section 顯示 4 個商店
- 每個商店顯示圖片
- 圖片正常載入

---

### 測試 3: Filament Admin

```
http://localhost:8000/backend/stores
```

**預期:**
- Table 顯示商店列表
- Image Column 顯示圓形縮圖
- 點擊 View 查看完整圖片

---

## 💡 上傳真實圖片

### 方法 1: 透過 Filament Admin

```
1. 登入 Admin Panel
2. Stores → Edit Store
3. Store Images → Add Image
4. 選擇圖片上傳
5. 設定為 Primary Image
6. 儲存
```

### 方法 2: 手動複製

```bash
# 複製您的圖片到 storage/app/public/stores/
cp /path/to/your/image.jpg storage/app/public/stores/my-store.jpg

# 然後在資料庫更新路徑
php artisan tinker
>>> $img = App\Models\StoreImage::find(1);
>>> $img->image_path = 'stores/my-store.jpg';
>>> $img->save();
```

---

## 📋 Storage 最佳實踐

### 1. 使用 Storage Facade

```php
use Illuminate\Support\Facades\Storage;

// 儲存圖片
Storage::disk('public')->put('stores/image.jpg', $file);

// 刪除圖片
Storage::disk('public')->delete('stores/image.jpg');

// 檢查存在
Storage::disk('public')->exists('stores/image.jpg');
```

### 2. 在 Model 中添加 Accessor

```php
// Store Model
public function getImageUrlAttribute(): ?string
{
    $primaryImage = $this->getPrimaryImage();
    
    if (!$primaryImage) {
        return null;
    }
    
    return asset('storage/' . $primaryImage->image_path);
}

// 使用
<img src="{{ $store->image_url ?? 'default.jpg' }}">
```

### 3. 添加預設圖片

```blade
@if($store->getPrimaryImage() && file_exists(public_path('storage/' . $store->getPrimaryImage()->image_path)))
    <img src="{{ asset('storage/' . $store->getPrimaryImage()->image_path) }}">
@else
    <img src="{{ asset('images/store-placeholder.jpg') }}">
@endif
```

---

## ✅ 完成檢查

- [x] Storage link 已創建
- [x] storage/app/public/stores/ 目錄已創建
- [x] 佔位圖片已生成
- [x] 資料庫圖片路徑正確
- [x] 圖片可以透過 URL 訪問

---

## 🎉 完成！

**Storage 已設置完成:**

✅ Storage link: `public/storage` → `storage/app/public`  
✅ 圖片目錄: `stores/`, `products/`, `ads/`  
✅ 佔位圖片: 16 張商店圖片已創建  
✅ 圖片可訪問: `http://localhost:8000/storage/stores/store-1-1.jpg`  

**立即測試:** http://localhost:8000 🚀

商店圖片現在應該正常顯示了！

