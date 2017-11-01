<?php
/**
 * Preview of a single component.
 */

namespace ThemeStyleguide;

$styleguide = Styleguide::instance();

?>

<html>
<head>
    <title>Component Preview</title>

    <?php View::show('preview/head-scripts'); ?>

    <style type="text/css">
    .theme-styleguide-preview-component-wrapper {
        position: relative !important;
    }

    .theme-styleguide-preview-variation-title {
        padding: 2px 4px !important;

        position: absolute !important;
        right: 0 !important;
        top: 0 !important;

        font-family: monospace !important;
        font-size: 12px !important;
        line-height: 15px !important;
        color: white !important;
        text-transform: none !important;
        font-weight: 400 !important;

        background-color: royalblue !important;
        border-radius: 3px !important;
    }

    .theme-styleguide-preview-variation-title:first-child {
        margin-top: 0 !important;
    }
    </style>
</head>

<body>
    <div>
        <?php

        if (isset($_GET['page'])) {
            $predefinedPreview = $styleguide->getPredefinedPage($_GET['page']);

            if ($predefinedPreview) {
                View::show("preview/predefined/$predefinedPreview");
            } else {
                ?>

                <p><?php _e('There\'s nothing here. Move along.', 'wordpress-styleguide'); ?></p>

                <?php
            }
        } else {
            $preview = new Preview($_GET['path'], $_GET['file']);
            $preview->insert();
        }

        ?>
    </div>

    <?php View::show('preview/footer-scripts'); ?>
</body>
</html>