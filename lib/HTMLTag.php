<?php

class HTMLTag
{

    /**
     * Name of the HTML tag.
     *
     * @var string
     */
    private $tag;

    /**
     * Associative array of HTML attributes.
     *
     * @var array
     */
    private $attributes = [];

    /**
     * All classes belonging to the tag.
     *
     * @var array
     */
    private $classes = [];

    /**
     * Contents of the "style" attribute, as associative array.
     *
     * @var array
     */
    private $styles = [];

    /**
     * Associative array of data-* tags.
     *
     * @var array
     */
    private $data = [];

    /**
     * Elements that have no closing tag.
     *
     * @var array
     */
    private static $voidElements = [
        'area',
        'base',
        'br',
        'col',
        'command',
        'embed',
        'hr',
        'img',
        'input',
        'keygen',
        'link',
        'meta',
        'param',
        'source',
        'track',
        'wbr'
    ];

    /**
     * Some HTML tags don't make sense if particular attribute is not present.
     * This is not based on validity according to HTML5 specification, but an attempt
     * to avoid common mistakes and omissions.
     *
     * @var array
     */
    private static $requiredAttributes = [
        'a'      => 'href',
        'embed'  => 'src',
        'iframe' => 'src',
        'input'  => 'type',
        'source' => 'src'
    ];

    /**
     * HTMLTag constructor.
     *
     * @param string $tag        Tag name.
     * @param array  $attributes Associative array of attributes.
     */
    public function __construct($tag, $attributes = [])
    {
        $this->tag = $tag;

        if (is_string($attributes)) {
            $this->addClass($attributes);

            return;
        }

        $this->attributes = $attributes;

        if (isset($this->attributes['class'])) {
            if (is_string($this->attributes['class'])) {
                $this->addClass($this->attributes['class']);
            } else {
                foreach ($this->attributes['class'] as $class) {
                    $this->addClass($class);
                }
            }

            unset($this->attributes['class']);
        }

        if (isset($this->attributes['style'])) {
            $this->styles = $this->attributes['style'];
            unset($this->attributes['style']);
        }

        if (isset($this->attributes['data'])) {
            $this->data = $this->attributes['data'];
            unset($this->attributes['data']);
        }
    }

    /* Public API */

    /**
     * Print the opening tag.
     */
    public function open()
    {
        if (
            array_key_exists($this->tag, self::$requiredAttributes) &&
            ! array_key_exists(self::$requiredAttributes[$this->tag], $this->attributes)
        ) {
            $requiredAttribute = self::$requiredAttributes[$this->tag];
            throw new LogicException("\"$this->tag\" is missing a required attribute: \"$requiredAttribute\"");
        }

        echo "<$this->tag";
        $this->printAttributes();

        if ($this->isVoid()) {
            echo ' /';
        }

        echo '>';
    }

    /**
     * Print the closing tag.
     *
     * @throws LogicException When trying to close a void tag.
     */
    public function close()
    {
        if ($this->isVoid()) {
            throw new LogicException("\"$this->tag\" does't have a closing tag.");
        }

        echo "</$this->tag>";
    }

    /**
     * Prints the tag.
     * If the tag is not void the closing tag will be printed immediately after the opening tag,
     * thus preventing from adding any content in the middle.
     */
    public function printTag()
    {
        $this->open();

        if ( ! $this->isVoid()) {
            $this->close();
        }
    }

    /**
     * Change this tag to another one.
     *
     * @param string $newTag New HTML tag name.
     *
     * @return self Self, for chaining.
     */
    public function changeTo($newTag)
    {
        $this->tag = $newTag;

        return $this;
    }

    /**
     * Add attributes or styles.
     *
     * @param array|string $attribute Name of the attribute, or array of attributes to add.
     * @param bool         $value     Value of attribute to add, if $attribute is a string.
     *
     * @return self Self, for chaining.
     */
    public function add($attribute, $value = false)
    {
        if (is_array($attribute)) {
            foreach ($attribute as $name => $attributeValue) {
                $this->addAttribute($name, $attributeValue);
            }

            return $this;
        }

        if ( ! $value) {
            return $this;
        }

        $this->addAttribute($attribute, $value);

        return $this;
    }

