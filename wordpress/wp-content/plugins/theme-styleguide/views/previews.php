<?php
/**
 * List of component previews.
 *
 * @var array  $page
 * @var string $path
 * @var array $files
 */

namespace ThemeStyleguide;

?>

 <div class="previews">
    <?php

    // Displaying predefined page
    if (isset($page)) {
        if ($page['iframe']) {
            ?>

            <iframe src="<?php bloginfo('url'); ?>/theme-styleguide-preview/?page=<?php echo $page['name']; ?>"></iframe>

            <?php
        } else {
            $previewName = $page['name'];
            View::show("preview/predefined/$previewName");
        }
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
