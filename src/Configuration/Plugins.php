<?php
namespace Theme\Configuration;

use Theme\Registerable;

/**
 * Provides configuration for 3rd party plugins.
 */
class Plugins implements Registerable {
  /**
   * Register all the hooks
   */
  public function register() {
    // WordPress SEO
    add_filter('wpseo_metabox_prio', [$this, 'set_wordpress_seo_metabox_priority']);

    // WP Controllers
    add_action('admin_notices', [$this, 'display_wp_controllers_notice']);
  }

  /**
   * By default the WordPress SEO metabox is high priority, placing it above custom metaboxes. This
   * is annoying so we shoot it to the bottom.
   */
  public function set_wordpress_seo_metabox_priority() {
    return 'low';
  }

  /**
   * Since the theme depends on the WP Controllers plugin to be active, display a notice if they're
   * not to guide the user to activate them again.
   */
  public function display_wp_controllers_notice() {
    if( !is_plugin_active( 'wp-controllers/wp-controllers.php' )) { ?>
      <div class="notice notice-error is-dismissible">
        <h3><?php _e( 'WP Controllers Plugin is required. Please <a href="' . admin_url( 'plugins.php' ) . '"">go to plugin page </a>and activate now!' , 'bonestheme' ) ?></h3>
      </div>
    <?php }
  }
}
