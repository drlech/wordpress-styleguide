<?php
/**
 * @var array $item
 */

namespace ThemeStyleguide;

?>

 <div class="menu-item">
    <div class="box"><?php echo $item['name']; ?></div>

    <?php if (isset($item['subitems'])): ?>
        <div class="subitems">
            <?php

            foreach ($item['subitems'] as $item) {
                View::show('menu-item', ['item' => $item]);
            }

            ?>
        </div>
    <?php endif; ?>
 </div>