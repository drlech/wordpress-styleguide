<?php
/**
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

            View::show('menu-item', ['name' => 'root']);

            foreach ($styleguide->getFolderTree() as $folder) {
                if (is_array($folder)) {
                    View::show('menu-item', [
                        'name' => $folder['name'],
                        'subitems' => $folder['subitems']
                    ]);

                    continue;
                }

                View::show('menu-item', ['name' => $folder]);
            }

            ?>
        </aside>

        <div class="components">
            Main part
        </div>
    </main>

</body>
</html>