<?php

namespace Theme\Helper;

/**
 * Makes life easier for modifying the admin-side
 *
 * @version 0.1.0
 */
class Admin {
  private static
    $applied_hooks              = array(),
    $editors_to_remove          = array(),
    $metaboxes_to_remove        = array(),
    $support_to_remove          = array(),
    $post_metaboxes_to_remove   = array(),
    $page_to_remove             = array(),
    $menus_to_remove            = array(),
    $submenus_to_remove         = array(),
    $items_to_move              = array(),
    $separators_to_remove       = array(),
    $separators_to_add          = array();

  private static function is_hook_added($function) {
    return in_array($function, self::$applied_hooks);
  }

  private static function get_post_id() {
    if ( !isset($_GET['post']) && !isset($_GET['post_ID']) )
      return;

    return ( isset($_GET['post']) ) ? $_GET['post'] : $_POST['post_ID'] ;
  }

  private static function add_hook($hook, $function, $priority = 10) {
    if ( !self::is_hook_added($function) ) {
      add_filter($hook, array(__CLASS__, $function), $priority);
      self::$applied_hooks[] = $function;
    }
  }

  private static function get_post_types($type) {
    switch(strtolower($type)) {
      case 'custom':
        return get_post_types(array('_builtin' => false));
      case 'builtin':
        return get_post_types(array('_builtin' => true));
      case 'all':
        return get_post_types();
      default:
        return array($type);
    }
  }

  public static function remove_editor($template) {
    if ( !is_admin() ) return;

    $template = is_array($template) ? $template : array($template);
    self::$editors_to_remove = array_merge(self::$editors_to_remove, $template);

    self::add_hook('init', '_remove_editor');
  }

  public static function _remove_editor() {
    $post_id = self::get_post_id();
    if ( !$post_id ) return;

    $template_file = get_post_meta($post_id,'_wp_page_template',TRUE);

    if ( in_array($template_file, self::$editors_to_remove) )
      remove_post_type_support('page', 'editor');
  }

  public static function remove_post_type_support($type, $support) {
    if ( !is_admin() ) return;

    self::$support_to_remove[] = array('type' => $type, 'support' => $support);
    self::add_hook('init', '_remove_post_type_support');
  }

  public static function _remove_post_type_support() {
    if ( empty(self::$support_to_remove) ) return;

    foreach(self::$support_to_remove as $group) {
      $types = self::get_post_types($group['type']);
      foreach($types as $type) {
        remove_post_type_support($type, $group['support']);
      }
    }
  }

  public static function remove_template_metabox($metabox, $template) {
    if ( !is_admin() ) return;

    if ( !isset(self::$metaboxes_to_remove[$metabox]) )
      self::$metaboxes_to_remove[$metabox] = array();

    if ( is_array($template) )
      self::$metaboxes_to_remove[$metabox] = array_merge(self::$metaboxes_to_remove[$metabox], $template);
    else
      self::$metaboxes_to_remove[$metabox][] = $template;

    self::add_hook('do_meta_boxes', '_remove_meta_boxes');
  }

  public static function remove_template_featured_image($template) {
    self::remove_template_metabox('featured-image', $template);
  }

  public static function remove_template_revisions($template) {
    self::remove_template_metabox('revisions', $template);
  }

  public static function _remove_meta_boxes() {
    if ( empty(self::$metaboxes_to_remove) ) return;

    $post_id = self::get_post_id();
    if ( !$post_id ) return;

    $template_file = get_post_meta($post_id,'_wp_page_template',TRUE);
    $metaboxes = self::$metaboxes_to_remove;

    if ( !empty($metaboxes['featured-image']) && in_array($template_file, $metaboxes['featured-image']) )
      remove_meta_box('postimagediv', 'page', 'side');

    if ( !empty($metaboxes['revisions']) && in_array($template_file, $metaboxes['revisions']) )
      remove_meta_box('revisionsdiv', 'page', 'normal');
  }

  public static function remove_menu_page($menu) {
    if ( !is_admin() ) return;

    $menu = is_array($menu) ? $menu : array($menu);
    self::$menus_to_remove = array_merge(self::$menus_to_remove, $menu);
    self::add_hook('admin_menu', '_remove_menu_page', 999);
  }

