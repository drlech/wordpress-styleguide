<?php
/**
 * List of component previews.
 *
 * @var string $page
 * @var string $path
 * @var array $files
 */

namespace ThemeStyleguide;

?>

 <div class="previews">
    <?php

    // Displaying predefined page
    if (isset($page)) {
        ?>

        <iframe src="<?php bloginfo('url'); ?>/theme-styleguide-preview/?page=<?php echo $page; ?>"></iframe>

        <?php
    }

    // Displaying previews of all components from given files
    else {
        foreach ($files as $file) {
            View::show('previews-preview', [
                'path' => $path,
                'file' => $file
            ]);
        }
    }

    ?>
 </div>
