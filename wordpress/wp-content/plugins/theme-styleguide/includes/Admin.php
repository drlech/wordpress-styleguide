<?php

namespace ThemeStyleguide;

defined('ABSPATH') or die();

class Admin {

    /**
     * Initialize the Admin Panel functionality.
     */
    public static function init() {
        self::addSyleguideToMenu();
        self::addStyleguideEndpoint();
        self::addPreviewEndpoint();
    }

    /**
     * Make the styleguide accessible from the admin panel.
     */
    private static function addSyleguideToMenu() {
        add_action('admin_menu', function() {
            add_menu_page(
                __('Styleguide', 'theme-styleguide'),
                __('Styleguide', 'theme-styleguide'),
                'manage_options',
                'theme-styleguide',
                [__CLASS__, 'styleguideMenuRedirect'],
                'dashicons-media-interactive'
            );
        });
    }

    /**
     * If we added normal Admin Panel page, we'd have all the interface
     * around it, which is not ideal.
     * Instead we'll register a custom route for the page, and
     * the admin panel link will just redirect there.
     */
    public static function styleguideMenuRedirect() {
        ?>

            <div class="wrap">
                <p><?php _e('Opening styleguide...', 'theme-styleguide'); ?></p>

                <script type="text/javascript">
                window.location = "<?php bloginfo('url'); ?>/theme-styleguide";
                </script>
            </div>

        <?php
    }

    /**
     * Register the endpoint for the styleguide page.
     */
    public static function addStyleguideEndpoint() {
        add_action('init', function() {
            add_rewrite_endpoint('theme-styleguide', EP_ROOT);
        });
    }

    /**
     * Add an endpoint for displaying the previews of the components.
     */
    public static function addPreviewEndpoint() {
        add_action('init', function() {
            add_rewrite_endpoint('theme-styleguide-preview', EP_ROOT);
        });
    }
}