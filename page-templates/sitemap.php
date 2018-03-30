<?php
/**
 * Template Name: Sitemap
 * WP Controller: SiteMap
 */
?>

<?php get_header(); ?>

<?php
  $Page = get_post_controller();

  $authors_section  = get_the_sitemap_authors($Page->author_type, $Page->authors);
  $pages_section    = get_the_sitemap_pages($Page);
  $posts_section    = get_the_sitemap_posts($Page);
  $custom_sections  = get_the_sitemap_custom_sections($Page);

  echo <<<HTML
  <div id="content" class="page wrap">
    <div id="inner-content">
		  <div class="row high-padding gray text-align-left cf">
        <h1>$Page->title</h1>
        $authors_section
        $pages_section
        $posts_section
        $custom_sections
      </div>
    </div>
  </div>
HTML;
?>

<?php get_footer(); ?>

<?php // View Functions

function get_the_sitemap_custom_sections($Page) {
  $menus = $Page->custom_sections;
  if ( empty($menus) ) return '';

  $sections = '';
  foreach($menus as $index => $menu_id) {
    $menu = wp_get_nav_menu_object($menu_id);
    $items = wp_get_nav_menu_items($menu_id);

    $list = '';
    foreach($items as $item)
      $list .= "<li><a href='$item->url'>$item->title</a></li>";

    $sections .= "<h2 id='$menu->slug'>$menu->name</h2><ul>$list</ul>";
  }

  return $sections;
}

function get_the_sitemap_authors($type, $authors) {
  if ( $type == 'none' ) return '';

  if ( $type == 'all' ) $authors = User::get_authors('post');

  if ( empty($authors) ) return '';

  $list = '';
  foreach($authors as $author) {
    $list .= "<li><a href='{$author->posts_url()}'>$author->display_name</a></li>";
  }

  if ( !$list ) return '';

  return "
    <h2 id='authors'>Authors</h2>
    <ul>$list</ul>
  ";
}

function get_the_sitemap_pages($Page) {
  $type     = $Page->pages_type;
  $pages    = $Page->pages;
  $options  = $Page->pages_options;
  $wrap_html= $Page->pages_wrap_html;

  if ( $type == 'none' ) return '';

  $show_private = ( in_array('show-private', $options) && is_user_logged_in() );
  $nest_pages = in_array('nest-pages', $options);

  if ( $type == 'all' ) {
    $args = array(
      'post_status' => ( $show_private ) ? 'publish,private' : 'publish'
    );

    $pages = ( $nest_pages ) ? Page::get_base_pages($args) : Page::get_pages($args);
  }

  $list = get_the_sitemap_list($pages, $show_private, $nest_pages);

  if ( !$list ) return '';

  return "
    {$wrap_html['pages-wrap-before'][0]}
    <h2 id='pages'>Pages</h2>
    $list
    {$wrap_html['pages-wrap-after'][0]}
  ";
}

function get_the_sitemap_posts($Page) {
  $type     = $Page->posts_type;
  $posts    = $Page->posts;
  $options  = $Page->posts_options;

  if ( $type == 'none' ) return '';

  $show_private = ( in_array('show-private', $options) && is_user_logged_in() );
  $status = ( $show_private ) ? array('publish','private') : 'publish';

  switch($type) {
    case 'all':
      $posts = PostController::get_posts(array(
        'post_type'   => 'post',
        'numberposts' => -1,
        'post_status' => $status,
        'orderby'     => 'date'
      ));
      break;
    case 'latest':
      $posts = PostController::get_posts(array(
        'post_type'   => 'post',
        'numberposts' => $Page->posts_latest,
        'post_status' => $status,
        'orderby'     => 'date'
      ));
      break;
    case 'range':
      $posts = PostController::get_posts(array(
        'post_type'   => 'post',
        'numberposts' => -1,
        'post_status' => $status,
        'orderby'     => 'date',
        'date_query'  => array(
          'after'       => $Page->posts_after_date
        )
      ));
      break;
  }

  $list = get_the_sitemap_list($posts, $show_private);

  if ( !$list ) return '';

  return "
    <h2 id='posts'>Posts</h2>
    $list
  ";
}

function get_the_sitemap_list($posts, $show_private = false, $recursive = false) {
  $list = '';
  foreach($posts as $post) {
    if ( !$show_private && $post->status == 'private' )
      continue;

    $child_list = ( $recursive && !empty($post->children()) ) ? get_the_sitemap_list($post->children(), $show_private, true) : '';

    $list .= "<li><a href='{$post->url}'>$post->title</a>$child_list</li>";
  }

  if ( !$list ) return '';

  return "<ul>$list</ul>";
}

?>
