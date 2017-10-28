<?php
/**
 * List of component previews.
 *
 * @var string $path
 * @var array $files
 */

?>

 <div class="previews">
    <?php

    foreach ($files as $file) {
        $url = add_query_arg([
            'path' => $path,
            'file' => $file
        ], get_bloginfo('url') . '/theme-styleguide-preview');

        // Build a path to the file to display.
        // Generally taken from the parameters passed to the view, but we don't
        // want to display "root" (as it's not really a name of the real folder)
        // so when on root we just display the file name.
        $pathToShow = $path;
        if ('root' === $pathToShow) {
            $pathToShow = '';
        }

        $filePath = $file;
        if ($pathToShow) {
            $filePath = "$pathToShow/$file";
        }

        ?>

        <div class="component-preview">
            <h3><?php echo $filePath; ?></h3>
            <iframe src="<?php echo $url; ?>"></iframe>
        </div>

        <?php
    }

    ?>
 </div>