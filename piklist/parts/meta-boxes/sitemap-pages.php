<?php
/**
 * Meta Box: false
 * Post Type: page
 * Template: page-templates/sitemap
 * Context: normal
 * Priority: high
 * Order: 10
 * Flow: Sitemap
 * Tab: Pages
 */

// Description
piklist('field', [
    'type'  => 'html',
    'label' => 'Notice',
    'value' => '<ul>
    <li>Only published posts will be displayed unless private option is checked.</li>
  </ul>'
]);

// Pages Radio
piklist('field', [
    'type'    => 'radio',
    'field'   => 'pages_type',
    'label'   => 'Pages',
    'list'    => false,
    'value'   => 'all',
    'choices' => [
        'none'   => __('Do Not Display'),
        'all'    => __('Show All Pages'),
        'select' => __('Select Pages')
    ]
]);

// Before/After HTML
piklist('field', [
    'type'       => 'group',
    'field'      => 'pages-wrap-html',
    'label'      => 'Wrap Section in HTML',
    'conditions' => [
        [
            'field' => 'pages',
            'value' => ['all', 'select']
        ]
    ],
    'fields'     => [
        [
            'type'       => 'text',
            'field'      => 'pages-wrap-before',
            'columns'    => 4,
            'attributes' => [
                'placeholder' => 'Before'
            ]
        ],
        [
            'type'       => 'text',
            'field'      => 'pages-wrap-after',
            'columns'    => 4,
            'attributes' => [
                'placeholder' => 'After'
            ]
        ]
    ]
]);

// Page Options
piklist('field', [
    'type'       => 'checkbox',
    'field'      => 'page_options',
    'label'      => 'Page Options',
    'value'      => ['show-private', 'nest-pages'],
    'choices'    => [
        'show-private' => __('Show private pages if logged in'),
        'nest-pages'   => __('Nest lists to reflect heirarchy')
    ],
    'conditions' => [
        [
            'field' => 'pages_type',
            'value' => ['all', 'select']
        ]
    ]
]);

// Page Selections
$pages = get_posts([
    'post_type'   => 'page',
    'numberposts' => -1,
    'post_status' => ['publish', 'private'],
]);

$page_choices = [];
foreach ($pages as $page) {
    $page_choices[$page->ID] = sitemap_page_title($page);
}

asort($page_choices);

piklist('field', [
    'type'       => 'select',
    'field'      => 'selected_pages',
    'label'      => 'Include:',
    'choices'    => $page_choices,
    'attributes' => [
        'class'    => 'select2',
        'multiple' => 'multiple'
    ],
    'conditions' => [
        [
            'field' => 'pages_type',
            'value' => 'select'
        ]
    ]
]);

function sitemap_page_title($page, $name = '')
{
    $name = ($name) ? $name : $page->post_title;
    if ( ! $page->post_parent) {
        return $name;
    }

    $parent = get_post($page->post_parent);
    $name   = "$parent->post_title > $name";

    return sitemap_page_title($parent, $name);
}
