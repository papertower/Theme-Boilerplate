<?php
/*
Title: Users
Post Type: page
Template: page-templates/sitemap
Context: normal
Priority: high
Order: 5
Collapse: true
*/

// Description
piklist('field', array(
  'type'    => 'html',
  'label'   => 'Notice',
  'value'   => '<ul>
    <li>Users are limited to those thave have published posts</li>
  </ul>'
));

// Author Radio
piklist('field', array(
  'type'    => 'radio',
  'field'   => 'authors_type',
  'label'   => 'Authors',
  'list'    => false,
  'value'   => 'all',
  'choices' => array(
    'none'    => __('Do Not Display'),
    'all'     => __('Show All Authors'),
    'select'  => __('Select Authors')
  )
));

// Author Selections
$authors = User::get_authors('post');
$author_choices = array();

foreach($authors as $author)
  $author_choices[$author->id] = $author->display_name;

asort($author_choices);

piklist('field', array(
  'type'      => 'select',
  'field'     => 'selected_authors',
  'label'     => 'Include:',
  'choices'   => $author_choices,
  'attributes'=> array(
    'class'     => 'select2',
    'multiple'  => 'multiple'
  ),
  'conditions'=> array(
    array(
      'field'   => 'authors_type',
      'value'   => 'select'
    )
  )
));
