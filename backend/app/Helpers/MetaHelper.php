<?php

namespace App\Helpers;

class MetaHelper
{
    /**
     * Generate meta title with site name suffix
     */
    public static function title(?string $title = null, bool $includeSiteName = true): string
    {
        $siteName = config('app.name', 'DealMindanao');
        
        if (!$title) {
            return $siteName;
        }
        
        return $includeSiteName ? "{$title} | {$siteName}" : $title;
    }

    /**
     * Truncate description to optimal length for meta tags
     */
    public static function description(?string $description = null, int $maxLength = 160): string
    {
        $default = 'Discover quality hardware and equipment from trusted local sellers across Mindanao. Order online, pay offline.';
        
        if (!$description) {
            return $default;
        }
        
        if (strlen($description) <= $maxLength) {
            return $description;
        }
        
        return substr($description, 0, $maxLength - 3) . '...';
    }

    /**
     * Generate keywords from text or array
     */
    public static function keywords($keywords = null): string
    {
        if (is_array($keywords)) {
            return implode(', ', $keywords);
        }
        
        if (is_string($keywords)) {
            return $keywords;
        }
        
        return 'Mindanao, hardware, equipment, local sellers, deals, Philippines';
    }

    /**
     * Generate Open Graph image URL
     */
    public static function ogImage(?string $image = null): string
    {
        if ($image) {
            // If it's already a full URL, return it
            if (str_starts_with($image, 'http')) {
                return $image;
            }
            
            // Otherwise, make it absolute
            return url($image);
        }
        
        // Default OG image
        return url('/logo_main-final.png');
    }

    /**
     * Sanitize text for meta tags (remove HTML, trim)
     */
    public static function sanitize(?string $text = null): string
    {
        if (!$text) {
            return '';
        }
        
        // Strip HTML tags
        $text = strip_tags($text);
        
        // Replace multiple spaces with single space
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Trim whitespace
        return trim($text);
    }

    /**
     * Generate structured data (JSON-LD) for products
     */
    public static function productStructuredData($product): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => self::sanitize($product->description),
            'image' => !empty($product->images) ? url($product->images[0]) : self::ogImage(),
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->price - ($product->discount ?? 0),
                'priceCurrency' => 'PHP',
                'availability' => $product->stock_quantity > 0 
                    ? 'https://schema.org/InStock' 
                    : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => $product->company->name ?? 'DealMindanao',
                ],
            ],
        ];

        if ($product->category) {
            $data['category'] = $product->category->name;
        }

        if ($product->sku) {
            $data['sku'] = $product->sku;
        }

        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Generate breadcrumb structured data (JSON-LD)
     */
    public static function breadcrumbStructuredData(array $items): string
    {
        $itemList = [];
        
        foreach ($items as $index => $item) {
            $itemList[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => url($item['url'] ?? ''),
            ];
        }

        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemList,
        ];

        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
