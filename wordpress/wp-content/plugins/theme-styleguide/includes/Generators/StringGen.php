<?php

namespace ThemeStyleguide\Generators;

defined('ABSPATH') or die();

/**
 * "String" is reserved :(
 */
class StringGen extends Generator {

    public function generate() {
        return 'Lorem ipsum';
    }
}