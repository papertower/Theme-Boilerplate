<?php
$args = array(
  'type'      => 'select',
  'field'     => isset($taxonomy) ? $taxonomy : null,
  'scope'     => 'taxonomy',
  'template'  => 'field',
  'choices'   => piklist(get_terms(array(
    'taxonomy'    => isset($taxonomy) ? $taxonomy : null,
    'hide_empty'  => false
  )), array('term_id', 'name')),
  'attributes'=> array(
    'class'     => 'select2',
    'style'     => 'width: 100%'
  )
);

if ( isset($multiple) && $multiple ) {
  $args['attributes']['multiple'] = 'multiple';
}

piklist('field', $args);
