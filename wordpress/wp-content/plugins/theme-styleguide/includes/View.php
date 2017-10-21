<?php

namespace ThemeStyleguide;

class View {

    /**
     * Figure out the directory containing the views.
     */
    public static function getViewsDir() {
        return preg_replace('/[\/\\\]includes.*/', '/views', __DIR__);
    }

    /**
     * Display the view of the given name.
     *
     * @param string $name Name of the view file to display (without extension).
     * @param array $args Optional parameters to pass to view as local variables.
     */
    public static function show($name, $args = []) {
        if ($args) {
            extract($args);
        }

        include self::getViewsDir() . "/$name.php";
    }
}