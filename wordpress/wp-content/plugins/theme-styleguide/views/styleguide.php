<?php
/**
 * The styleguide page.
 *
 * @var Styleguide $styleguide
 */

namespace ThemeStyleguide;

?>

<html>
<head>
    <title><?php _e('Styleguide', 'theme-styleguide'); ?></title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo Assets::getAsset('style.css'); ?>">
</head>

<body>

    <header class="page-header">
        <h1><?php echo $styleguide->getThemeName(); ?></h1>
        <div class="additional-header-info"><?php
            _e(
                sprintf('Components loaded from: %s', 'parts/components'),
                'theme-styleguide'
            );
        ?></div>
    </header>

    <main>
        <aside>
            <?php

            // Display menu item corresponding to the root of the
            // folder containing the components
            View::show('menu-item', [
                'item' => [
                    'name' => 'root',
                    'path' => 'root'
                ]
            ]);

            // Display (nested) menu structure reflecting the folder structure
            // of the components folder
            foreach ($styleguide->getFolderTree() as $folder) {
                View::show('menu-item', ['item' => $folder]);
            }

            ?>
        </aside>

        <div class="components">
            <?php

            $path = 'root';
            if (isset($_GET['path'])) {
                $path = $_GET['path'];
            }

            $files = $styleguide->getFiles($path);
            if (!$files) {
                ?>

                <p><?php _e('There\'s nothing here. Move along.', 'wordpress-styleguide'); ?></p>

                <?php
            } else {
                View::show('previews', [
                    'path' => $path,
                    'files' => $files
                ]);
            }

            ?>
        </div>
    </main>
</body>
</html>