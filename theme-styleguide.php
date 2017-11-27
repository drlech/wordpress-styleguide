<?php
/*
Plugin Name: Styleguide
Description: Automatically generates styleguide for the WordPress theme from its components.
Version: 0.10.1
*/

namespace ThemeStyleguide;

defined('ABSPATH') or die();

// Autoload plugin classes
spl_autoload_register(function($class) {
    $namespace = 'ThemeStyleguide';

    // If this is not the class from plugins namespace, ignore it
    if (!preg_match("/^$namespace/", $class)) {
        return;
    }

    // Remove namespace root from the class
    $class = preg_replace("/^$namespace\\\/", '', $class);
    $filename = __DIR__ . "/includes/$class.php";

    if (!file_exists($filename)) {
        throw new \DomainException("Class $class not found.");
    }

    require_once $filename;
});

// Add everything from lib
require_once 'lib/HTMLTag.php';

// Init
if (is_admin()) {
    Admin::init();
} else {
    Front::init();
}
