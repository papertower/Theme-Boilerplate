<?php

function get_the_page_navigation($echo = false) {
  global $wp_query;

  $bignum = 999999999;
  if ( $wp_query->max_num_pages <= 1 )
    return;

  $output = '<nav class="pagination">';

  $output .= paginate_links( array(
    'base'         => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
    'format'       => '',
    'current'      => max( 1, get_query_var('paged') ),
    'total'        => $wp_query->max_num_pages,
    'prev_text'    => '&larr;',
    'next_text'    => '&rarr;',
    'type'         => 'list',
    'end_size'     => 3,
    'mid_size'     => 3
  ) );

  $output .= '</nav>';

  if ( $echo ) {
    echo $output;
  } else {
    return $output;
  }
}
