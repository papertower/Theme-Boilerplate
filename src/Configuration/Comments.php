<?php

namespace Theme\Configuration;

use Theme\Registerable;

/**
 * This completely whipes all comment functionality from WordPress in terms of post types. If
 * the theme ought to use comments, then set DISABLE_COMMENTS to false.
 */
class Comments implements Registerable {
  const DISABLE_COMMENTS = true;

  /**
   * Hooks into the various places of WordPress to remove comments
   */
  public function register() {
    if ( !self::DISABLE_COMMENTS ) return;

    add_filter('init', [$this, 'remove_from_admin_bar']);
    add_filter('admin_init', [$this, 'disable_admin_comments']);
    add_filter('admin_menu', [$this, 'remove_from_admin_menu']);
    add_filter('comments_array', [$this, 'empty_comments_array']);
    add_filter('comments_open', '__return_false');
    add_filter('pings_open', '__return_false');
  }

  /**
   * Removes the comments menu from the admin bar
   */
  public function remove_from_admin_bar() {
  	if (is_admin_bar_showing()) {
  		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
  	}
  }

  /**
   * This redirects users away from the comments page (if they somehow got there), disables comments
   * and trackbacks in the post types, and removes the comments metabox from the dashboard.
   */
  public function disable_admin_comments() {
    // Redirect any user trying to access comments page
  	global $pagenow;
    if ( in_array($pagenow, array('options-discussion.php', 'edit-comments.php'))) {
  		wp_redirect(admin_url()); exit;
  	}

    // Disable support for comments and trackbacks in post types
  	$post_types = get_post_types();
  	foreach ($post_types as $post_type) {
  		if(post_type_supports($post_type, 'comments')) {
  			remove_post_type_support($post_type, 'comments');
  			remove_post_type_support($post_type, 'trackbacks');
        remove_meta_box('commentsdiv', $post_type, 'normal');
        remove_meta_box('commentsstatusdiv', $post_type, 'normal');
        remove_meta_box('trackbacksdiv', $post_type, 'normal');
  		}
  	}

    // Remove dashboard meta box
  	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
  }

  /**
   * Removes the comment pages from the admin side-menu
   */
  public function remove_from_admin_menu() {
  	remove_menu_page('edit-comments.php');
    remove_submenu_page('options-general.php', 'options-discussion.php');
  }

  /**
   * Returns an empty array so comments queried for the post is always empty
   * @param  array $comments Post comments
   * @return array           Empty array for comments
   */
  public function empty_comments_array($comments) {
    return array();
  }
}
