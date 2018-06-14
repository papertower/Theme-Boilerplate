<?php

namespace Theme\Assets;

use Theme\Registerable;

/**
 * Provides the base functionality for a child class to easily enqueue scripts for front or back-end
 */
abstract class BaseAssets implements Registerable {
  const FRONT_SCRIPTS = true;

  /**
   * @var string Assets URI relative to Stylesheet URI
   */
  public $assets_uri;

  /**
   * @var string Assets Path relative to the Stylesheet path
   */
  public $assets_path;

  /**
   * Set the assets URI and path when constructed
   */
  public function __construct() {
    $this->assets_uri = get_template_directory_uri() . '/assets/dist';
    $this->assets_path = get_template_directory() . '/assets/dist';
  }

  /**
   * Hook into the appropriate front or admin hooks for the child class
   */
  public function register() {
    $hook = static::FRONT_SCRIPTS ? 'wp_enqueue_scripts' : 'admin_enqueue_scripts';

    add_action($hook, [$this, 'enqueue']);
  }

  /**
   * Hook method for child class to enqueue appropriate scripts
   */
  abstract public function enqueue();

  /**
   * Provides a shorthand method of enqueueing scripts for the theme based on theme standards.
   *
   * @param  string $handle       Handle for the script
   * @param  string $filename     Simple filename with no path (e.g. example.js)
   * @param  array  $dependencies Array of dependency handles to load the script after
   */
  protected function enqueue_script($handle = '', $filename = '', $dependencies = array(), $in_footer = true) {
    wp_enqueue_script(
      $handle,
      $filename ? "{$this->assets_uri}/{$filename}" : '',
      $dependencies,
      $filename ? filemtime("{$this->assets_path}/{$filename}") : '',
      $in_footer
    );
  }

  /**
   * Provides a shorthand method of enqueueing styles for the theme based on theme standards.
   *
   * @param  string $handle       Handle for the stylesheet
   * @param  string $filename     Simple filename with no path (e.g. example.css)
   * @param  array  $dependencies Array of dependency handles to load the stylesheet after
   */
  protected function enqueue_style($handle = '', $filename = '', $dependencies = array(), $media = 'all') {
    wp_enqueue_style(
      $handle,
      $filename ? "{$this->assets_uri}/{$filename}" : '',
      $dependencies,
      $filename ? filemtime("{$this->assets_path}/{$filename}") : '',
      $media
    );
  }
}
