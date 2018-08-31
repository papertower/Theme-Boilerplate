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
        // Register Post Type
        add_action('init', [$this, 'register_post_type']);

        // Setup templates
        $this->load($this->get_slug());

        // Add taxonomy filters
        add_action('restrict_manage_posts', [$this, 'add_taxonomy_filters'], 10, 2);
    }

    /**
     * Registers the post type
     */
    public function register_post_type()
    {
        register_post_type($this->get_slug(), $this->get_arguments());
    }

    /**
     * @param string         $post_type
     * @param \WP_List_Table $which
     */
    public function add_taxonomy_filters($post_type, $which)
    {
        if ($post_type !== $this->get_slug()) {
            return;
        }

        $taxonomies = get_object_taxonomies($post_type, 'objects');

        foreach ($taxonomies as $taxonomy => $object) {
            if (!isset($object->show_admin_filter)) {
                continue;
            }

            if (is_array($object->show_admin_filter) && !in_array($post_type, $object->show_admin_filter)) {
                continue;
            }

            wp_dropdown_categories([
                'show_option_all' => "All {$object->label}",
                'taxonomy'        => $taxonomy,
                'name'            => $taxonomy,
                'orderby'         => 'name',
                'selected'        => isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '',
                'hierarchical'    => $object->hierarchical,
                'depth'           => 3,
                'show_count'      => true,
                'hide_empty'      => false,
                'value_field'     => 'slug'
            ]);
        }
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
