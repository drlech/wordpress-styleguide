<?php

namespace ThemeStyleguide;

defined('ABSPATH') or die();

class Front {

    /**
     * Initialize frontend functionalities.
     */
    public static function init() {
        self::addStyleguidePage();
        self::addPreviewPage();
    }

    /**
     * Add a required query var, and based on its presence
     * display the styleguide page.
     */
    public static function addStyleguidePage() {
        add_filter('query_vars', function ($vars) {
            $vars[] = 'theme-styleguide';

            return $vars;
        });

        add_action('template_redirect', function() {
            global $wp_query;

            if (false === get_query_var('theme-styleguide', false)) {
                return;
            }

            $styleguide = Styleguide::instance();

            if ($styleguide->is('MISSING')) {
                View::show('styleguide-missing');
            } else {
                View::show('styleguide', [
                    'styleguide' => $styleguide
                ]);
            }

            exit();
        });
    }

    /**
     * Register a query var, and based on its presence
     * display the component preview.
     */
    public static function addPreviewPage() {
        add_filter('query_vars', function ($vars) {
            $vars[] = 'theme-styleguide-preview';

            return $vars;
        });

        add_action('template_redirect', function() {
            global $wp_query;

            if (false === get_query_var('theme-styleguide-preview', false)) {
                return;
            }

            View::show('preview/preview', [
                'preview' => $preview
            ]);
            exit();
        });
    }
}
