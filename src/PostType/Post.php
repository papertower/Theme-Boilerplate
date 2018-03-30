<?php

namespace Theme\PostType;

use Theme\Registerable;

use function \remove_post_type_support;

/**
 * Extension class for the default post post type. This is used for changing the labels, REST API
 * controller, adding support for the custom view templates, and anything else relevent.
 */
class Post implements Registerable {
  use TemplateLoader;

  const SLUG = 'post';

  /**
   * Hook into appropriate WordPress actions and filter
   */
  public function register() {
    add_filter('post_type_labels_post', [$this, 'get_labels']);
    add_action('admin_menu', [$this, 'admin_menu']);

    add_filter('init', [$this, 'modify_post_supports']);

    // Optional hooks, uncomment if relevant
    // add_action('init', [$this, 'set_controller_class'], 100);

    $this->load(self::SLUG);
  }

  /**
   * Get override labels for the default post type
   * @return array Post type labels
   */
  public function get_labels() {
    return [
      'name'              => 'Posts',
      'singular_name'     => 'Post',
      'add_new'           => 'Add Post',
      'add_new_item'      => 'Add New Post',
      'edit_item'         => 'Edit Post',
      'new_item'          => 'New Post',
      'all_items'         => 'All Posts',
      'view_item'         => 'View Post',
      'search_items'      => 'Search Posts',
      'not_found'         => 'No Posts Found',
      'not_found_in_trash'=> 'No Posts found in Trash',
      'parent_item_colon' => '',
      'menu_name'         => 'Blog Posts',
      'name_admin_bar'    => 'Blog Posts'
    ];
  }

  /**
   * Overrides the default post type labels in the admin menu
   */
  public function admin_menu() {
    global $menu, $submenu;
    $labels = $this->get_labels();

    foreach($menu as &$item) {
      if ( isset($item[0]) && 'Posts' === $item[0] ) {
        $item[0] = $labels['name'];
        break;
      }
    }

  	$submenu['edit.php'][5][0] = $labels['all_items'];
  	$submenu['edit.php'][10][0] = $labels['add_new'];
  }

  /**
   * Modifies the what the post type supports
   */
  public function modify_post_supports() {
    remove_post_type_support(self::SLUG, 'custom-fields');
  }

  public function set_rest_controller_class() {
  	global $wp_post_types;

  	if ( isset( $wp_post_types['post'] ) ) {
  		$wp_post_types['post']->rest_controller_class = 'Theme\\API\\Post';
  	}
  }
}
