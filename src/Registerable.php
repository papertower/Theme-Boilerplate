<?php

namespace Theme;

/**
 * Interface for service classes which are intended to be registerd after instantiation.
 */
interface Registerable {
	/**
	 * Register the current Registerable.
	 */
	public function register();
}
