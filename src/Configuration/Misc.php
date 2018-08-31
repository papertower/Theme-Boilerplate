<?php

namespace Theme\Configuration;

use Theme\Registerable;

/**
 * Handles every other configuration that doesn't really fit nicely into another category. Sorry
 * for the mess. Please better organize if someone has a good idea.
 */
class Misc implements Registerable
{
    /**
     * Register all the hooks and functions
     */
    public function register()
    {
        // Cleanup the head hooks
        add_action('init', [$this, 'cleanup_head']);

        // Set a custom page title
        add_filter('wp_title', [$this, 'set_page_title'], 10, 3);

        // Remove injected css for recent comments widget
        remove_filter('wp_head', 'wp_widget_recent_comments_style');
    }

    /**
     * By default the head block has a ton of unnecessary, old hooks, or some that we'd just prefer to
     * handle manually. This cleans up a bunch of hooks.
     */
    public function cleanup_head()
    {
        // EditURI link
        remove_action('wp_head', 'rsd_link');
        // windows live writer
        remove_action('wp_head', 'wlwmanifest_link');
        // index link
        remove_action('wp_head', 'index_rel_link');
        // previous link
        remove_action('wp_head', 'parent_post_rel_link', 10, 0);
        // start link
        remove_action('wp_head', 'start_post_rel_link', 10, 0);
        // links for adjacent posts
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
        // WP version
        remove_action('wp_head', 'wp_generator');
    }

    /**
     * Sets a custom page title that's better than what WordPress generates by default.
     *
     * @param string $title       Default WordPress title
     * @param string $sep         Seperator for distinguishing pages from sections
     * @param string $seplocation Whether the title should go on the left or right of the section
     */
    public function set_page_title($title, $sep, $seplocation)
    {
        global $page, $paged;

        // Don't affect in feeds.
        if (is_feed()) {
            return $title;
        }

        // Add the blog's name
        if ('right' == $seplocation) {
            $title .= get_bloginfo('name');
        } else {
            $title = get_bloginfo('name') . $title;
        }

        // Add the blog description for the home/front page.
        $site_description = get_bloginfo('description', 'display');

        if ($site_description && (is_home() || is_front_page())) {
            $title .= " {$sep} {$site_description}";
        }

        // Add a page number if necessary:
        if ($paged >= 2 || $page >= 2) {
            $title .= " {$sep} " . sprintf(__('Page %s', 'dbt'), max($paged, $page));
        }

        return $title;
    }
}
