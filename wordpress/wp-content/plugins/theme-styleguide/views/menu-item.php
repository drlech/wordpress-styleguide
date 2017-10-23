<?php
/**
 * @var array $item
 */

namespace ThemeStyleguide;

$styleguide = Styleguide::instance();
$menuItem = new \HTMLTag('div', 'menu-item');

if ($styleguide->isPathActive($item['path'])) {
    $menuItem->addClass('active');
}

$menuItem->open();

?>

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

 <?php

 $menuItem->close();