  public static function remove_submenu_page($menu, $submenu) {
    if ( !is_admin() ) return;

    $submenu = is_array($submenu) ? $submenu : array($submenu);
    if ( !isset(self::$submenus_to_remove[$menu]) ) self::$submenus_to_remove[$menu] = array();
    self::$submenus_to_remove[$menu] = array_merge(self::$submenus_to_remove[$menu], $submenu);
    self::add_hook('admin_menu', '_remove_menu_page', 999);
  }

  public static function _remove_menu_page() {
    foreach(self::$menus_to_remove as $index => $menu)
      remove_menu_page($menu);

    foreach(self::$submenus_to_remove as $menu => $submenus) {
      foreach($submenus as $index => $submenu)
        remove_submenu_page($menu, $submenu);
    }
  }

  public static function remove_post_metabox($id, $post_type, $context) {
    if ( !is_admin() ) return;

    $lower_post_type = strtolower($post_type);
    if ( in_array($lower_post_type, array('builtin', 'custom', 'all')) ) {
      // Keyword is used
      self::$post_metaboxes_to_remove[] = array('id' => $id, 'type' => $lower_post_type, 'context' => $context);

    } else {
      // Post type is provided
      $post_type = is_array($post_type) ? $post_type : array($post_type);

      foreach($post_type as $type)
        self::$post_metaboxes_to_remove[] = array('id' => $id, 'type' => $type, 'context' => $context);
    }

    self::add_hook('do_meta_boxes', '_remove_post_metabox');
  }

  public static function _remove_post_metabox() {
    if ( empty(self::$post_metaboxes_to_remove) ) return;

    $builtin = $custom = $all = null;

    foreach(self::$post_metaboxes_to_remove as $metabox) {
      $types = self::get_post_types($metabox['type']);
      foreach($types as $type) {
        remove_meta_box($metabox['id'], $type, $metabox['context']);
      }
    }
  }

  public static function move_menu_item($item, $new_position) {
    if ( !is_admin() ) return;

    if ( !is_numeric($item) ) {
      switch (strtolower($item)) {
        case 'first-separator': $item = 60; break;
        case 'second-separator': $item = 100; break;
      }
    }

    self::$items_to_move[$item] = $new_position;
    self::add_hook('admin_head', '_set_admin_menu', 10);
  }

  public static function remove_menu_separator($position) {
    self::$separators_to_remove[] = $position;
    self::add_hook('admin_head', '_set_admin_menu', 10);
  }

  public static function add_menu_separator($position) {
    self::$separators_to_add[] = $position;
    self::add_hook('admin_head', '_set_admin_menu', 10);
  }

  public static function _set_admin_menu() {
    global $menu;

    $insert = function($index, $value) use (&$menu) {
      if ( !isset($menu[$index]) ) {
        $menu[$index] = $value;
        return;
      }

      $new_menu = array($index => $value);
      foreach($menu as $set_index => $item) {
        if ( $index > $set_index ) {
          $new_menu[$set_index] = $item;
        } else {
          $new_menu[$set_index + 1] = $item;
        }
      }

      $menu = $new_menu;
    };

    foreach(self::$items_to_move as $item => $new_position) {
      if ( !is_numeric($item) ) {
        $item = strtolower($item);
        foreach($menu as $index => $menu_items) {
          if ( false !== strpos(strtolower($menu_items[0]), $item) ) {
            $item = $index;
            break;
          }
        }
        if ( !is_numeric($item) ) continue;
      }

      $value = $menu[$item];
      unset($menu[$item]);
      $insert($new_position, $value);
    }

    foreach(self::$separators_to_remove as $index => $position) {
      if ( $menu[$position][4] === 'wp-menu-separator' )
        unset($menu[$position]);
    }

    foreach(self::$separators_to_add as $index => $position) {
      $separator = array('', 'read', "separator-$position", '', 'wp-menu-separator');
      $insert($position, $separator);
    }

    ksort($menu);
  }

  public static function show_menu_indexes() {
    self::add_hook('admin_head', '_show_menu_indexes', 999);
  }

  public static function _show_menu_indexes() {
    global $menu;
    foreach($menu as $index => &$items)
      $items[0] .= " ($index)";
  }
}
