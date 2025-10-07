<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageService
{
    protected ImageManager $manager;

    public function __construct()
    {
        // Create image manager with GD driver
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Process and convert uploaded image to WebP
     *
     * @param mixed $file Uploaded file
     * @param string $directory Storage directory (e.g., 'stores', 'products')
     * @param int $maxWidth Maximum width (default: 1200)
     * @param int $maxHeight Maximum height (default: 900)
     * @return string Saved file path
     */
    public function processAndConvertToWebp(
        mixed $file,
        string $directory,
        int $maxWidth = 1200,
        int $maxHeight = 900
    ): string {
        // Read the uploaded image
        $image = $this->manager->read($file);

        // Scale the image proportionally
        // Using scale() from https://image.intervention.io/v3/modifying-images/resizing#scale-images
        $image->scale(width: $maxWidth, height: $maxHeight);

        // Generate unique filename using UUID
        $filename = Str::uuid() . '.webp';
        $path = $directory . '/' . $filename;

        // Convert to WebP and save to storage
        $encoded = $image->toWebp(quality: 85);
        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }

    /**
     * Process image with cover (crop and resize)
     *
     * @param mixed $file Uploaded file
     * @param string $directory Storage directory
     * @param int $width Target width
     * @param int $height Target height
     * @param string $position Crop position (center, top, bottom, left, right)
     * @return string Saved file path
     */
    public function processCoverToWebp(
        mixed $file,
        string $directory,
        int $width = 800,
        int $height = 600,
        string $position = 'center'
    ): string {
        $image = $this->manager->read($file);

        // Use cover() to crop and resize
        $image->cover($width, $height, $position);

        // Generate unique filename
        $filename = uniqid() . '_' . time() . '.webp';
        $path = $directory . '/' . $filename;

        // Convert to WebP and save
        $encoded = $image->toWebp(quality: 85);
        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }

    /**
     * Delete image from storage
     *
     * @param string $path Image path
     * @return bool
     */
    public function deleteImage(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Get image dimensions
     *
     * @param string $path Image path in storage
     * @return array{width: int, height: int}|null
     */
    public function getImageDimensions(string $path): ?array
    {
        if (!Storage::disk('public')->exists($path)) {
            return null;
        }

        $fullPath = Storage::disk('public')->path($path);
        $image = $this->manager->read($fullPath);

        return [
            'width' => $image->width(),
            'height' => $image->height(),
        ];
    }
}

