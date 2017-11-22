<?php
/**
 * @var string $title words:3
 * @var string $link  url
 * @var array  $items size:5 [string sentences:2]
 */

?>

<div class="widget">
    <div class="title"><?php echo $title; ?></div>

    <div class="link"><?php echo $link; ?></div>

    <?php if ($items): ?>
        <ul>
            <?php foreach ($items as $item): ?>
                <li><?php echo $item; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
