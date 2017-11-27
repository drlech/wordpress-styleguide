<?php

namespace ThemeStyleguide;

defined('ABSPATH') or die();

class Styleguide {

    /**
     * A singleton of this class.
     *
     * @var Styleguide
     */
    private static $_instance = null;

    /**
     * Maps predefined pages slugs to the names of the views
     * that display them.
     *
     * @var string
     */
    private static $predefinedPages = [
        'typography' => [
            'name' => 'typography',
            'iframe' => true
        ],
        'showcase' => [
            'name' => 'showcase',
            'iframe' => false
        ]
    ];

    /**
     * A list of all states the styleguide can take.
     *
     * @var array
     */
    public static $states = [
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

    private function __construct() {
        $this->setState('PENDING');

        if (!file_exists($this->getComponent())) {
            $this->setState('MISSING');

            return;
        }

        $this->prepare();
        $this->files = $this->removeEmpties($this->files);
    }

    /**
     * Create a singleton of this class.
     */
    public static function instance() {
        if (self::$_instance) {
            return self::$_instance;
        }

        return new self();
    }

    /* Public API */

    /**
     * Check if the styleguide is in a given state.
     *
     * State must come from self::$states.
     *
     * @param string $state
     * @return bool
     */
    public function is($state) {
        // Styleguide cannot be in a state that doesn't exist. Duh.
        if (!isset(self::$states[$state])) {
            return false;
        }

        return $this->state === self::$states[$state];
    }

    /**
     * Check if there is a predefined preview page with a given name.
     *
     * Returns the name of the view file to render if the preview exists,
     * false otherwise.
     *
     * @var string $page
     * @return string|bool
     */
    public function getPredefinedPage($page) {
        if (isset(self::$predefinedPages[$page])) {
            return self::$predefinedPages[$page];
        }

        return false;
    }

    /**
     * Convert $this->files to an array that can be passed
     * to the view.
     */
    public function getFolderTree() {
        return $this->getFoldersFromFiles($this->files);
    }

    /**
     * Retrieve one-dimensional array of all paths within the components folder.
     *
     * This array will contain all possible paths to all subfolders.
     *
     * @return array
     */
    public function getAllPaths() {
        $paths = ['root'];

        return array_merge($paths, $this->getPathsFrom('', $this->files));
    }

    /**
     * Retrieve a link to the styleguide displaying a folder
     * specified by path.
     *
     * This function checks if the specified folder exists
     * in the files array and is recognized by the styleguide.
     *
     * @param string $path
     */
    public function getLinkFor($path) {
        $url = $this->getBaseUrl();

        if (!$path || 'root' === $path || !$this->getFilesFrom($this->files, $path)) {
            return $url;
        }

        return add_query_arg('path', $path, $url);
    }

    /**
     * Retrieve the link for one of the predefined pages,
     * if the page exists.
     *
     * @param string $page
     * @return string|bool
     */
    public function getLinkForPredefinedPage($page) {
        // If the page doesn't exist just display root components.
        // That's not ideal, but I have no good idea what to do with it
        // at the moment.
        if (!isset(self::$predefinedPages[$page])) {
            return $this->getLinkFor('root');
        }

        return add_query_arg('page', $page, $this->getBaseUrl());
    }

    /**
     * Check if we are currently displaying styleguide
     * for the given path.
     *
     * @param string $path
     * @return bool
     */
    public function isMenuItemActive($path) {
        // Predefined pages have priority.
        if (isset($_GET['page'])) {
            return false;
        }

        $currentPath = false;
        if (isset($_GET['path'])) {
            $currentPath = $_GET['path'];
        }

        if (!$currentPath && 'root' === $path) {
            return true;
        }

        if (!$currentPath) {
            return false;
        }

        return $path === $currentPath;
    }

    /**
     * Check if menu item representing given path
     * is a parent of the active item.
     *
     * @param string $path
     * @return bool
     */
    public function isMenuItemActiveParent($path) {
        // Predefined pages have priority.
        if (isset($_GET['page'])) {
            return false;
        }

        $currentPath = false;
        if (isset($_GET['path'])) {
            $currentPath = $_GET['path'];
        }

        if (!$currentPath && 'root' === $path) {
            return true;
        }

        if (!$currentPath) {
            return false;
        }

        return $currentPath !== $path && strpos($currentPath, $path) === 0;
    }

    /**
     * Check if we are currently displaying given predefined
     * preview page.
     *
     * @param string $page
     * @return string
     */
    public function isPredefinedPageActive($page) {
        // First check if that page even exists
        if (!isset(self::$predefinedPages[$page])) {
            return false;
        }

        if (!isset($_GET['page'])) {
            return false;
        }

        return $_GET['page'] === $page;
    }

    /**
     * Get the list of all php files located under
     * given path.
     *
     * @param string $path
     */
    public function getFiles($path = 'root') {
        $settings = get_option('theme-styleguide-settings');

        $files = null;
        $components = [];
        if ('root' === $path || !$path) {
            $files = $this->files;
        } else {
            $files = $this->getFilesFrom($this->files, $path);
        }

        // Path was not found
        if (!$files) {
            return false;
        }

        // Display all children components
        if (isset($settings['one-component-per-folder'])) {
            $folders = array_keys(array_filter($files, function($item) {
                return is_array($item);
            }));

            foreach ($folders as $folder) {
                $componentFiles = $this->getFiles("$path/$folder");

                if ($componentFiles) {
                    $components = array_merge($components, array_map(function($item) use ($folder) {
                        return "$folder/$item";
                    }, $componentFiles));
                }
            }
        }

        // Take only files, no folders
        $components = array_merge($components, array_filter($files, function($item) {
            return !is_array($item);
        }));

        // In alphabetical order
        sort($components);

        return $components;
    }

    /**
     * Retrieve the full path to the component.
     *
     * @param string $path
     * @param string $filename
     */
    public function getComponentPath($path, $filename) {
        $base = get_template_directory() . '/' . $this->getComponentsDir();

        if ('root' === $path) {
            $path = '';
        }

        if ($path) {
            $base .= "/$path";
        }

        return "$base/$filename";
    }

    public function getComponentsDir() {
        $settings = get_option('theme-styleguide-settings');
        if ($settings['components-location']) {
            return $settings['components-location'];
        }

        return 'parts/components';
    }

    /* Private API */

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
            $item = [
                'name' => $name,
                'link' => $this->getLinkFor($path),
                'isActive' => $this->isMenuItemActive($path),
                'isActiveParent' => $this->isMenuItemActiveParent($path)
            ];

            $subitems = $this->getFoldersFromFiles($folders[$name], $name, $path);
            if (is_array($subitems)) {
                $item['subitems'] = $subitems;
            }

            $result[] = $item;
        }

