<?php

namespace App\Helpers;

class AssetHelper
{
    /**
     * Get TailAdmin asset URL
     *
     * @param string $path Path relative to public/assets/ (e.g., 'images/brand/logo.png' or 'js/components/chart.js')
     * @return string Full asset URL
     */
    public static function asset($path)
    {
        return asset("assets/{$path}");
    }

    /**
     * Get TailAdmin image URL
     *
     * @param string $image Image filename or path (e.g., 'brand/logo.png' or 'icons/bell.svg')
     * @return string Full image URL
     */
    public static function image($image)
    {
        return asset("assets/images/{$image}");
    }

    /**
     * Get TailAdmin component path
     *
     * @param string $component Component name (e.g., 'card', 'button', 'header')
     * @return string Path to component
     */
    public static function component($component)
    {
        return "assets/js/components/{$component}";
    }

    /**
     * Get TailAdmin icon URL
     *
     * @param string $icon Icon name (e.g., 'bell', 'user', 'settings')
     * @return string Full icon URL
     */
    public static function icon($icon)
    {
        return asset("assets/images/icons/{$icon}.svg");
    }
}
