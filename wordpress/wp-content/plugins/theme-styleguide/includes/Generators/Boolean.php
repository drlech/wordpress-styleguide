<?php

namespace ThemeStyleguide\Generators;

defined('ABSPATH') or die();

class Boolean extends Generator {

    /**
     * @inheritdoc
     */
    public function generate() {
        if ('true' === $this->comment) {
            return true;
        }

        if ('false' === $this->comment) {
            return false;
        }

        if ('random' === $this->comment) {
            return (bool) mt_rand(0, 1);
        }

        return false;
    }
}
