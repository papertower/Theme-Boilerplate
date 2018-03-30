<?php

namespace Theme\PostType;

use Theme\Registerable;

use function \remove_post_type_support;

/**
 * Extension class for the default page post type. This is used for adding support for the custom
 * view templates and anything else relevent.
 */
class Page implements Registerable {
  use TemplateLoader;

  const SLUG = 'page';

  /**
   * Hook into appropriate WordPress actions and filter
   */
  public function register() {
    $this->load(self::SLUG);

    add_filter('init', [$this, 'modify_post_supports']);
  }

  /**
   * Modifies the what the post type supports
   */
  public function modify_post_supports() {
    remove_post_type_support(self::SLUG, 'custom-fields');
  }
}
