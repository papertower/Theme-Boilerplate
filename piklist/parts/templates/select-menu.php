<?php
$choices = piklist(get_terms('nav_menu', array('hide_empty'  => true)), array('term_id', 'name'));

if ( !empty($allow_no_menu) ) {
  $choices = array('' => 'No Menu') + $choices;
}

piklist('field', array(
  'type'      => 'select',
  'field'     => isset($field) ? $field : 'menu',
  'label'     => isset($label) ? $label : 'Menu',
  'columns'   => 12,
  'choices'   => $choices,
  'attributes'=> array(
    'class'     => 'select2',
    'style'     => 'width: 100%'
  )
));
