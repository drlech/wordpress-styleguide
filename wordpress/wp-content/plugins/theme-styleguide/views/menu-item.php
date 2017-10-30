<?php
/**
 * Menu items displayed in the sidebar of styleguide.
 *
 * @var array $item
 */

namespace ThemeStyleguide;

$styleguide = Styleguide::instance();
$menuItem = new \HTMLTag('div', 'menu-item');

// Process the classes for the menu item based on passed settings
if (isset($item['isActive']) && true === $item['isActive']) {
    $menuItem->addClass('active');
}

if (isset($item['isActiveParent']) && true === $item['isActiveParent']) {
    $menuItem->addClass('active-parent');
}

$menuItem->open();

?>

<a href="<?php echo $item['link']; ?>" class="box"><?php echo $item['name']; ?></a>

<?php if (isset($item['subitems'])): ?>
    <div class="subitems">
        <?php

        foreach ($item['subitems'] as $item) {
            View::show('menu-item', ['item' => $item]);
        }

        ?>
    </div>
<?php endif; ?>

 <?php

 $menuItem->close();
