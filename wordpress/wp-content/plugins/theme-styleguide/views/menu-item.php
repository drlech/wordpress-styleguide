<?php
/**
 * @var array $item
 */

namespace ThemeStyleguide;

$styleguide = Styleguide::instance();

?>

 <div class="menu-item">
    <a href="<?php echo $styleguide->getLinkFor($item['path']); ?>" class="box"><?php echo $item['name']; ?></a>

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