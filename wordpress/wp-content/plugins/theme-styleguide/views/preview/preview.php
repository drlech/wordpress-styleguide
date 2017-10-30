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
        .theme-styleguide-preview-variation-title {
            margin-top: 35px !important;

            font-family: monospace !important;
            font-size: 14px !important;
            line-height: 16px !important;
            text-transform: none !important;
            font-weight: 400 !important;
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
            $preview = new Preview();
            $preview->insert();
        }

        ?>
    </div>

    <?php View::show('preview/footer-scripts'); ?>
</body>
</html>
