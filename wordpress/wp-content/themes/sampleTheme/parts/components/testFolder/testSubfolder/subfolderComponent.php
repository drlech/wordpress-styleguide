<?php
/**
 * @var string $image image:300-400x10-100
 * @var array $data assoc [text => string words:3, decorators => int 3-7]
 */

$decorators = $data['decorators'];

?>

<div class="subfolder-component">
    <img src="<?php echo $image; ?>" alt="">

    <div class="text"><?php echo $data['text']; ?></div>

    <div class="decorators">
        <?php for ($i = 0; $i < $decorators; $i++): ?>
            <div class="decorator"></div>
        <?php endfor; ?>
    </div>
</div>

