<?php
/*
Title: Custom Sections
Post Type: page
Template: page-templates/sitemap
Context: normal
Priority: high
Order: 25
Collapse: true
*/

$menus = get_terms('nav_menu', array('hide_empty' => false));

piklist('field', array(
  'help'    => 'Menu to use for section',
  'type'    => 'select',
  'field'   => 'custom-sections',
  'label'   => 'Menu',
  'add_more'=> true,
  'choices' => piklist($menus, array('term_id', 'name'))
));
