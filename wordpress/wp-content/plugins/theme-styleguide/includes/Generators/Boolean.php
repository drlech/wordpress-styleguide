<?php

namespace ThemeStyleguide\Generators;

defined('ABSPATH') or die();

class Boolean extends Generator {

    /**
     * @inheritdoc
     */
    public function generate() {
        if ('true' === $this->params) {
            return true;
        }

        if ('false' === $this->params) {
            return false;
        }

        if ('random' === $this->params) {
            return (bool) mt_rand(0, 1);
        }

        return false;
    }
}
