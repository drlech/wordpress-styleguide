<?php

namespace ThemeStyleguide;

$styleguide = Styleguide::instance();

$paths = $styleguide->getAllPaths();

if ($paths) {
    foreach ($paths as $path) {
        $files = $styleguide->getFiles($path);

        foreach ($files as $file) {
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
