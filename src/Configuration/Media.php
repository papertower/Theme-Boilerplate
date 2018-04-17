<?php
namespace Theme\Configuration;

use Theme\Registerable;

/**
 * Provides configuration for the WordPress media. The most common use is to add image sizes which
 * can easily be added to the add_image_sizes method.
 */
class Media implements Registerable {
  /**
   * Register all the hooks and functions
   */
  public function register() {
    // Media Dialog
    add_filter('media_view_settings', [$this, 'set_link_media_files_by_default']);

    // Set default thumbnail size
    add_action('after_setup_theme', [$this, 'set_default_thumbnail_size']);

    // Image Sizes
    $this->add_image_sizes();

    // oEmbed
    $this->set_oembed_content_width();
  }

  /**
   * Place all image size additions here in a single, lovely place.
   */
  private function add_image_sizes() {
    add_image_size( 'thumb-600', 600, 150, true );
    add_image_size( 'thumb-300', 300, 100, true );
  }

  /**
   * The $content_width global variable is used by WordPress for oEmbeds. Use this to set the width
   * either statically or conditionallly based on the theme's needs.
   */
  private function set_oembed_content_width() {
    global $content_width;
    $content_width = 640;
  }

  public function set_default_thumbnail_size() {
    set_post_thumbnail_size(125, 125, true);
  }

  /**
   * When linking to files via the media library, change the default to link to the file by default,
   * as opposed to linking to the attachment single page, which is rarely used.
   * @param array $settings Media settings
   */
  public function set_link_media_files_by_default($settings) {
    $settings['galleryDefaults']['link'] = 'file';
    return $settings;
  }
}
