<?php

namespace Theme\Configuration;

use Theme\Registerable;

/**
 * Manages the menus for the theme
 */
class Menu implements Registerable {
  /**
   * Register all the hooks and functions
   */
  public function register() {
    add_action('after_setup_theme', [$this, 'register_navigation_menus']);
  }

  public function register_navigation_menus() {
  	register_nav_menus(
  		array(
  			'main-nav' => __( 'The Main Menu', 'theme' ),   // main nav in header
  			'footer-links' => __( 'Footer Links', 'theme' ) // secondary nav in footer
  		)
  	);
  }
}
