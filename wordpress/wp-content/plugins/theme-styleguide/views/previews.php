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

        ?>

        <iframe src="<?php echo $url; ?>"></iframe>

        <?php
    }

    ?>
 </div>