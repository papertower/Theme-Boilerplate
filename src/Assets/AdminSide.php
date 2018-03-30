<?php

namespace Theme\Assets;

/**
 * Handles all of the scripts and styles for the admin-side of the site. To add a new style or
 * script, simply add it to the enqueue method below.
 *
 * BaseAssets provide shorthand methods for enqueue_script and enqueue_style. It assumes the asset
 * location to be in the dist directory and also adds automatic versioning.
 */
class AdminSide extends BaseAssets  {
  const FRONT_SCRIPTS = false;

  /**
   * Runs at the time when enqueueing should happen for the admin-side
   */
  public function enqueue() {
    $this->enqueue_script('theme-scripts', 'admin-scripts.js', ['jquery', 'piklist']);
  }
}
