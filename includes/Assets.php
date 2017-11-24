<?php

namespace ThemeStyleguide;

defined('ABSPATH') or die();

class Assets {

    /**
     * Generate the URL to the assets dir.
     */
    public static function getAssetsUrl() {
        $includesUrl = plugins_url('', __FILE__);
        return preg_replace('/[\/\\\]includes.*/', '/assets', $includesUrl);
    }

    /**
     * Get the URL to the given asset.
     *
     * @param string name Path to the asset, relative to the assets directory.
     */
    public static function getAsset($name) {
        return self::getAssetsUrl() . "/$name";
    }
}