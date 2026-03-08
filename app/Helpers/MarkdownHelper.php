<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class MarkdownHelper
{
    /**
     * Render markdown content with improved handling for pasted plain text.
     * Converts single line breaks to paragraph breaks so pasted content displays correctly.
     * Converts relative /storage/ image paths to absolute URLs.
     */
    public static function toHtml(string $content): string
    {
        if (empty(trim($content))) {
            return '';
        }

        $content = self::normalizePastedContent($content);
        $html = Str::markdown($content);

        return self::resolveStorageUrls($html);
    }

    /**
     * Convert relative /storage/ paths in img src to absolute URLs.
     */
    protected static function resolveStorageUrls(string $html): string
    {
        return preg_replace_callback(
            '/<img([^>]+)src=["\']([^"\']+)["\']/',
            function ($matches) {
                $attrs = $matches[1];
                $src = $matches[2];
                if (str_starts_with($src, '/storage/')) {
                    $src = asset(ltrim($src, '/'));
                }
                return '<img' . $attrs . 'src="' . e($src) . '"';
            },
            $html
        );
    }

    /**
     * Normalize pasted content: preserve paragraph structure for plain text.
     * Single newlines become paragraph breaks so each line displays as a separate paragraph.
     */
    protected static function normalizePastedContent(string $content): string
    {
        // Normalize line endings
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        // Protect existing double newlines (paragraph breaks) with placeholder
        $content = str_replace("\n\n", "\x00PARA\x00", $content);

        // Single newlines -> paragraph breaks (for pasted text from Word, Docs, etc.)
        $content = str_replace("\n", "\n\n", $content);

        // Restore protected double newlines
        $content = str_replace("\x00PARA\x00", "\n\n", $content);

        return $content;
    }
}
