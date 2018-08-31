<?php

namespace Theme\Taxonomy;

/**
 * An example taxonomy. Do not leave this on production. The intention is for the file to be
 * duplicated and modified to reflect intended custom taxonomies.
 */
class Example extends BaseTaxonomy
{
    const SLUG = 'example';

    /**
     * @inheritdoc
     */
    public function get_slug()
    {
        return self::SLUG;
    }

    /**
     * @inheritdoc
     */
    public function get_post_types()
    {
        return \Theme\PostType\Example::SLUG;
    }

    public function get_labels()
    {
        return [
            'name'              => 'Examples',
            'singular_name'     => 'Example',
            'search_items'      => 'Search Examples',
            'all_items'         => 'All Examples',
            'parent_item'       => 'Parent Example',
            'parent_item_colon' => 'Parent Example:',
            'edit_item'         => 'Edit Example',
            'update_item'       => 'Update Example',
            'add_new_item'      => 'Add New Example',
            'new_item_name'     => 'New Genre Example',
            'menu_name'         => 'Example',
        ];
    }

    /**
     * @inheritdoc
     */
    public function get_arguments()
    {
        return [
            'hierarchical' => true,
            'labels'       => $this->get_labels(),
            'show_ui'      => true,
            'rewrite'      => ['slug' => 'example']
        ];
    }
}
