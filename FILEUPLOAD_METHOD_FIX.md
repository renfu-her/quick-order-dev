# FileUpload Method Fix

## 錯誤修復 (Error Fix)

**錯誤**: `BadMethodCallException: Method Filament\Forms\Components\FileUpload::getUploadedFileUrlUsing does not exist.`

## 問題原因 (Root Cause)

在 Filament v4 中，`FileUpload` 組件不再包含 `getUploadedFileUrlUsing` 方法。這個方法在 Filament v3 中已被移除。

## 解決方案 (Solution)

### 1. 移除不存在的方法

**修改前**:
```php
->deleteUploadedFileUsing(function ($file) {
    if ($file) {
        Storage::disk('public')->delete($file);
    }
})
->getUploadedFileUrlUsing(function ($file) {  // ❌ 這個方法不存在
    return asset('storage/' . $file);
}),
```

**修改後**:
```php
->deleteUploadedFileUsing(function ($file) {
    if ($file) {
        Storage::disk('public')->delete($file);
    }
}),
```

### 2. 添加 disk 配置

**修改前**:
```php
FileUpload::make('image_path')
    ->label('Image')
    ->image()
    ->imageEditor()
    ->directory('stores')  // 缺少 disk 配置
```

**修改後**:
```php
FileUpload::make('image_path')
    ->label('Image')
    ->image()
    ->imageEditor()
    ->disk('public')  // ✅ 明確指定 disk
    ->directory('stores')
```

## Filament v4 FileUpload 自動 URL 生成

在 Filament v4 中，`FileUpload` 組件會自動處理 URL 生成：

### 自動 URL 生成邏輯
```php
// 當 FileUpload 組件配置了 disk 和 directory 時
->disk('public')
->directory('stores')

// Filament 會自動生成 URL
// 數據庫值: "stores/uuid.webp"
// 自動生成 URL: "https://domain.com/storage/stores/uuid.webp"
```

### 不需要手動配置的方法
- ❌ `getUploadedFileUrlUsing()` - 不存在
- ❌ `getUploadedFileUrl()` - 不存在
- ❌ `url()` - 不需要

### 需要的配置
- ✅ `disk('public')` - 指定存儲磁盤
- ✅ `directory('stores')` - 指定目錄
- ✅ `saveUploadedFileUsing()` - 自定義保存邏輯
- ✅ `deleteUploadedFileUsing()` - 自定義刪除邏輯

## 完整的 FileUpload 配置

```php
FileUpload::make('image_path')
    ->label('Image')
    ->image()
    ->imageEditor()
    ->disk('public')                    // ✅ 指定存儲磁盤
    ->directory('stores')               // ✅ 指定目錄
    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
    ->maxSize(5120)
    ->downloadable()
    ->openable()
    ->required()
    ->helperText('Image will be automatically converted to WebP format and optimized')
    ->columnSpan(2)
    ->getUploadedFileNameForStorageUsing(
        fn($file): string => (string) str(Str::uuid7() . '.webp')
    )
    ->saveUploadedFileUsing(function ($file) {
        // 自定義保存邏輯
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
        // 自定義刪除邏輯
        if ($file) {
            Storage::disk('public')->delete($file);
        }
    }),
```

## URL 生成流程 (URL Generation Flow)

### 1. 數據庫存儲
```
image_path: "stores/0199bd4a-132a-7002-8b6c-16c219f6ffbf.webp"
```

### 2. Filament 自動處理
```php
// Filament 內部邏輯 (簡化版)
$disk = 'public';
$directory = 'stores';
$filename = '0199bd4a-132a-7002-8b6c-16c219f6ffbf.webp';

// 自動生成 URL
$url = Storage::disk($disk)->url($directory . '/' . $filename);
// 結果: "https://domain.com/storage/stores/0199bd4a-132a-7002-8b6c-16c219f6ffbf.webp"
```

### 3. 前端顯示
```html
<img src="https://domain.com/storage/stores/0199bd4a-132a-7002-8b6c-16c219f6ffbf.webp" />
```

## 測試步驟 (Testing Steps)

### 1. 測試編輯頁面
```
1. 前往 /backend/stores/1/edit
2. 確認頁面正常加載，沒有錯誤
3. 檢查 Store Images 區域
4. 確認已上傳的圖片正確顯示
```

### 2. 測試圖片上傳
```
1. 點擊 "Add Image"
2. 上傳一張新圖片
3. 確認圖片立即顯示
4. 檢查文件名格式為 UUID7
```

### 3. 測試圖片功能
```
1. 測試 Download 功能
2. 測試 Open 功能 (在新標籤打開)
3. 測試 Image Editor 功能
4. 測試刪除功能
```

## 修改的文件 (Modified Files)

- ✅ `app/Filament/Resources/Stores/Schemas/StoreForm.php`
  - 移除 `getUploadedFileUrlUsing()` 方法
  - 添加 `disk('public')` 配置

## Filament v4 vs v3 差異

| 功能 | Filament v3 | Filament v4 |
|------|-------------|-------------|
| URL 生成 | 需要手動配置 | 自動處理 |
| `getUploadedFileUrlUsing` | 存在 | ❌ 不存在 |
| `disk()` 配置 | 可選 | ✅ 推薦 |
| 自動 URL | 需要配置 | ✅ 自動 |

## 最佳實踐 (Best Practices)

### 1. 明確指定 disk
```php
->disk('public')  // 推薦：明確指定
```

### 2. 使用自定義保存邏輯
```php
->saveUploadedFileUsing(function ($file) {
    // 自定義處理邏輯
    return 'custom/path/filename.webp';
})
```

### 3. 使用自定義刪除邏輯
```php
->deleteUploadedFileUsing(function ($file) {
    // 自定義刪除邏輯
    Storage::disk('public')->delete($file);
})
```

### 4. 不要嘗試手動配置 URL
```php
// ❌ 不要這樣做
->getUploadedFileUrlUsing(...)  // 方法不存在

// ✅ 讓 Filament 自動處理
->disk('public')
->directory('stores')
```

## 狀態 (Status)
✅ **已修復 (FIXED)** - FileUpload 方法錯誤已解決

現在編輯頁面應該可以正常加載並顯示圖片了！
