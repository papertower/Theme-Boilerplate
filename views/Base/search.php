<?php get_header(); ?>

<?php
$the_posts = function() {
  $posts = get_post_controllers();

  $list = '';
  foreach($posts as $post) {
    $type = get_post_type_object($post->type);

    $list .= "
      <article>
        <h2>{$type->labels->singular_name}: <a href='{$post->url()}'>{$post->title()}</a></h2>
        <p>{$post->excerpt(60)}</p>
      </article>
    ";
  }

  return $list;
};
?>

<?php
global $wp_query;

$search = get_search_query();

if ( $wp_query->found_posts ) {
  $current_page = $wp_query->get('paged');
  if ( !$current_page ) $current_page = 1;

  $start_num = ( ( $current_page - 1 ) * $wp_query->post_count ) + 1;
  $end_num = ( $current_page * $wp_query->post_count );

  $results_text = "Showing <strong>$start_num-$end_num</strong> of <strong>{$wp_query->found_posts}</strong> Results for <em>$search</em>";
} else {
  $results_text = "Sorry! There are no results for <em>$search</em>. Perhaps it's mispelled. Please try another search.";
}

\Theme\Helper\ViewLoader::include('page_navigation');

$pagination = get_the_page_navigation();

echo <<<HTML
<div class="lighttan search-results">
  <div class="row">
    <div class="small-12 columns">
      <h1>Search Query</h1>
      <span class="results">$results_text</span>
      {$the_posts()}

      $pagination
    </div>
  </div>
</div>
HTML;
?>

<?php get_footer(); ?>