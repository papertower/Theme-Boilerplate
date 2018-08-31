<?php

namespace Theme;

/**
 * Creates and manages the single theme instance
 */
final class ThemeFactory
{
    /**
     * Create and return a single instance of the Theme class
     * @return Theme Single theme instance
     */
    public static function create()
    {
        static $theme = null;

        if (null === $theme) {
            $theme = new Theme();
        }

        return $theme;
    }
}
