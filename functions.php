<?php

// Setup the PSR-4 Autoloader
require_once 'vendor/autoload.php';

// Load the theme!
$theme = Theme\ThemeFactory::create();
$theme->register();
