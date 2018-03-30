<?php
$choices = piklist(GFAPI::get_forms(), array('id', 'title'));

if ( isset($allow_no_form) && $allow_no_form ) {
  $choices = array('' => 'No Form') + $choices;
}

piklist('field', array(
  'type'      => 'select',
  'field'     => isset($field) ? $field : 'form',
  'label'     => isset($label) ? $label : 'Form',
  'choices'   => $choices,
  'attributes'=> array(
    'class'     => 'select2',
    'style'     => 'width: 30%'
  )
));
