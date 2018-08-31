<?php

namespace Theme\Taxonomy;

use Theme\Registerable;

/**
 * Abstract class for registering child taxonomy classes.
 */
abstract class BaseTaxonomy implements Registerable
{
    /**
     * Hook into init to register the taxonomy
     */
    public function register()
    {
        add_action('init', [$this, 'register_taxonomy']);
    }

    /**
     * Registers the taxonomy with WordPress
     */
    public function register_taxonomy()
    {
        register_taxonomy(
            $this->get_slug(),
            $this->get_post_types(),
            $this->get_arguments()
        );
    }

    /**
     * Returns the slug
     * @return string
     */
    abstract public function get_slug();

    /**
     * Returns the associated post type(s)
     * @return array|string
     */
    abstract public function get_post_types();

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
