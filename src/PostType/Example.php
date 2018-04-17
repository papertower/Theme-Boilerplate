<?php

namespace Theme\PostType;

/**
 * An example post type. Do not leave this on production. The intention is for the file to be
 * duplicated and modified to reflect intended custom post types.
 */
class Example extends BasePostType {
  const SLUG = 'example';

  /**
   * @inheritdoc
   */
  public function get_slug() {
    return self::SLUG;
  }

  /**
   * @inheritdoc
   */
  public function get_labels() {
    return [
      'name'               => 'Examples',
      'singular_name'      => 'Example',
      'add_new'            => 'Add Example',
      'add_new_item'       => 'Add New Example',
      'edit_item'          => 'Edit Example',
      'new_item'           => 'New Example',
      'all_items'          => 'All Examples',
      'view_item'          => 'View Example',
      'search_items'       => 'Search Examples',
      'not_found'          => 'No Examples Found',
      'not_found_in_trash' => 'No Examples found in Trash',
      'parent_item_colon'  => '',
      'menu_name'          => 'Examples'
    ];
  }

  /**
   * @inheritdoc
   */
  public function get_arguments() {
    return [
      'labels'              => $this->get_labels(),
      'title'               => __( 'Title' ),
      'supports'            => array( 'title', 'editor', 'excerpt' ),
      'public'              => true,
      'has_archive'         => true,
      'rewrite'             => array( 'slug' => 'example' ),
      'capability_type'     => 'post',
      'hide_meta_box'       => array( 'slug' ),
      'wp_controller_class' => 'Example'
    ];
  }
}
