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
