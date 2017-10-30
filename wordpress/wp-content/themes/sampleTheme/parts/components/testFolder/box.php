<?php
/**
 * @var bool   $withBorder
 * @var int    $width
 * @var string $text       sentences:5
 */

$classes = ['box'];
if (true === $withBorder) {
    $classes[] = 'with-border';
}

$style = '';
if (isset($width)) {
    $style = "style=\"width: ${width}px;\"";
}

?>

<div class="<?php echo implode(' ', $classes); ?>" <?php echo $style ?>>
    <?php echo $text; ?>
</div>
