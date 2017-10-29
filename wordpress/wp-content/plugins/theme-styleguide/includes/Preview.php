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
        include $this->filepath;
    }

    /* Private API */

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
                'name' => preg_replace('/^\$?/', '', $varMatch[2]),
                'type' => $varMatch[1]
            ];

            if (isset($varMatch[4])) {
                $var['comment'] = $varMatch[4];
            }

            $variables[] = $var;
        }

        return $variables;
    }
}