<?php
$args = [
    'type'       => 'select',
    'field'      => isset($taxonomy) ? $taxonomy : null,
    'scope'      => 'taxonomy',
    'template'   => 'field',
    'choices'    => piklist(get_terms([
        'taxonomy'   => isset($taxonomy) ? $taxonomy : null,
        'hide_empty' => false
    ]), ['term_id', 'name']),
    'attributes' => [
        'class' => 'select2',
        'style' => 'width: 100%'
    ]
];

if (isset($multiple) && $multiple) {
    $args['attributes']['multiple'] = 'multiple';
}

piklist('field', $args);
