# UUID Filename Implementation

## 更新內容 (Update)

已將圖片上傳的文件命名方式改為使用 `Str::uuid()`，確保每個文件名都是全局唯一的。

## 修改文件 (Modified Files)

### `app/Services/ImageService.php`

**之前 (Before):**
```php
// Generate unique filename
$filename = uniqid() . '_' . time() . '.webp';
```

**之後 (After):**
```php
// Generate unique filename using UUID
$filename = Str::uuid() . '.webp';
```

## UUID 優勢 (UUID Advantages)

1. **全局唯一性 (Global Uniqueness)**
   - UUID (通用唯一識別碼) 保證在全球範圍內的唯一性
   - 不依賴於時間戳，避免時間衝突

2. **安全性 (Security)**
   - UUID 是隨機生成的，難以預測
   - 提高文件路徑的安全性

3. **標準格式 (Standard Format)**
   - 格式：`xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx`
   - 例如：`550e8400-e29b-41d4-a716-446655440000.webp`

4. **Laravel 原生支持 (Laravel Native Support)**
   - 使用 Laravel 的 `Str::uuid()` 輔助函數
   - 無需額外依賴

## 文件名範例 (Filename Examples)

### 舊格式 (Old Format)
```
677a1234_1735789456.webp
677a1235_1735789457.webp
```

### 新格式 (New Format - UUID)
```
9b5f8c2d-4e7a-4a1b-9f3c-8d2e6a5b7c9d.webp
a3b7c9e1-2f4d-4b8a-9e3f-7c5a9b2d8e6f.webp
```

## 實現細節 (Implementation Details)

### 處理流程 (Processing Flow)
1. 用戶上傳圖片 (User uploads image)
2. `ImageService` 接收文件 (ImageService receives file)
3. 使用 `Str::uuid()` 生成唯一文件名 (Generate unique filename using UUID)
4. 轉換為 WebP 格式 (Convert to WebP format)
5. 縮放圖片到指定尺寸 (Scale image to specified dimensions)
6. 保存到 storage (Save to storage)

### 代碼位置 (Code Location)
```php
// app/Services/ImageService.php - Line 45
$filename = Str::uuid() . '.webp';
$path = $directory . '/' . $filename;
```

## 應用範圍 (Application Scope)

此更改自動應用於所有使用 `ImageService` 的地方：

- ✅ Store 圖片上傳 (Store images)
- ✅ Product 圖片上傳 (Product images)
- ✅ 任何其他使用 `processAndConvertToWebp()` 方法的地方

## 測試方式 (Testing)

1. **上傳新圖片 (Upload New Image)**
   ```
   前往 /backend/stores/1/edit
   上傳一張圖片
   檢查 storage/app/public/stores/ 目錄
   確認文件名格式為 UUID
   ```

2. **驗證文件格式 (Verify File Format)**
   ```
   文件名應該是：xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx.webp
   例如：9b5f8c2d-4e7a-4a1b-9f3c-8d2e6a5b7c9d.webp
   ```

3. **檢查圖片顯示 (Check Image Display)**
   ```
   前端頁面應該正常顯示圖片
   圖片路徑：/storage/stores/[uuid].webp
   ```

## 向後兼容性 (Backward Compatibility)

- ✅ 舊的圖片文件仍然可以正常訪問和顯示
- ✅ 只有新上傳的圖片會使用 UUID 命名
- ✅ 不需要遷移現有的圖片文件

## 注意事項 (Notes)

1. **文件名長度 (Filename Length)**
   - UUID + .webp = 41 字符
   - 比舊格式 (約 25 字符) 稍長

2. **性能影響 (Performance Impact)**
   - `Str::uuid()` 生成速度非常快
   - 對性能影響可忽略不計

3. **唯一性保證 (Uniqueness Guarantee)**
   - UUID v4 的碰撞機率極低 (約 1/2^122)
   - 實際應用中可視為絕對唯一

## 狀態 (Status)
✅ **已完成 (COMPLETED)** - UUID 文件命名已成功實現並可以使用
