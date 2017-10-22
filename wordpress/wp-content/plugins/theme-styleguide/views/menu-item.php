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
                if (array_key_exists('subitems', $item)) {
                    View::show('menu-item', [
                        'name' => $item['name'],
                        'subitems' => $item['subitems']
                    ]);

                    continue;
                }

                View::show('menu-item', ['name' => $item['name']]);
            }

            ?>
        </div>
    <?php endif; ?>
 </div>