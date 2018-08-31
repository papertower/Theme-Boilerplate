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
piklist('field', [
    'type'  => 'html',
    'label' => 'Notice',
    'value' => '<ul>
    <li>Only published posts will be displayed unless private option is checked.</li>
  </ul>'
]);

// Posts Radio
piklist('field', [
    'type'    => 'radio',
    'field'   => 'posts_type',
    'label'   => 'Posts',
    'list'    => false,
    'value'   => 'all',
    'choices' => [
        'none'   => __('Do Not Display'),
        'all'    => __('Show All Posts'),
        'latest' => __('Show Latest [field=posts-latest] Posts'),
        'range'  => __('Show After [field=posts-range][field=posts-range-unit]'),
        'select' => __('Select Posts')
    ],
    'fields'  => [
        [
            'type'       => 'number',
            'field'      => 'posts-latest',
            'value'      => 5,
            'embed'      => true,
            'attributes' => [
                'class' => 'small-text',
                'min'   => 1
            ]
        ],
        [
            'type'       => 'number',
            'field'      => 'posts-range',
            'value'      => 14,
            'embed'      => true,
            'attributes' => [
                'class' => 'small-text',
                'min'   => 1
            ]
        ],
        [
            'type'    => 'select',
            'field'   => 'posts-range-unit',
            'value'   => 'day',
            'embed'   => true,
            'choices' => [
                'day'   => 'Day(s)',
                'month' => 'Month(s)',
                'year'  => 'Year(s)'
            ]
        ]
    ]
]);

// Before/After HTML
piklist('field', [
    'type'       => 'group',
    'field'      => 'posts-wrap-html',
    'label'      => 'Wrap Section in HTML',
    'conditions' => [
        [
            'field'   => 'posts',
            'compare' => '!=',
            'value'   => 'none'
        ]
    ],
    'fields'     => [
        [
            'type'       => 'text',
            'field'      => 'posts-wrap-before',
            'columns'    => 4,
            'attributes' => [
                'placeholder' => 'Before'
            ]
        ],
        [
            'type'       => 'text',
            'field'      => 'posts-wrap-after',
            'columns'    => 4,
            'attributes' => [
                'placeholder' => 'After'
            ]
        ]
    ]
]);

// Post Options
piklist('field', [
    'type'       => 'checkbox',
    'field'      => 'posts_options',
    'label'      => 'Post Options',
    'value'      => 'show-private',
    'choices'    => [
        'show-private' => __('Show private posts if logged in')
    ],
    'conditions' => [
        [
            'field'   => 'posts_type',
            'compare' => '!=',
            'value'   => 'none'
        ]
    ]
]);

// Post Selections
$posts = get_posts([
    'post_type'   => 'post',
    'numberposts' => -1,
    'post_status' => ['publish', 'private'],
]);

$post_choices = [];
foreach ($posts as $post) {
    $post_choices[$post->ID] = $post->post_title;
}

asort($post_choices);

piklist('field', [
    'type'       => 'select',
    'field'      => 'selected_posts',
    'label'      => 'Include:',
    'choices'    => $post_choices,
    'attributes' => [
        'class'    => 'select2',
        'multiple' => 'multiple'
    ],
    'conditions' => [
        [
            'field' => 'posts_type',
            'value' => 'select'
        ]
    ]
]);
