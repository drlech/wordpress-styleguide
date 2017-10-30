<?php
/**
 * @var string $description
 * @var string $component
 */

namespace ThemeStyleguide;

?>

<div class="theme-styleguide-preview-component-wrapper">
    <?php

    if (isset($description) && $description) {
        View::show('preview/variation-title', ['title' => $description]);
    }

    echo $component;

    ?>
</div>
