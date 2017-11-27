<?php

namespace ThemeStyleguide;

$styleguide = Styleguide::instance();

$paths = $styleguide->getAllPaths();

// When the option to show all children is turned on then the components
// might show up twice - when when iteration hits parent, and then again
// when iteration hits the children themselves.
// Let's make sure all components show up only once.
$alreadyShownComponents = [];

if ($paths) {
    foreach ($paths as $path) {
        $files = $styleguide->getFiles($path);
        if (!$files) {
            continue;
        }

        foreach ($files as $file) {
            $componentPath = "$path/$file";
            if (in_array($componentPath, $alreadyShownComponents)) {
                continue;
            }

            $alreadyShownComponents[] = $componentPath;

            View::show('previews-preview', [
                'path' => $path,
                'file' => $file
            ]);
        }
    }
} else {
    ?>

    <p><?php _e('There\'s nothing here. Move along.', 'wordpress-styleguide'); ?></p>

    <?php
}
