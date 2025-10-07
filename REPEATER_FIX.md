# Repeater Component Fix

## Issue Fixed
**Error**: `BadMethodCallException: Method Filament\Forms\Components\Repeater::deleteUploadedFileUsing does not exist.`

## Root Cause
The `deleteUploadedFileUsing` method was incorrectly applied to the `Repeater` component instead of the `FileUpload` component inside it.

## Solution Applied

### Before (Incorrect)
```php
Repeater::make('images')
    ->schema([
        FileUpload::make('image_path')
            ->saveUploadedFileUsing(function ($file) {
                // ... image processing
            }),
        // ... other fields
    ])
    ->deleteUploadedFileUsing(function ($file) {  // ❌ WRONG: This method doesn't exist on Repeater
        $imageService->deleteImage($file);
    }),
```

### After (Correct)
```php
Repeater::make('images')
    ->schema([
        FileUpload::make('image_path')
            ->saveUploadedFileUsing(function ($file) {
                // ... image processing
            })
            ->deleteUploadedFileUsing(function ($file) {  // ✅ CORRECT: This method exists on FileUpload
                $imageService->deleteImage($file);
            }),
        // ... other fields
    ]),
```

## Key Points

1. **Method Availability**: `deleteUploadedFileUsing` is only available on `FileUpload` components, not on `Repeater` components.

2. **Proper Placement**: The method should be chained directly to the `FileUpload` component that handles the file operations.

3. **Functionality**: The method will now properly handle file deletion when images are removed from the repeater.

## Files Modified
- `app/Filament/Resources/Stores/Schemas/StoreForm.php`

## Testing
The store edit page should now load without errors, and image deletion should work properly when removing images from the repeater.

## Status
✅ **FIXED** - The error has been resolved and the store edit page should now work correctly.
