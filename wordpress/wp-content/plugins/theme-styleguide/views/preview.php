<?php
/**
 * Preview of a single component.
 *
 * @var Preview $preview
 */

namespace ThemeStyleguide;

?>

<html>
<head>
    <title>Component Preview</title>

    <?php

    wp_print_styles();
    wp_print_head_scripts();
    wp_enqueue_scripts();

    ?>
</head>

<body>
    <?php

    $preview->insert();

    wp_print_footer_scripts();

    ?>
</body>
</html>