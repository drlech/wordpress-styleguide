<?php
/**
 * @var string $title words:3
 * @var array  $items
 */

?>

<div class="widget">
    <div class="title"><?php echo $title; ?></div>

    <?php if ($items): ?>
        <ul>
            <?php foreach ($items as $item): ?>
                <li><?php echo $item; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
