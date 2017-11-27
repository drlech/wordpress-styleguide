# WordPress styleguide

Plugin automatically generates a styleguide for the active theme.

There are some requirements - theme must be made of components placed in their own folder, and each component must have a file doc documenting variables used by that file.

Values of the variables are randomly generated, and descriptions of the variables in the file doc can be used to specify what values should the component previews contains.

Example:
```html
<?php
/**
 * @var string $title words:3
 * @var content $content sentences:5
 */

?>

<div class="sample-component">
  <div class="title"><?php echo $title; ?></div>

  <div class="content">
    <?php echo $content; ?>
  </div>
</div>

```

## Available types and parameters

### string

* `sentence` - One sentence.
* `sentences:X` - X random sentences.
* `word` - A random word.
* `words:X` - X random words.
* `url` - A random URL.
* `image:X` - A random image. X can be `icon`, `small`, `medium`, or `large`.
* `image:WxH` - An image with W width and H height.
* `image:{Wmin}-{Wmax}x{Hmin}-{Hmax}` - Image of random dimensions. Width will be between `Wmin` and `Wmax`, height will be between `Hmin` and `Hmax`.
* `content` - A few paragraphs of random content, similar to what can be created using WordPress WYSIWYG editor. This can contains headings, blockquotes, and images with alignment classes, like `alignleft`.

### array

* `size:X [type parameters]` - Random array of size X, whose elements are of given type with given parameters. Example: `size:5 [string sentences:5]`. Size can also be a range, e.g. `size:3-5`.
* `assoc [index => type parameters, index => type parameters]` - Associative array with elements as specified in the brackets. Example: `assoc [title => string words:3, content => string sentences:5]`.

### boolean (or bool)

* `true` - Will always be true.
* `false` - Will always be false.
* `random` - Random value.

### int (or integer)

* `X` - Will always be given number.
* `X-Y` - Random value between `X` and `Y`.
