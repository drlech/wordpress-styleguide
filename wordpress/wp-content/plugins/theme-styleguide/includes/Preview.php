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
            throw new \DomainException('Invalid parameters');
        }
    }

    /* Public API */

    public function insert() {
        $styleguide = Styleguide::instance();
        $file = $styleguide->getComponentsDir() . '/' . $this->filename;

        // Sanity check.
        // We *should* be certain that the file exists after constructor.
        if (!file_exists($file)) {
            throw new \Exception('Well, that should not happen.');
        }

        include $file;
    }
}