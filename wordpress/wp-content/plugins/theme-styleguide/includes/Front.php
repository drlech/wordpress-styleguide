<?php

namespace ThemeStyleguide;

class Front {

    /**
     * Initialize frontend functionalities.
     */
    public static function init() {
        self::addStyleguidePage();
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

            $styleguide = new Styleguide();
            View::show('styleguide', [
                'styleguide' => $styleguide
            ]);
            exit();
        });
    }
}