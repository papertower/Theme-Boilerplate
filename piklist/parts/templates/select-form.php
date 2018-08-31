<?php
$choices = piklist(GFAPI::get_forms(), ['id', 'title']);

if (isset($allow_no_form) && $allow_no_form) {
    $choices = ['' => 'No Form'] + $choices;
}

piklist('field', [
    'type'       => 'select',
    'field'      => isset($field) ? $field : 'form',
    'label'      => isset($label) ? $label : 'Form',
    'choices'    => $choices,
    'attributes' => [
        'class' => 'select2',
        'style' => 'width: 30%'
    ]
]);
