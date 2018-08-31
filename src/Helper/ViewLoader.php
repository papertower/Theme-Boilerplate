<?php

namespace Theme\Helper;

/**
 * A loader helper for easily loading files within the view directory
 */
class ViewLoader
{
    /**
     * Includes the passsed views
     *
     * @param  string ...$views View file(s) to be loaded
     */
    public static function include(...$views)
    {
        self::load($views, false, false);
    }

    /**
     * Includes once the passsed views
     *
     * @param  string ...$views View file(s) to be loaded
     */
    public static function include_once(...$views)
    {
        self::load($views, false, true);
    }

    /**
     * Requires the passsed views
     *
     * @param  string ...$views View file(s) to be loaded
     */
    public static function require(...$views)
    {
        self::load($views, true, false);
    }

    /**
     * Requires once the passsed views
     *
     * @param  string ...$views View file(s) to be loaded
     */
    public static function require_once(...$views)
    {
        self::load($views, true, true);
    }

    /**
     * Loads the passed view(s). The intention is to include view files and functions for the
     * current template.
     *
     * @param  array   $views   View(s) to be loaded
     * @param  boolean $require Whether to require or include
     * @param  boolean $once    Whether to load once or not
     */
    private static function load($views, $require, $once)
    {
        $path = get_stylesheet_directory();

        foreach ((array)$views as $view) {
            if ($require) {
                if ($once) {
                    require_once "$path/views/$view";
                } else {
                    require "$path/views/$view";
                }
            } else {
                if ($once) {
                    include_once "$path/views/$view";
                } else {
                    include "$path/views/$view";
                }
            }
        }
    }
}
