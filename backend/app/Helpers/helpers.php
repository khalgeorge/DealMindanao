<?php

/**
 * Helper functions for the application
 */

if (!function_exists('meta_title')) {
    /**
     * Generate SEO-friendly meta title
     */
    function meta_title(?string $title = null, bool $includeSiteName = true): string
    {
        return \App\Helpers\MetaHelper::title($title, $includeSiteName);
    }
}

if (!function_exists('meta_description')) {
    /**
     * Generate SEO-friendly meta description
     */
    function meta_description(?string $description = null, int $maxLength = 160): string
    {
        return \App\Helpers\MetaHelper::description($description, $maxLength);
    }
}

if (!function_exists('meta_keywords')) {
    /**
     * Generate meta keywords
     */
    function meta_keywords($keywords = null): string
    {
        return \App\Helpers\MetaHelper::keywords($keywords);
    }
}

if (!function_exists('og_image')) {
    /**
     * Get Open Graph image URL
     */
    function og_image(?string $image = null): string
    {
        return \App\Helpers\MetaHelper::ogImage($image);
    }
}

if (!function_exists('sanitize_meta')) {
    /**
     * Sanitize text for meta tags
     */
    function sanitize_meta(?string $text = null): string
    {
        return \App\Helpers\MetaHelper::sanitize($text);
    }
}

if (!function_exists('product_image_url')) {
    /**
     * Return a fully-qualified product image URL.
     *
     * Handles three storage variants stored in the images JSON column:
     *   1. Already a full URL   → return as-is
     *   2. Starts with /storage → strip leading slash, pass to Storage::url()
     *   3. Relative path        → pass directly to Storage::url()
     *
     * Falls back to /images/unknown-product.svg when the array is empty.
     */
    function product_image_url(?array $images, int $index = 0): string
    {
        $fallback = '/images/unknown-product.svg';

        if (empty($images) || !isset($images[$index])) {
            return $fallback;
        }

        $raw = $images[$index];

        if (empty($raw)) {
            return $fallback;
        }

        // Already a full absolute URL (e.g. seeded external URLs or previous migrations)
        if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
            return $raw;
        }

        // Strip leading slash then strip the "storage/" prefix if present
        // so Storage::url() always gets a relative path like "products/file.jpg"
        $path = ltrim($raw, '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return \Illuminate\Support\Facades\Storage::url($path);
    }
}
