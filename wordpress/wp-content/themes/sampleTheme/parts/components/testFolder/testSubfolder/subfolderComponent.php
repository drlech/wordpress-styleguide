<?php
/**
 * @var array $data assoc [text => string words:3, decorators => number 3-7]
 */

?>

<div class="subfolder-component">
    <div class="text"><?php echo $data['text']; ?></div>

    <div class="decorators">
        <?php foreach ($data['decorators'] as $decorator): ?>$_COOKIE
            <div class="decorator"></div>
        <?php endforeach; ?>
    </div>
</div>

