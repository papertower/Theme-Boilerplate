<?php

namespace Theme\Helper;

class Utilities
{
    /**
     * Returns the echoed content of a function
     * @link http://php.net/manual/en/language.types.callable.php
     *
     * @param callable $function
     * @param array    $arguments (optional)
     *
     * @returns string Output of function
     */
    public static function get_function_output($function, $arguments = [])
    {
        $contents = '';
        ob_start();
        call_user_func_array($function, $arguments);
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    /**
     * Returns the contents of the get_template_part wordpress function
     * @link http://codex.wordpress.org/Function_Reference/get_template_part
     *
     * @param string $slug
     * @param string $name
     *
     * @returns string
     */
    public static function get_the_template_part($slug, $name)
    {
        return self::get_function_output('get_template_part', [$slug, $name]);
    }

    /**
     * Makes sure all the line endings in a string are line feeds.
     *
     * @param string $text text to be changed
     *
     * @return string text with new endings
     */
    public static function normalize_line_endings($text)
    {
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\r", "\n", $text);

        return $text;
    }

    /**
     *    Inspects and returns a url with a server (http/ftp/etc)
     *    prefix and returns the url with the prefix. If $replace
     *    is true and the prefix is different, it will re replaced,
     *    otherwise the original url will be returned.
     *
     * @param string  $url
     * @param string  $url
     * @param boolean $replace
     *
     * @return string
     */
    public static function apply_url_prefix($url, $prefix = 'http://', $replace = false)
    {
        $pattern   = "/(^(?:f|ht)tps?:\/\/)([\w.\/\?=&-]+)/i";
        $has_match = preg_match($pattern, $url, $matches);

        if ($has_match) {
            if (($matches[1] == $prefix) || ! $replace) {
                return $url;
            } else {
                return $prefix . $matches[2];
            }
        } else {
            return $prefix . $url;
        }
    }

    /**
     * Returns the amount of time it took to run a function over
     * a variable number of iterations
     *
     * @param callable $function
     * @param array    $args
     * @param array    $options List of options:
     *                          integer Interations
     *                          string unit 'micro'|'milli'|'' -- empty is seconds
     *                          string type 'total'|'average' -- how it will be calculated
     *
     * @return float
     */
    public static function get_function_time($function, $args, $options)
    {
        extract(wp_parse_args($options, [
            'iterations' => 1,
            'unit'       => 'micro',
            'type'       => 'total',
        ]));

        if ($iterations > 1) {
            $time_start = microtime();
            for ($count = 1; $count <= $iterations; $count++) {
                call_user_func_array($function, $args);
            }
            $time = microtime() - $time_start;

            if ($type == 'average') {
                $time = $time / $iterations;
            }

        } else {
            $time_start = microtime();
            call_user_func_array($function, $args);
            $time = microtime() - $time_start;
        }


        switch ($unit) {
            case 'micro': // microseconds
                return $time * 1000000;

            case 'milli': // milliseconds
                return $time * 1000;

            default: // seconds
                return $time;
        }
    }

    /**
     * Generate date archive rewrite rules for a given custom post type
     *
     * @param  string $cpt slug of the custom post type
     *
     * @return rules              returns a set of rewrite rules for Wordpress to handle
     */
    public static function generate_date_archives($cpt, $wp_rewrite)
    {
        $rules = [];

        $post_type    = get_post_type_object($cpt);
        $slug_archive = $post_type->has_archive;
        if ($slug_archive === false) {
            return $rules;
        }
        if ($slug_archive === true) {
            $slug_archive = ($post_type->rewrite['slug']) ? $post_type->rewrite['slug'] : $post_type->name;
        }

        $dates = [
            [
                'rule' => "([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})",
                'vars' => ['year', 'monthnum', 'day']
            ],
            [
                'rule' => "([0-9]{4})/([0-9]{1,2})",
                'vars' => ['year', 'monthnum']
            ],
            [
                'rule' => "([0-9]{4})",
                'vars' => ['year']
            ]
        ];

        foreach ($dates as $data) {
            $query = 'index.php?post_type=' . $cpt;
            $rule  = $slug_archive . '/' . $data['rule'];

            $i = 1;
            foreach ($data['vars'] as $var) {
                $query .= '&' . $var . '=' . $wp_rewrite->preg_index($i);
                $i++;
            }

            $rules[$rule . "/?$"]                               = $query;
            $rules[$rule . "/feed/(feed|rdf|rss|rss2|atom)/?$"] = $query . "&feed=" . $wp_rewrite->preg_index($i);
            $rules[$rule . "/(feed|rdf|rss|rss2|atom)/?$"]      = $query . "&feed=" . $wp_rewrite->preg_index($i);
            $rules[$rule . "/page/([0-9]{1,})/?$"]              = $query . "&paged=" . $wp_rewrite->preg_index($i);
        }

        return $rules;
    }

    /**
     * Returns an alphabetically sorted array of all the hooks currently listed
     * @return array
     */
    public static function hooks()
    {
        global $wp_filter;

        $hooks = $wp_filter;
        ksort($hooks);

        return $hooks;
    }

    /**
     * Includes all the php files within a given directory
     *
     * @param string  $directory_path absolute path to the directory to pull from, end with /
     * @param boolean $recursive      whether or not to dig into directories found
     * @param boolean $once           whether to use include or include_once
     */
    public static function include_directory($directory_path, $recursive = false, $once = true)
    {
        // Make sure provided directory is a directory
        if ( ! is_dir($directory_path)) {
            throw new Exception("$directory_path is not a valid directory");
        }

        // Make sure an asterisk is the last character for glob
        $directory_path .= ('*' === substr($directory_path, -1)) ? '' : '*';

        // Retrieve contents and throw error if empty or error
        $contents = glob($directory_path);
        if (false === $contents) {
            throw new Exception("error loading contents for $directory_path");
        }

        // Parse through files and include
        foreach ($contents as $file) {
            if (is_dir($file)) {
                // include directory files if recursive
                if ($recursive) {
                    self::include_directory("$file/", $recursive, $once);
                }
            } else {
                // Include file only if php
                if (strcasecmp('php', substr(strrchr($file, '.'), 1)) === 0) {
                    if ($once) {
                        include_once($file);
                    } else {
                        include($file);
                    }
                }
            }
        }
    }

    public static function remove_anonymous_filter($tag, $function, $priority = 10)
    {
        $filters = $GLOBALS['wp_filter'][$tag];
        if (empty($filters)) {
            return;
        }

        $filter = $filters[$priority];
        if (empty($filter)) {
            return;
        }

        foreach ($filter as $identifier => $callable) {
            if ($function === $callable) {
                remove_filter($tag, $callable, $priority);

                return;
            }
        }
    }

    public static function remove_anonymous_action($tag, $function, $priority = 10)
    {
        self::remove_anonymous_filter($tag, $function, $priority);
    }
}
