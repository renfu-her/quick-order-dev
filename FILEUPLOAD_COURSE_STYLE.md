# FileUpload Implementation - Course Style

## 更新說明 (Update Summary)

已將所有 `FileUpload` 組件更新為使用與 `CourseResource.php` 相同的實現方式。

## 主要變更 (Key Changes)

### 1. UUID7 文件命名
使用 `Str::uuid7()` 替代 `Str::uuid()`，提供時間排序的 UUID。

### 2. 直接在組件中處理
不再使用外部 `ImageService`，直接在 `FileUpload` 組件中處理圖片。

### 3. 新增功能
- `downloadable()` - 允許下載已上傳的圖片
- `openable()` - 允許在新標籤中打開圖片
- `getUploadedFileNameForStorageUsing()` - 自定義文件名

## 完整實現 (Full Implementation)

### StoreForm.php
```php
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

FileUpload::make('image_path')
    ->label('Image')
    ->image()
    ->imageEditor()
    ->directory('stores')
    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
    ->maxSize(5120) // 5MB
    ->downloadable()
    ->openable()
    ->required()
    ->helperText('Image will be automatically converted to WebP format and optimized')
    ->columnSpan(2)
    ->getUploadedFileNameForStorageUsing(
        fn($file): string => (string) str(Str::uuid7() . '.webp')
    )
    ->saveUploadedFileUsing(function ($file) {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        $image->scale(width: 1200, height: 900);
        $filename = Str::uuid7()->toString() . '.webp';
        
        if (!file_exists(storage_path('app/public/stores'))) {
            mkdir(storage_path('app/public/stores'), 0755, true);
        }
        $image->toWebp(80)->save(storage_path('app/public/stores/' . $filename));
        return 'stores/' . $filename;
    })
    ->deleteUploadedFileUsing(function ($file) {
        if ($file) {
            Storage::disk('public')->delete($file);
        }
    }),
```

### ProductForm.php
```php
FileUpload::make('image_path')
    ->label('Image')
    ->image()
    ->imageEditor()
    ->directory('products')
    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
    ->maxSize(5120) // 5MB
    ->downloadable()
    ->openable()
    ->required()
    ->helperText('Image will be automatically converted to WebP format and optimized')
    ->columnSpan(2)
    ->getUploadedFileNameForStorageUsing(
        fn($file): string => (string) str(Str::uuid7() . '.webp')
    )
    ->saveUploadedFileUsing(function ($file) {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        $image->scale(width: 1200, height: 900);
        $filename = Str::uuid7()->toString() . '.webp';
        
        if (!file_exists(storage_path('app/public/products'))) {
            mkdir(storage_path('app/public/products'), 0755, true);
        }
        $image->toWebp(80)->save(storage_path('app/public/products/' . $filename));
        return 'products/' . $filename;
    })
    ->deleteUploadedFileUsing(function ($file) {
        if ($file) {
            Storage::disk('public')->delete($file);
        }
    }),
```

## UUID7 vs UUID4

### UUID4 (舊版)
- 格式：`550e8400-e29b-41d4-a716-446655440000`
- 完全隨機生成
- 無法排序

### UUID7 (新版)
- 格式：`018d6e1a-5b4c-7000-8000-000000000000`
- 包含時間戳（前 48 位）
- 可以按時間排序
- 更好的資料庫性能

## 處理流程 (Processing Flow)

1. **上傳** (Upload)
   - 用戶選擇圖片文件
   - Filament 接收文件

2. **命名** (Naming)
   - `getUploadedFileNameForStorageUsing` 生成 UUID7 文件名
   - 格式：`{uuid7}.webp`

3. **處理** (Processing)
   - `saveUploadedFileUsing` 處理圖片
   - 使用 Intervention Image 讀取
   - 縮放到 1200x900 (保持比例)
   - 轉換為 WebP 格式 (質量 80%)

4. **保存** (Saving)
   - 檢查目錄是否存在
   - 保存到 `storage/app/public/{directory}/`
   - 返回相對路徑

