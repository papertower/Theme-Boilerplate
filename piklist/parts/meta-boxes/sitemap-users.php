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
piklist('field', [
    'type'  => 'html',
    'label' => 'Notice',
    'value' => '<ul>
    <li>Users are limited to those thave have published posts</li>
  </ul>'
]);

// Author Radio
piklist('field', [
    'type'    => 'radio',
    'field'   => 'authors_type',
    'label'   => 'Authors',
    'list'    => false,
    'value'   => 'all',
    'choices' => [
        'none'   => __('Do Not Display'),
        'all'    => __('Show All Authors'),
        'select' => __('Select Authors')
    ]
]);

// Author Selections
$authors        = User::get_authors('post');
$author_choices = [];

foreach ($authors as $author) {
    $author_choices[$author->id] = $author->display_name;
}

asort($author_choices);

piklist('field', [
    'type'       => 'select',
    'field'      => 'selected_authors',
    'label'      => 'Include:',
    'choices'    => $author_choices,
    'attributes' => [
        'class'    => 'select2',
        'multiple' => 'multiple'
    ],
    'conditions' => [
        [
            'field' => 'authors_type',
            'value' => 'select'
        ]
    ]
]);
