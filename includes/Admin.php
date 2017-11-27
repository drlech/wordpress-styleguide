<?php

namespace ThemeStyleguide;

defined('ABSPATH') or die();

class Admin {

    /**
     * Name (identifier) of the custom admin page representing
     * the styleguide.
     *
     * @var string
     */
    private static $pageName = 'theme-styleguide';

    /**
     * Initialize the Admin Panel functionality.
     */
    public static function init() {
        self::addSyleguideToMenu();
        self::addStyleguideOptions();
        self::addStylguidePageScripts();

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
                self::$pageName,
                [__CLASS__, 'styleguidePageHtml'],
                'dashicons-media-interactive'
            );
        });
    }

    /**
     * Add styles and script only for this admin page.
     */
    private static function addStylguidePageScripts() {
        add_action('admin_enqueue_scripts', function($hook) {
            if ($hook !== 'toplevel_page_' . self::$pageName) {
                return;
            }

            wp_enqueue_style('theme-styleguide-styles', Assets::getAsset('admin.css'));
        });
    }

    /**
     * Register sections and fields for styleguide settings.
     */
    private static function addStyleguideOptions() {
        add_action('admin_init', function() {
            register_setting(self::$pageName, 'theme-styleguide-settings');

            add_settings_section(
                'theme-styleguide-general-settings',
                __('General Settings', 'theme-styleguide'),
                null,
                self::$pageName
            );

            // Location of all components
            add_settings_field(
                'theme-styleguide-components-location',
                __('Components folder', 'theme-styleguide'),
                [__CLASS__, 'settingsComponentLocationHtml'],
                self::$pageName,
                'theme-styleguide-general-settings'
            );

            // Ignore files from given folder
            // This setting will ignore php files contained within a given folder,
            // but will still recurse it. That option is useful for when we have
            // dedicated folder for non-component things, like scenes.
            add_settings_field(
                'theme-styleguide-ignore-files-from',
                __('Ignore files from', 'theme-styleguide'),
                [__CLASS__, 'settingsIgnoreFilesFrom'],
                self::$pageName,
                'theme-styleguide-general-settings'
            );
        });
    }

    /**
     * Output HTML for the page.
     *
     * Page contains some settings and a link to the styleguide itself.
     *
     * Styleguide is a separate page (outside of the admin panel), because we don't want
     * to have admin panel menus around it.
     */
    public static function styleguidePageHtml() {
        ?>

            <div class="wrap">
                <div class="theme-styleguide-primary-link">
                    <a href="<?php bloginfo('url'); ?>/theme-styleguide" target="_blank"><?php _e('Open styleguide', 'theme-styleguide'); ?></a>
                </div>

                <form action="options.php" method="POST">
                    <?php

                    settings_fields(self::$pageName);
                    do_settings_sections(self::$pageName);
                    submit_button(__('Save', 'theme-styleguide'));

                    ?>
                </form>

                <div>

                </div>
            </div>

        <?php
    }

    /**
     * Generate HTML for the following settings field:
     * theme-styleguide-components-location
     *
     * Defines the path to the components folder, relative to theme.
     */
    public static function settingsComponentLocationHtml() {
        $settings = get_option('theme-styleguide-settings');
        $optionName = 'theme-styleguide-components-location';

        if (!$settings[$optionName]) {
            $settings[$optionName] = 'parts/components';
        }

        ?>

        <input
            type="text"
            class="regular-text"
            name="theme-styleguide-settings[<?php echo $optionName; ?>]"
            value="<?php echo $settings[$optionName]; ?>"
        >

        <p class="description">
            <?php _e('Path to the folder where components are, relative to the theme directory.', 'theme-styleguide'); ?>
        </p>

        <?php
    }

    /**
     * Generate HTML for the following settings field:
     * theme-styleguide-ignore-files-from
     *
     * Specify files from which folders to ignore and not display
     * in the styleguide.
     */
    public static function settingsIgnoreFilesFrom() {
        $settings = get_option('theme-styleguide-settings');
        $optionName = 'theme-styleguide-ignore-files-from';

        ?>

        <textarea
            name="theme-styleguide-settings[<?php echo $optionName; ?>]"
            class="regular-text"
            rows="3"
        ><?php echo $settings[$optionName]; ?></textarea>

        <p class="description">
            <?php _e('Accepts regex. Each expression in new line.', 'theme-styleguide'); ?>
        </p>

        <?php
    }

    /* Endpoints for the custom pages */

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