5. **刪除** (Deletion)
   - `deleteUploadedFileUsing` 處理文件刪除
   - 使用 Storage facade 安全刪除

## 圖片規格 (Image Specifications)

| 設定 | 值 |
|------|-----|
| 最大尺寸 | 1200x900 px |
| 縮放方式 | scale (保持比例) |
| 輸出格式 | WebP |
| 壓縮質量 | 80% |
| 最大文件大小 | 5MB |
| 支援格式 | JPEG, PNG, WebP |

## 優勢 (Advantages)

### 1. UUID7 的優勢
- ✅ **時間排序** - UUID7 包含時間戳，可以按時間排序
- ✅ **資料庫性能** - 更適合作為主鍵，減少索引碎片
- ✅ **唯一性** - 保持全局唯一性

### 2. 直接處理的優勢
- ✅ **簡化架構** - 不需要額外的 Service 類
- ✅ **更靈活** - 可以為不同資源使用不同的處理邏輯
- ✅ **更清晰** - 所有邏輯集中在一個地方

### 3. 新功能的優勢
- ✅ **downloadable()** - 方便管理員下載原圖
- ✅ **openable()** - 快速預覽圖片
- ✅ **imageEditor()** - 內建圖片編輯器

## 文件結構 (File Structure)

```
storage/app/public/
├── stores/
│   ├── 018d6e1a-5b4c-7000-8000-000000000001.webp
│   ├── 018d6e1a-5b4c-7000-8000-000000000002.webp
│   └── ...
└── products/
    ├── 018d6e1b-1234-7000-8000-000000000001.webp
    ├── 018d6e1b-1234-7000-8000-000000000002.webp
    └── ...
```

## 測試步驟 (Testing Steps)

### 1. 測試 Store 圖片上傳
```
1. 前往 /backend/stores/1/edit
2. 點擊 "Add Image"
3. 上傳一張 JPEG 或 PNG 圖片
4. 檢查 storage/app/public/stores/ 目錄
5. 確認文件名格式為 UUID7
6. 確認圖片已轉換為 WebP
7. 確認圖片尺寸為 1200x900 (或更小)
```

### 2. 測試 Product 圖片上傳
```
1. 前往 /backend/products/1/edit
2. 點擊 "Add Image"
3. 上傳一張 JPEG 或 PNG 圖片
4. 檢查 storage/app/public/products/ 目錄
5. 確認文件名格式為 UUID7
6. 確認圖片已轉換為 WebP
```

### 3. 測試圖片刪除
```
1. 在 Store 或 Product 編輯頁面
2. 刪除一張圖片
3. 檢查 storage 目錄，確認文件已刪除
```

### 4. 測試新功能
```
1. 測試 Download 功能 - 點擊下載按鈕
2. 測試 Open 功能 - 點擊在新標籤打開
3. 測試 Image Editor - 使用內建編輯器
```

## 修改的文件 (Modified Files)

- ✅ `app/Filament/Resources/Stores/Schemas/StoreForm.php`
- ✅ `app/Filament/Resources/Products/Schemas/ProductForm.php`

## 向後兼容性 (Backward Compatibility)

- ✅ 舊的圖片文件仍然可以正常訪問
- ✅ 只有新上傳的圖片會使用 UUID7 命名
- ✅ 不需要遷移現有的圖片文件
- ✅ `ImageService.php` 保持不變，可供其他地方使用

## 注意事項 (Notes)

1. **文件名長度**
   - UUID7 + .webp = 41 字符
   - 確保資料庫欄位足夠長 (建議 VARCHAR(255))

2. **目錄權限**
   - 代碼會自動創建目錄（如果不存在）
   - 權限設置為 0755

3. **圖片質量**
   - WebP 質量設置為 80%
   - 可以根據需求調整（1-100）

4. **縮放方式**
   - 使用 `scale()` 保持比例縮放
   - 不會裁切圖片
   - 如需裁切，改用 `cover()`

## 狀態 (Status)
✅ **已完成 (COMPLETED)** - 所有 FileUpload 組件已更新為 Course Style
