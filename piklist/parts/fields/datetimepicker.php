<?php
$id    = piklist_form::get_field_id($arguments);
$name  = piklist_form::get_field_name($arguments);
$value = is_array($value) ? esc_attr(end($value)) : esc_attr($value);

$attributes['class'][] = 'flatpickr';
$attributes            = piklist_form::attributes_to_string($attributes);

$options = wp_parse_args(isset($options) ? $options : [], [
    'show_time'      => true,
    'display_format' => false
]);

$show_time  = $options['show_time'] ? 'data-enable-time="true"' : '';
$alt_format = $options['display_format'] ? "data-alt-input='true' data-alt-format='{$options['display_format']}'" : '';

echo <<<HTML
<input type="text" id="$id" name="$name" $attributes value="$value" $show_time $alt_format placeholder="Pick date" data-input>
HTML;
