<?php

namespace Theme;

/**
 * Handle the view structure for the theme. Rather than everything being in the root structure of
 * the theme, the templates are broken down by section, post type, and taxonomy.
 *
 * Unfortunately, WordPress does not support changing where the header and footer files are when
 * get_footer() and get_header() are used. To load the header and footer for this theme use
 * do_action('get_header') and do_action('get_footer') in the place of the corresponding functions.
 */
class TemplateLoader implements Registerable {

  /**
   * Register the hooks for handling the templates, header, and footer
   */
  public function register() {
    $template_types = [
      'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
      'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment'
    ];

    foreach($template_types as $type) {
      add_filter("{$type}_template_hierarchy", [$this, 'get_view_templates']);
    }

    add_action('get_footer', [$this, 'load_footer']);
    add_action('get_header', [$this, 'load_header']);
  }

  /**
   * Includes the view footer file
   * @param  string $name Name of a specific footer file
   */
  public function load_footer($name) {
    $file = $name
      ? STYLESHEETPATH . "/views/Base/footer-$name.php"
      : STYLESHEETPATH . "/views/Base/footer.php";

    if ( file_exists($file) ) {
      include $file;
    }
  }

  /**
   * Includes the view header file
   * @param  string $name Name of a specific header file
   */
  public function load_header($name) {
    $file = $name
      ? STYLESHEETPATH . "/views/Base/header-$name.php"
      : STYLESHEETPATH . "/views/Base/header.php";

    if ( file_exists($file) ) {
      include $file;
    }
  }

  /**
   * Adds the view template locations based on the type of request happening. The existing template
   * locations will continue to work, the view locations are simply prioritized.
   * @param  array $templates Default templates
   * @return array            Templates with view locations added
   */
  public function get_view_templates($templates) {
    global $wp_query;

    $sep = DIRECTORY_SEPARATOR;

    $additional_templates = [];

    switch(true) {
      case is_page_template():
        return $templates;

      case $wp_query->is_singular():
      case $wp_query->is_page():
      case $wp_query->is_attachment():
        $post = get_queried_object();
        $directory = "PostType{$sep}{$post->post_type}{$sep}";
        break;

      case $wp_query->is_post_type_archive():
      case $wp_query->is_date():
      case $wp_query->is_archive() && !$wp_query->is_tax():
	       $post_types = array_filter( (array) get_query_var( 'post_type' ) );
         $directory = 1 === count($post_types)
          ? "PostType{$sep}{$post_types[0]}{$sep}" : '';
          break;

      case $wp_query->is_tax():
      case $wp_query->is_category():
      case $wp_query->is_tag():
        $term = get_queried_object();
        $directory = !empty($term->slug) ? "Taxonomy{$sep}{$term->taxonomy}{$sep}" : '';
        break;

      case $wp_query->is_home():
        $directory = "PostType{$sep}post{$sep}";
        break;

      default:
        $directory = "Base{$sep}";
    }

    foreach((array) $templates as $template) {
      $additional_templates[] = 'views' . DIRECTORY_SEPARATOR . $directory . $template;
    }

    return array_merge($additional_templates, $templates);
  }
}
