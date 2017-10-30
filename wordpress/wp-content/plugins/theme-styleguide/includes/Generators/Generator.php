<?php

namespace ThemeStyleguide\Generators;

defined('ABSPATH') or die();

abstract class Generator {

    /**
     * Variable comment. It will determine what value
     * is generated for the variable.
     *
     * @var string
     */
    protected $comment;

    public function __construct($comment = false) {
        $this->comment = trim($comment);
    }

    /**
     * Generate the sample variable value.
     */
    public abstract function generate();

    /**
     * Retrieve the list of all available variable value generators.
     *
     * @return array
     */
    public static function getGenerators() {
        return [
            'string'  => StringGen::class,
            'int'     => Number::class,
            'integer' => Number::class,
            'bool'    => Boolean::class,
            'boolean' => Boolean::class
        ];
    }
}
