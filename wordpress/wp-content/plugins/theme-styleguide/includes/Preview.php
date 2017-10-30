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
        $variations = $this->generateVariableValues();

        // There are no variables, and thus no variations.
        // We can insert the component preview once, as it can (most likely)
        // be displayed without relaying on any additional data.
        if (!$variations) {
            include $this->filepath;

            return;
        }

        // Otherwise display a component preview once for each variation,
        // each having its own set of variables.
        foreach ($variations as $variation) {
            if (isset($variation['values'])) {
                extract($variation['values']);
            }

            include $this->filepath;
        }
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

        // If the component has few different variations (or types, or whatever),
        // then we want to showcase all of them.
        $variations = $this->generateVariations($vars);

        // At this point the file doc exists and contains variables
        // and we have extracted them. We can start generating sample values.
        $generators = Generators\Generator::getGenerators();
        $generatedValues = [];

        foreach ($variations as $variation) {
            $vars = $variation['vars'];

            $variationValues = [];
            foreach ($vars as $var) {
                // Either variable has no type (which should not be possible, as we validate
                // it earlier), or we don't have generator for given variable type.
                // In that case - skip that variable and hope nothing breaks.
                if (!isset($var['type']) || !isset($generators[$var['type']])) {
                    continue;
                }

                $generator = new $generators[$var['type']]($var['comment']);
                $variationValues[$var['name']] = $generator->generate();
            }

            $generatedValues[] = ['values' => $variationValues];
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

    /**
     * If component variable is a bool with "variation" specified as option in the comment
     * we want to display the component multiple times, one for each value to showcase
     * all possible states component can take.
     *
     * This function will check if there are any variations specified and return array
     * of arrays of variables, but with a variation variable set to a fixed value
     * for each variation.
     *
     * @param array $vars
     * @return array
     */
    private function generateVariations($vars) {
        $variations = [];

        for ($i = 0; $i < count($vars); $i++) {
            $var = $vars[$i];

            if ($var['type'] !== 'bool' && $var['type'] !== 'boolean') {
                continue;
            }

            if (trim($var['comment']) !== 'variations') {
                continue;
            }

            // Create variations for both boolean values.
            // We set the values in strings because all that is generated here
            // will be passed to generators, which read variable comments
            // as strings.
            $newVariationTrue = $vars;
            $newVariationTrue[$i]['comment'] = 'true';

            $newVariationFalse = $vars;
            $newVariationFalse[$i]['comment'] = 'false';

            $variations[] = ['vars' => $newVariationTrue];
            $variations[] = ['vars' => $newVariationFalse];
        }

        // There might be no boolean variables and thus no variations created.
        // In that case we want to return the original set of variables.
        // We return it in the array for consistency - that's how variations
        // are returend and array of array of variables is what the calling
        // function will expect.
        if (!$variations) {
            return ['vars' => $vars];
        }

        return $variations;
    }
}
