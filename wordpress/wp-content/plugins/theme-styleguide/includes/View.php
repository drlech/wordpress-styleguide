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
     */
    public static function show($name) {
        include self::getViewsDir() . "/$name.php";
    }
}