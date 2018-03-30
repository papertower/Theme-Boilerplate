<?php
/**
 * Meta Box: false
 * Post Type: page
 * Template: page-templates/sitemap
 * Context: normal
 * Priority: high
 * Order: 10
 * Flow: Sitemap
 * Tab: Posts
 */

// Description
piklist('field', array(
  'type'    => 'html',
  'label'   => 'Notice',
  'value'   => '<ul>
    <li>Only published posts will be displayed unless private option is checked.</li>
  </ul>'
));

// Posts Radio
piklist('field', array(
  'type'    => 'radio',
  'field'   => 'posts_type',
  'label'   => 'Posts',
  'list'    => false,
  'value'   => 'all',
  'choices' => array(
    'none'    => __('Do Not Display'),
    'all'     => __('Show All Posts'),
    'latest'  => __('Show Latest [field=posts-latest] Posts'),
    'range'   => __('Show After [field=posts-range][field=posts-range-unit]'),
    'select'  => __('Select Posts')
  ),
  'fields'  => array(
    array(
      'type'      => 'number',
      'field'     => 'posts-latest',
      'value'     => 5,
      'embed'     => true,
      'attributes'=> array(
        'class'     => 'small-text',
        'min'       => 1
      )
    ),
    array(
      'type'      => 'number',
      'field'     => 'posts-range',
      'value'     => 14,
      'embed'     => true,
      'attributes'=> array(
        'class'     => 'small-text',
        'min'       => 1
      )
    ),
    array(
      'type'      => 'select',
      'field'     => 'posts-range-unit',
      'value'     => 'day',
      'embed'     => true,
      'choices'   => array(
        'day'       => 'Day(s)',
        'month'     => 'Month(s)',
        'year'      => 'Year(s)'
      )
    )
  )
));

// Before/After HTML
piklist('field', array(
  'type'    => 'group',
  'field'   => 'posts-wrap-html',
  'label'   => 'Wrap Section in HTML',
  'conditions'=> array(
    array(
      'field'   => 'posts',
      'compare' => '!=',
      'value'   => 'none'
    )
  ),
  'fields'  => array(
    array(
      'type'      => 'text',
      'field'     => 'posts-wrap-before',
      'columns'   => 4,
      'attributes'=> array(
        'placeholder' => 'Before'
      )
    ),
    array(
      'type'      => 'text',
      'field'     => 'posts-wrap-after',
      'columns'   => 4,
      'attributes'=> array(
        'placeholder' => 'After'
      )
    )
  )
));

// Post Options
piklist('field', array(
  'type'      => 'checkbox',
  'field'     => 'posts_options',
  'label'     => 'Post Options',
  'value'     => 'show-private',
  'choices'   => array(
    'show-private'  => __('Show private posts if logged in')
  ),
  'conditions'=> array(
    array(
      'field'   => 'posts_type',
      'compare' => '!=',
      'value'   => 'none'
    )
  )
));

// Post Selections
$posts = get_posts(array(
  'post_type'   => 'post',
  'numberposts' => -1,
  'post_status' => array('publish', 'private'),
));

$post_choices = array();
foreach($posts as $post)
  $post_choices[$post->ID] = $post->post_title;

asort($post_choices);

piklist('field', array(
  'type'      => 'select',
  'field'     => 'selected_posts',
  'label'     => 'Include:',
  'choices'   => $post_choices,
  'attributes'=> array(
    'class'     => 'select2',
    'multiple'  => 'multiple'
  ),
  'conditions'=> array(
    array(
      'field'   => 'posts_type',
      'value'   => 'select'
    )
  )
));
