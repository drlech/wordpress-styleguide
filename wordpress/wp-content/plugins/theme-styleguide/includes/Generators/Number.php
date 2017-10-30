<?php

namespace ThemeStyleguide\Generators;

defined('ABSPATH') or die();

class Number extends Generator {

    /**
     * @inheritdoc
     */
    public function generate() {
        // Number range.
        // Choose the number randomly in that case.
        if (preg_match('/(\d+)-(\d+)/', $this->comment, $matches)) {
            $one = (int) $matches[1];
            $two = (int) $matches[2];
            $min = min($one, $two);
            $max = max($one, $two);

            return mt_rand($min, $max);
        }

        // Specific number given.
        if (preg_match('/\d+/', $this->comment, $matches)) {
            return (int) $matches[0];
        }

        // If there is no format of the number specified then don't generate anything.
        // We cannot possibly guess what size of the number will be appropriate
        // because component might be using small numbers (1-3) to display number of
        // some decorators, or larger (hundreds) to set a width in pixels.
        return null;
    }
}