        return $result;
    }

    /**
     * Retrieve all possible paths to all subfolders within given folder.
     *
     * @param string $current Current path (appended to beginning).
     * @param array $where Nested array to go through.
     * @return array
     */
    private function getPathsFrom($current, $where) {
        $paths = [];
        if ($current) {
            $paths = [$current];
        }

        foreach ($where as $name => $item) {
            if (!is_array($item)) {
                continue;
            }

            $subpath = $name;
            if ($current) {
                $subpath = "$current/$name";
            }

            $paths = array_merge($paths, $this->getPathsFrom($subpath, $item));
        }

        return $paths;
    }

    /**
     * Get the URL to the styleguide page.
     */
    private function getBaseUrl() {
        return get_bloginfo('url') . '/theme-styleguide';
    }

    /**
     * Check if a given path exists in the given array.
     * Path is a /-separated string, where each part references next
     * level of the array.
     *
     * @param array $where
     * @param string $path
     */
    private function getFilesFrom($where, $path) {
        $separatorPos = strpos($path, '/');

        if (false === $separatorPos) {
            if (array_key_exists($path, $where)) {
                return $where[$path];
            }

            return false;
        }

        $key = substr($path, 0, $separatorPos);
        if (!array_key_exists($key, $where)) {
            return false;
        }

        $rest = substr($path, $separatorPos + 1);
        return $this->getFilesFrom($where[$key], $rest);
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
        $fullPath = get_template_directory() . '/' . $this->getComponentsDir();
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

        // A setting allows to ignore files from certain folders
        $ignoreFiles = false;
        $settings = get_option('theme-styleguide-settings');
        if (isset($settings['ignore-files-from'])) {
            $expressions = preg_split("/\n/", $settings['ignore-files-from']);

            foreach ($expressions as $expression) {
                if (preg_match("/$expression/", $dir)) {
                    $ignoreFiles = true;

                    break;
                }
            }
        }

        // Check files and recurse
        foreach ($files as $index => $file) {
            if ('.' === $file || '..' === $file || $ignoreFiles) {
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

    /**
     * Remove (recursively) empty entries from given array.
     *
     * @param array $target
     * @return array
     */
    private function removeEmpties($target) {
        foreach ($target as $key => $value) {
            if (is_array($value)) {
                $target[$key] = $this->removeEmpties($target[$key]);
            }

            if (empty($target[$key])) {
                unset($target[$key]);
            }
        }

        return $target;
    }

    /* Helper functions displaying general styleguide info */

    public function getThemeName() {
        return wp_get_theme();
    }
}
