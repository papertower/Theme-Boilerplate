<?php

namespace Theme\Assets;

/**
 * Handles all of the scripts and styles for the front-end of the site. To add a new style or
 * script, simply add it to the enqueue method below.
 *
 * BaseAssets provide shorthand methods for enqueue_script and enqueue_style. It assumes the asset
 * location to be in the dist directory and also adds automatic versioning.
 */
class FrontEnd extends BaseAssets
{
    const FRONT_SCRIPTS = true;

    /**
     * Runs at the time when enqueueing should happen for the front-end
     */
    public function enqueue()
    {
        // Load on every page
        wp_enqueue_script('modernizr', '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', [], '2.8.3',
            false);
        wp_enqueue_script('jquery');

        $this->enqueue_style('theme-stlyesheet', 'style.css');

        // Load in specific conditions
        switch (true) {
            default:
                $this->enqueue_script('scripts', 'front-scripts.js', ['jquery']);
        }

        // Polyfill for responsiveness on browsers older than IE 9
        if (preg_match('/(?i)msie [2-8]/', $_SERVER['HTTP_USER_AGENT'])) {
            wp_enqueue_script('respond', '//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js', [''], '',
                true);
        }
    }
}
