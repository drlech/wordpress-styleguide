<?php
/**
 * @var string name
 * @var array subitems
 */

namespace ThemeStyleguide;

?>

 <div class="menu-item">
    <div class="box"><?php echo $name; ?></div>

    <?php if (!empty($subitems)): ?>
        <div class="subitems">
            <?php

            foreach ($subitems as $item) {
                if (is_array($item)) {
                    View::show('menu-item', [
                        'name' => $item['name'],
                        'subitems' => $item['subitems']
                    ]);

                    continue;
                }

                View::show('menu-item', ['name' => $item]);
            }

            ?>
        </div>
    <?php endif; ?>
 </div>