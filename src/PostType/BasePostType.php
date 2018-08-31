<?php

namespace Theme\PostType;

use Theme\Registerable;

/**
 * Abstract class for registering child post type classes and loading the custom templates.
 */
abstract class BasePostType implements Registerable
{
    use TemplateLoader;

    /**
     * Hook into init to register the post type, then load the template loader
     */
    public function register()
    {
        add_action('init', [$this, 'register_post_type']);
        $this->load($this->get_slug());
    }

    /**
     * Registers the post type
     */
    public function register_post_type()
    {
        register_post_type($this->get_slug(), $this->get_arguments());
    }

    /**
     * Returns the slug
     * @return string
     */
    abstract public function get_slug();

    /**
     * Returns the labels
     * @return array
     */
    abstract public function get_labels();

    /**
     * Returns the registration arguments
     * @return array
     */
    abstract public function get_arguments();
}
