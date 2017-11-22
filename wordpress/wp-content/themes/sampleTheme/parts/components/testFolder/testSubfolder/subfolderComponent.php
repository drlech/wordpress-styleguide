<?php
/**
 * @var array $data assoc [text => string words:3, decorators => int 3-7]
 */

$decorators = $data['decorators'];

?>

<div class="subfolder-component">
    <div class="text"><?php echo $data['text']; ?></div>

    <div class="decorators">
        <?php for ($i = 0; $i < $decorators; $i++): ?>
            <div class="decorator"></div>
        <?php endfor; ?>
    </div>
</div>

