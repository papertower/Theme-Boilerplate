<?php

$args = array(
  'type'    => 'editor',
  'field'   => 'post_content',
  'template'=> 'field',
  'scope'   => 'post',
  'columns' => isset($columns) ? $columns : 12,
  'sanitize'=> array(
    array( 'type' => 'wp_kses_post' )
  ),
  'options'  => array(
    'media_buttons' => isset($media_buttons) ? $media_buttons : false,
    'shortcode_buttons' => isset($shortcode_button) ? $shortcode_button : ( isset($media_buttons) ? $media_buttons : false ),
    'teeny'         => false,
    'editor_height'     => isset($editor_height) ? $editor_height : 300,
    'tinymce'       => array(
      'toolbar1'      => 'formatselect, bold, italic, underline, blockquote, strikethrough, bullist, numlist, undo, redo, link, unlink, removeformat, fullscreen',
      'toolbar2'      => ''
    )
  )
);

if ( isset($field) ) {
  $args['field'] = $field;
  unset($args['scope']);
}

if ( isset($label) ) {
  $args['label'] = $label;
  unset($args['template']);
}

piklist('field', $args);
