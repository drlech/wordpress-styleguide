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
    protected $params;

    public function __construct($params = false) {
        $this->params = trim($params);
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
            'boolean' => Boolean::class,
            'array'   => ArrayGen::class
        ];
    }

    /**
     * @param string $type
     * @param string $params
     * @return mixed
     */
    public static function generateValue($type, $params) {
        $generators = self::getGenerators();
        if (!isset($generators[$type])) {
            return null;
        }

        $generator = new $generators[$type]($params);
        return $generator->generate();
    }
}