    /**
     * Add a single attribute. The attribute name can also be "style" or "data", in which case
     * the value should be an array of styles or data-* attributes, respectively.
     *
     * @param string $attribute Name of the attribute to add.
     * @param mixed  $value     Value of the attribute.
     *
     * @return self Self, for chaining.
     */
    private function addAttribute($attribute, $value)
    {
        if ('class' === $attribute) {
            $this->addClass($value);

            return $this;
        }

        if ('style' === $attribute) {
            $this->addStyle($value);

            return $this;
        }

        if ('data' === $attribute) {
            $this->addData($value);

            return $this;
        }

        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * Add classes to the tag.
     *
     * @param string|array $class Class to add, or an array of classes to add.
     *
     * @return self Self, for chaining.
     */
    public function addClass($class)
    {
        if (is_string($class)) {
            $class = preg_split('/ /', $class);
        }

        if (1 === count($class)) {
            $class = $class[0];
        }

        if (is_array($class)) {
            foreach ($class as $c) {
                $this->addClass($c);
            }

            return $this;
        }

        if ( ! in_array($class, $this->classes)) {
            $this->classes[] = $class;
        }

        return $this;
    }

    /**
     * Add style or array of styles.
     *
     * @param string|array $style CSS property, or array of styles.
     * @param bool         $value CSS value, if $style is a string.
     *
     * @return self Self, for chaining.
     */
    public function addStyle($style, $value = false)
    {
        if (is_array($style)) {
            $this->styles = array_merge($this->styles, $style);

            return $this;
        }

        if ( ! $value) {
            return $this;
        }

        $this->styles[$style] = $value;

        return $this;
    }

    /**
     * Add single data-* attribute, or array of data-* attributes.
     *
     * @param string|array $data  data-* attribute name, or array of data-* attributes.
     * @param bool         $value data-* attribute value, if $data is a string.
     *
     * @return self Self, for chaining.
     */
    public function addData($data, $value = false)
    {
        if (is_array($data)) {
            $this->data = array_merge($this->data, $data);

            return $this;
        }

        if ( ! $value) {
            return $this;
        }

        $this->data[$data] = $value;

        return $this;
    }

    /**
     * Remove the attribute from the tag.
     * For classes, styles, and data-* attributes, $value can be passed to specify which attribute
     * should be removed.
     * If $value is false then the attribute will be removed from the tag entirely.
     *
     * @param string $attribute Name of the attribute to remove.
     * @param bool   $value     Value of the attribute to remove.
     *
     * @return self Self, for chaining.
     */
    public function remove($attribute, $value = false)
    {
        if ('class' === $attribute) {
            if (false === $value) {
                $this->classes = [];
            } else {
                $this->removeClass($value);
            }
        }

        if ('style' === $attribute) {
            if (false === $value) {
                $this->styles = [];
            } else {
                $this->removeStyle($value);
            }
        }

        if ('data' === $attribute) {
            if (false === $value) {
                $this->data = [];
            } else {
                $this->removeData($value);
            }
        }

        if (isset($this->attributes[$attribute])) {
            unset($this->attributes[$attribute]);
        }

        return $this;
    }

    /**
     * Remove class or multiple classes from the tag.
     * When passing string as $class it can be a single class, or multiple classes
     * separated by a space (like in HTML).
     *
     * @param string|array $class
     *
     * @return self Self, for chaining.
     */
    public function removeClass($class)
    {
        $class = preg_split('/ /', $class);
        if (1 === count($class)) {
            $class = $class[0];
        }

        if (is_array($class)) {
            foreach ($class as $c) {
                $this->removeClass($c);
            }

            return $this;
        }

        $key = array_search($class, $this->classes);
        if ($key !== false) {
            unset($this->classes[$key]);
        }

        return $this;
    }

    /**
     * Remove styles from the tag.
     *
     * @param string|array $style CSS property or an array of CSS properties to remove.
     *
     * @return self Self, for chaining.
     */
    public function removeStyle($style)
    {
        if (is_array($style)) {
            foreach ($style as $s) {
                $this->removeStyle($s);
            }

            return $this;
        }

        unset($this->styles[$style]);

        return $this;
    }

    /**
     * Remove data-* attributes from the tag.
     *
     * @param string|array $data Name of the data-* attribute, or array of data-* attributes to remove.
     *
     * @return self Self, for chaining.
     */
    public function removeData($data)
    {
        if (is_array($data)) {
            foreach ($data as $d) {
                $this->removeData($d);
            }

            return $this;
        }

        unset($this->data[$data]);

        return $this;
    }

    /**
     * Check if this tag is void (does not have closing tag).
     *
     * @return bool
     */
    public function isVoid()
    {
        return in_array($this->tag, self::$voidElements);
    }

    /* Private API */

    /**
     * Print HTML attributes associated with this HTML tag.
     */
    private function printAttributes()
    {
        $this->printCommonAttributes();
        $this->printClasses();
        $this->printData();
        $this->printStyles();
    }

    /**
     * Print all attributes except data-* and style.
     */
    private function printCommonAttributes()
    {
        foreach ($this->attributes as $name => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }

            echo " $name=\"$value\"";
        }
    }

    /**
     * Print all classes.
     */
    private function printClasses()
    {
        if (empty($this->classes)) {
            return;
        }

        $classes = implode(' ', $this->classes);
        echo " class=\"$classes\"";
    }

    /**
     * Print all data-* attributes.
     */
    private function printData()
    {
        foreach ($this->data as $name => $value) {
            echo " data-$name=\"$value\"";
        }
    }

    /**
     * Print all inline styles.
     */
    private function printStyles()
    {
        if (empty($this->styles)) {
            return;
        }

        $styles = '';
        foreach ($this->styles as $style => $value) {
            $styles .= "$style: $value;";
        }

        echo " style=\"$styles\"";
    }
}