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
        echo 'Preview of: ' . $path . '/' . $file . '<br>';
    }

    ?>
 </div>