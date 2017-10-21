<?php
/*
Plugin Name: Styleguide
*/

namespace ThemeStyleguide;

defined('ABSPATH') or die();

spl_autoload_register(function($class) {
    $namespace = 'ThemeStyleguide';

    // If this is not the class from plugins namespace, ignore it
    if (!preg_match("/^$namespace/", $class)) {
        return;
    }

    // Remove namespace root from the class
    $class = preg_replace("/^$namespace\\\/", '', $class);

    require_once __DIR__ . "/includes/$class.php";
});

Admin::init();