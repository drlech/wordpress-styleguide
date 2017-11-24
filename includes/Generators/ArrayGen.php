<?php

namespace ThemeStyleguide\Generators;

defined('ABSPATH') or die();

class ArrayGen extends Generator {

    /**
     * What type of array are we generating.
     * Possible values: normal, associative.
     *
     * @var string
     */
    private $type = 'normal';

    /**
     * @inheritdoc
     */
    public function generate() {
        if (preg_match('/assoc/', $this->params)) {
            $this->type = 'associative';
        }

        if ('normal' === $this->type) {
            return $this->generateNormalArray();
        }

        return $this->generateAssociativeArray();
    }

    /**
     * Generate value for non-associative array.
     *
     * All elements of the array will have the same variable type.
     *
     * @return array
     */
    private function generateNormalArray() {
        $params = $this->params;

        // size:N sets the size of generated array to the value N.
        // size:N-M sets the size of generated array to a random value from range.
        // If the parameter is not set it defaults to 3 elements.
        $size = 3;
        if (preg_match('/size:(\d+)-(\d+)/', $params, $matches)) {
            $size = mt_rand((int) $matches[1], (int) $matches[2]);
        } elseif (preg_match('/size:(\d+)/', $params, $matches)) {
            $size = (int) $matches[1];
        }

        // [types]
        // Specifies what types of variables will be placed in the array.
        // Types have the following format:
        // type params
        //
        // Example:
        // [string words:5]
        $hasTypes = preg_match('/\[(.+)\s+(.+)\]/', $params, $matches);
        if (!$hasTypes) {
            return [];
        }

        $values = [];
        for ($i = 0; $i < $size; $i++) {
            $values[] = Generator::generateValue($matches[1], $matches[2]);
        }

        return $values;
    }

    /**
     * Generate random associative array.
     *
     * This will be called if "assoc" parameter is present.
     *
     * @return array
     */
    private function generateAssociativeArray() {
        // [types]
        // The list of types for the array to generate. Format:
        // index => type params, index => type params
        //
        // Example:
        // [text => string words:3, stars => number 3]
        $hasTypes = preg_match('/\[(.+)\]/', $this->params, $matches);
        if (!$hasTypes) {
            return [];
        }

        $value = [];

        // Array fields are comma separated
        $fields = preg_split('/,\s+/', $matches[1]);
        foreach ($fields as $field) {
            $inCorrectFormat = preg_match('/(.+)\s+=>\s+(.+)\s+(.+)/', $field, $matches);
            if (!$inCorrectFormat) {
                continue;
            }

            // Extract variables from matches to make it clear what's what
            $index = $matches[1];
            $type = $matches[2];
            $params = $matches[3];

            $value[$index] = Generator::generateValue($type, $params);
        }

        return $value;
    }
}
