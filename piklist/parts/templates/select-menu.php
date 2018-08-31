<?php
$choices = piklist(get_terms('nav_menu', ['hide_empty' => true]), ['term_id', 'name']);

if ( ! empty($allow_no_menu)) {
    $choices = ['' => 'No Menu'] + $choices;
}

piklist('field', [
    'type'       => 'select',
    'field'      => isset($field) ? $field : 'menu',
    'label'      => isset($label) ? $label : 'Menu',
    'columns'    => 12,
    'choices'    => $choices,
    'attributes' => [
        'class' => 'select2',
        'style' => 'width: 100%'
    ]
]);
