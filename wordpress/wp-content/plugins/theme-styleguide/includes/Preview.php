<?php

namespace ThemeStyleguide;

defined('ABSPATH') or die();

class Preview {

    /**
     * Path to the folder containing the component,
     * relative to the components folder.
     *
     * @var string
     */
    private $path;

    /**
     * Name of the file containing the component.
     *
     * @var string
     */
    private $filename;

    /**
     * Full path to the component file.
     *
     * @var string
     */
    private $filepath;

    public function __construct() {
        $styleguide = Styleguide::instance();

        // Because the previews are typically displayed in an iframe
        // the parameters are passed via $_GET.
        // Let's check if the parameters are set.
        if (!isset($_GET['path']) || !isset($_GET['file'])) {
            throw new \DomainException('Parameters "path" and "file" required.');
        }

        // Check if the parameters are legit - correspond to the
        // actually existing file.
        $this->path = $_GET['path'];
        $this->filename = $_GET['file'];

        $files = $styleguide->getFiles($this->path);
        if (!$files) {
            throw new \DomainException('Invalid parameters.');
        }

        if (!in_array($this->filename, $files)) {
            throw new \DomainException('Invalid parameters.');
        }

        // Generate the full path to the file, and check if it really exists.
        // That's just a sanity check, we should know from styleguide call
        // that the file does exists.
        $styleguide = Styleguide::instance();
        $this->filepath = $styleguide->getComponentPath($this->path, $this->filename);

        if (!file_exists($this->filepath)) {
            throw new \Exception('Well, that should not happen.');
        }
    }

    /* Public API */

    /**
     * Print the component.
     */
    public function insert() {
        $values = $this->generateVariableValues();

        if ($values) {
            extract($values);
        }

        include $this->filepath;
    }

    /* Private API */

    /**
     * Get the file doc variables and generate sample values
     * for all of them. Those values will be passed to the displayed
     * preview so it can display properly.
     *
     * @return array Associative array - variable name => generated value.
     */
    private function generateVariableValues() {
        $fileDoc = $this->getFileDoc();
        if (!$fileDoc) {
            return false;
        }

        $vars = $this->getVarsFromDoc($fileDoc);
        if (!$vars) {
            return false;
        }

        // At this point the file doc exists and contains variables
        // and we have extracted them. We can start generating sample values.
        $generators = Generators\Generator::getGenerators();
        $generatedValues = [];

        foreach ($vars as $var) {
            // Either variable has no type (which should not be possible, as we validate
            // it earlier), or we don't have generator for given variable type.
            // In that case - skip that variable and hope nothing breaks.
            if (!isset($var['type']) || !isset($generators[$var['type']])) {
                continue;
            }

            $generator = new $generators[$var['type']]($var['comment']);
            $generatedValues[$var['name']] = $generator->generate();
        }

        return $generatedValues;
    }

    /**
     * Extract the file comment from the component file.
     *
     * @return string
     */
    private function getFileDoc() {
        $contents = file_get_contents($this->filepath);

        $hasComment = preg_match('/^[\s\n\r]*<\?php[\s\n\r]*(\/\*.+?\*\/)/s', $contents, $matches);
        if (!$hasComment) {
            return false;
        }

        return $matches[1];
    }

    /**
     * Parse the file comment that's in phpDoc format and extract information
     * about all variables.
     *
     * @return array
     */
    private function getVarsFromDoc($doc) {
        // Grab all @var declarations from the doc
        $hasVars = preg_match_all('/\@var.+/', $doc, $matches);
        if (!$hasVars) {
            return false;
        }

        // Parse all @var declarations and extract name of the variable,
        // type, and optional comment.
        $variables = [];
        foreach ($matches[0] as $declaration) {
            $hasCorrectFormat = preg_match('/\@var\s+(\w+)\s+(\$\w+)(\s+(.+))?/', $declaration, $varMatch);
            if (!$hasCorrectFormat) {
                continue;
            }

            $var = [
                'name'    => preg_replace('/^\$?/', '', $varMatch[2]),
                'type'    => $varMatch[1],
                'comment' => false
            ];

            if (isset($varMatch[4])) {
                $var['comment'] = $varMatch[4];
            }

            $variables[] = $var;
        }

        return $variables;
    }
}