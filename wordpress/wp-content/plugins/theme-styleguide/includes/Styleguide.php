<?php

namespace ThemeStyleguide;

defined('ABSPATH') or die();

class Styleguide {

    /**
     * A path relative to the current theme where the components
     * to be displayed in the styleguide are located.
     *
     * @var string
     */
    private static $componentsLocation = 'parts/components';

    /**
     * A list of all states the styleguide can take.
     *
     * @var array
     */
    private static $states = [
        'PENDING' => 1,
        'OK' => 2,
        'MISSING' => 3
    ];

    /**
     * Current state of the styleguide.
     *
     * @var int
     */
    private $state;

    /**
     * List of all files displayed by the styleguide.
     *
     * @var array
     */
    private $files = [];

    public function __construct() {
        $this->setState('PENDING');

        if (!$this->getComponent()) {
            $this->setState('MISSING');

            return;
        }

        $this->prepare();
    }

    /* Public API */

    public function getFolderTree() {
        return $this->getFoldersFromFiles($this->files);
    }

    /* Helpers for public API */

    private function getFoldersFromFiles($where, $name = '', $currentPath = '') {
        $result = [];

        // Folders are arrays contining their content,
        // files are just strings.
        $folders = array_filter($where, function($item) {
            return is_array($item);
        });

        if (empty($folders)) {
            return $name;
        }

        // Sort alphabetically, so it reflects whatever's
        // on the hard drive.
        ksort($folders);

        // Recursively scan all the folders.
        foreach ($folders as $name => $folder) {
            $path = preg_replace('/^\//', '', "$currentPath/$name");
            $subitems = $this->getFoldersFromFiles($folders[$name], $name, $path);

            if (is_array($subitems)) {
                $result[] = [
                    'name' => $name,
                    'subitems' => $subitems
                ];

                continue;
            }

            $result[] = [
                'name' => $name,
                'path' => $path
            ];
        }

        return $result;
    }

    /* Initialization / class operations */

    /**
     * Set the state of the styleguide.
     *
     * @see self::$states
     * @param string $state
     */
    private function setState($state) {
        if (!array_key_exists($state, self::$states)) {
            throw new \BadMethodCallException("State $state is invalid.");
        }

        $this->state = self::$states[$state];
    }

    /**
     * Retrieve a full path to the component.
     *
     * @param string $path Path to the component relative to the components folder.
     */
    private function getComponent($path = '') {
        $fullPath = get_template_directory() . '/' . self::$componentsLocation;
        if ($path) {
            $fullPath .= '/' . $path;
        }

        return $fullPath;
    }

    /**
     * Scan the components directory to retrieve all files to be displayed.
     */
    private function prepare() {
        $this->files = $this->prepareDir('');
    }

    /**
     * Scan a single subdirectory under components directory.
     *
     * @param string $dir Path under components directory.
     */
    private function prepareDir($dir) {
        $result = [];
        $files = scandir($this->getComponent($dir));

        foreach ($files as $index => $file) {
            if ('.' === $file || '..' === $file) {
                continue;
            }

            if (preg_match('/\.([^.]+)$/', $file, $matches)) {
                if ($matches[1] !== 'php') {
                    continue;
                }

                $result[] = $file;

                continue;
            }

            $result[$file] = $this->prepareDir($dir . '/' . $file);
        }

        return $result;
    }

    /* Helper functions displaying general styleguide info */

    public function getThemeName() {
        return wp_get_theme();
    }
}