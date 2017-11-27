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

            $predefinedPages = [
                'typography' => __('Typography', 'wordpress-styleguide'),
                'showcase' => __('Showcase', 'wordpress-styleguide')
            ];

            // Display menu items for the predefined pages.
            foreach ($predefinedPages as $slug => $title) {
                View::show('menu-item', [
                    'item' => [
                        'name' => $title,
                        'link' => $styleguide->getLinkForPredefinedPage($slug),
                        'isActive' => $styleguide->isPredefinedPageActive($slug)
                    ]
                ]);
            }

            echo '<hr>';

            if ($styleguide->getFiles('root')) {
                // Display menu item corresponding to the root of the
                // folder containing the components
                View::show('menu-item', [
                    'item' => [
                        'name' => 'root',
                        'link' => $styleguide->getLinkFor('root'),
                        'isActive' => $styleguide->isMenuItemActive('root'),
                        'isActiveParent' => false
                    ]
                ]);
            }

            // Display (nested) menu structure reflecting the folder structure
            // of the components folder
            foreach ($styleguide->getFolderTree() as $folder) {
                View::show('menu-item', ['item' => $folder]);
            }

            ?>
        </aside>

        <section class="components">
            <?php

            // Display predefined page
            if (isset($_GET['page'])) {
                $predefinedPage = $styleguide->getPredefinedPage($_GET['page']);

                if ($predefinedPage) {
                    View::show('previews', ['page' => $predefinedPage]);
                } else {
                    ?>

                    <p><?php _e('There\'s nothing here. Move along.', 'wordpress-styleguide'); ?></p>

                    <?php
                }
            }

            // Display previews of components
            else {
                $path = 'root';
                if (isset($_GET['path'])) {
                    $path = $_GET['path'];
                }

                $files = $styleguide->getFiles($path);
                if ($files) {
                     View::show('previews', [
                        'path' => $path,
                        'files' => $files
                    ]);
                } else {
                    ?>

                    <p><?php _e('There\'s nothing here. Move along.', 'wordpress-styleguide'); ?></p>

                    <?php
                }
            }

            ?>
        </section>
    </main>

    <script type="text/javascript" src="<?php echo Assets::getAsset('styleguide.js'); ?>"></script>
</body>
</html>
