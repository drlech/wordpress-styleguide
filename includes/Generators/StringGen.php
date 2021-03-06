<?php

namespace ThemeStyleguide\Generators;

defined('ABSPATH') or die();

/**
 * "String" is reserved :(
 */
class StringGen extends Generator {

    /**
     * The most common and famous starting sentence of "lorem ipsum".
     * Generated texts will always start with this one.
     *
     * @var string
     */
    public static $base = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';

    /**
     * Length of the base text (number of words).
     *
     * @var int
     */
    public static $baseLength = 8;

    /**
     * Vocabulary of all words that will be used to generate random
     * blocks of text.
     *
     * Source: https://github.com/joshtronic/php-loremipsum
     *
     * @var array
     */
    private static $vocabulary = [
        'lorem',        'ipsum',       'dolor',        'sit',
        'amet',         'consectetur', 'adipiscing',   'elit',
        'a',            'ac',          'accumsan',     'ad',
        'aenean',       'aliquam',     'aliquet',      'ante',
        'aptent',       'arcu',        'at',           'auctor',
        'augue',        'bibendum',    'blandit',      'class',
        'commodo',      'condimentum', 'congue',       'consequat',
        'conubia',      'convallis',   'cras',         'cubilia',
        'cum',          'curabitur',   'curae',        'cursus',
        'dapibus',      'diam',        'dictum',       'dictumst',
        'dignissim',    'dis',         'donec',        'dui',
        'duis',         'egestas',     'eget',         'eleifend',
        'elementum',    'enim',        'erat',         'eros',
        'est',          'et',          'etiam',        'eu',
        'euismod',      'facilisi',    'facilisis',    'fames',
        'faucibus',     'felis',       'fermentum',    'feugiat',
        'fringilla',    'fusce',       'gravida',      'habitant',
        'habitasse',    'hac',         'hendrerit',    'himenaeos',
        'iaculis',      'id',          'imperdiet',    'in',
        'inceptos',     'integer',     'interdum',     'justo',
        'lacinia',      'lacus',       'laoreet',      'lectus',
        'leo',          'libero',      'ligula',       'litora',
        'lobortis',     'luctus',      'maecenas',     'magna',
        'magnis',       'malesuada',   'massa',        'mattis',
        'mauris',       'metus',       'mi',           'molestie',
        'mollis',       'montes',      'morbi',        'mus',
        'nam',          'nascetur',    'natoque',      'nec',
        'neque',        'netus',       'nibh',         'nisi',
        'nisl',         'non',         'nostra',       'nulla',
        'nullam',       'nunc',        'odio',         'orci',
        'ornare',       'parturient',  'pellentesque', 'penatibus',
        'per',          'pharetra',    'phasellus',    'placerat',
        'platea',       'porta',       'porttitor',    'posuere',
        'potenti',      'praesent',    'pretium',      'primis',
        'proin',        'pulvinar',    'purus',        'quam',
        'quis',         'quisque',     'rhoncus',      'ridiculus',
        'risus',        'rutrum',      'sagittis',     'sapien',
        'scelerisque',  'sed',         'sem',          'semper',
        'senectus',     'sociis',      'sociosqu',     'sodales',
        'sollicitudin', 'suscipit',    'suspendisse',  'taciti',
        'tellus',       'tempor',      'tempus',       'tincidunt',
        'torquent',     'tortor',      'tristique',    'turpis',
        'ullamcorper',  'ultrices',    'ultricies',    'urna',
        'ut',           'varius',      'vehicula',     'vel',
        'velit',        'venenatis',   'vestibulum',   'vitae',
        'vivamus',      'viverra',     'volutpat',     'vulputate',
    ];

    /**
     * Predefined image sizes for generating random images.
     *
     * @var array
     */
    private static $imageSizes = [
        'icon' => ['w' => 30, 'h' => 30],
        'small' => ['w' => 100, 'h' => 80],
        'medium' => ['w' => 640, 'h' => 480],
        'large' => ['w' => 1600, 'h' => 900]
    ];

    /**
     * @inheritdoc
     */
    public function generate() {
        // 'Lorem ipsum' (case insensitive).
        // Generates: the most common/famous "Lorem ipsum dolor sit amet..." sentence.
        if ('lorem ipsum' === strtolower($this->params)) {
            return self::$base;
        }

        // A single sentence.
        // This will always be base "Lorem ipsum dolor sit amet..."
        if ('sentence' === $this->params) {
            return $this->generateSentences(1);
        }

        // sentences:number, e.g. sentences:5
        // Generates: given number of random sentences.
        if (preg_match('/sentences:(\d+)/', $this->params, $matches)) {
            $numberOfSentences = (int) $matches[1];
            return $this->generateSentences($numberOfSentences);
        }

        // A single word.
        if ('word' === $this->params) {
            return $this->generateWord();
        }

        // words:number, e.g. words:20
        // Generates: random sentences with a total number of words
        // as specified. This will not generate generate a string of non-puncuated
        // words, but normal sentences. Difference between this and "sentence:x" option
        // is that in case of "sentence:x" sentences will be completely random
        // (which means the total length of the text is random), but here we make sure
        // to generate text of specific length (in words).
        if (preg_match('/words:(\d+)/', $this->params, $matches)) {
            $numberOfWords = (int) $matches[1];
            return $this->generateWords($numberOfWords);
        }

        // url
        // Generates a sample URL.
        if ('url' === $this->params) {
            return $this->generateUrl();
        }

        // image:size, or image:WxH, image:W-WxH-H
        // Generates random image with specified size.
        // Size can be:
        // - A word, in which case size is taken from predefined sizes
        // defined as static property on this object.
        // - Fixed size (widht x height).
        // - Random size (width and height ranges to randomize from).
        if (preg_match('/image:(.+)/', $this->params, $matches)) {
            return $this->generateImageFromParams($matches[1]);
        }

        /**
         * content
         * Generates sample post/page content, a series of paragraphs with headings,
         * images, etc.
         */
        if ('content' === $this->params) {
            return $this->generateContent();
        }

        // If we didn't match a predefined rule, we return just those two words.
        // They are ideal for displaying common things like button texts,
        // labels, input placeholders, etc.
        return 'Lorem ipsum';
    }

    /* Lorem ipsum generators */

    /**
     * Generate given number of sentences.
     *
     * @param int $number
     * @return string
     */
    private function generateSentences($number) {
        if (1 === $number) {
            return self::$base;
        }

        $sentences = [self::$base];
        for ($i = 0; $i < $number - 1; $i++) {
            $sentences[] = $this->generateSentence();
        }

        return implode(' ', $sentences);
    }

    /**
     * Generate a string of random sentences that will have
     * a total of $number number of words.
     *
     * @param int $number
     * @return string
     */
    private function generateWords($number) {
        // If we request words fewer or equal than the base text then generate
        // a random sentence. That is to prevent displaying the same thing every time
        // we want to display a short text.
        // If the number of words to generate indicates multiple sentences will be
        // generated then the first sentence will always be "Lorem ipsum".
        if ($number <= self::$baseLength) {
            return $this->generateSentence($number);
        }

        // We are here if requested number of words was higher than the length
        // of the base sentence. In that case we take the base sentence
        // as the first one, and add a bunch of random sentences to it.
        $sentences = [self::$base];
        $number -= self::$baseLength;

        $maxSentenceLength = 15;

        // Keep generating sentences as long as remaining number of words
        // is smaller than the largest sentence that can be randomly generated.
        // That prevents us from overshooting.
        while ($number > 0 && $number >= $maxSentenceLength) {
            $newSentence = $this->generateSentence();
            $sentences[] = $newSentence;
            $number -= count(explode(' ', $newSentence));
        }

        // At this point we're missing just a couple of words till the end.
        // If we generate sentence at random we might overshoot and have longer
        // text than requested.
        $sentences[] = $this->generateSentence($number);

        return implode(' ', $sentences);
    }

    /**
     * Generate a random sentence.
     *
     * @param int $length Length of the sentence; will be random if not provided.
     * @return string
     */
    private function generateSentence($length = null) {
        $sentenceLength = $length;
        if (!$length) {
            $sentenceLength = mt_rand(5, 15);
        }

        $words = array_rand(self::$vocabulary, $sentenceLength);
        $words = array_map(function($item) {
            return self::$vocabulary[$item];
        }, $words);

        // Capitalize the first word.
        $words[0] = ucfirst($words[0]);

        // Add period ending the sentence.
        $words[count($words) - 1] .= '.';

        // Add commas to words randomly.
        // In english there's approx. between 0.6 and 1.8 words per sentence,
        // depending on author. There are no easily available stats for latin,
        // so we'll just use english stats.
        $wordsWithCommas = mt_rand(6, 18) / 10;
        $probOfComma = abs(($wordsWithCommas * 100) / $sentenceLength);

        // Skipping the last word because it already has a period.
        for ($i = 0; $i < count($words) - 1; $i++) {
            $chance = mt_rand(0, 100);
            if ($chance > $probOfComma) {
                continue;
            }

            $words[$i] .= ',';
        }

        return implode(' ', $words);
    }

    /**
     * Generate a single random word.
     *
     * @return string
     */
    private function generateWord() {
        $index = array_rand(self::$vocabulary);
        $word = self::$vocabulary[$index];

        // Capitalize it
        return ucfirst($word);
    }

    /* Image generators */

    /**
     * Generate a placeholder image of a given width and height.
     *
     * This function uses generates an URL to the image placeholder service,
     * and can be used by other functions to output the image URL after
     * they determine the final width and height.
     *
     * @param int $width
     * @param int $height
     * @return string
     */
    private function generateImage($width, $height) {
        return "http://via.placeholder.com/${width}x${height}";
    }

    /**
     * Parse the params given to the "image:" format and determine how the
     * placeholder image should be generated.
     *
     * @param string $imageParams
     * @return string
     */
    private function generateImageFromParams($imageParams) {
        if (preg_match('/(\d+)-(\d+)x(\d+)-(\d+)/', $imageParams, $matches)) {
            return $this->generateImageRandomSize($matches[1], $matches[2], $matches[3], $matches[4]);
        }

        if (preg_match('/(\d+)x(\d+)/', $imageParams, $matches)) {
            return $this->generateImage($matches[1], $matches[2]);
        }

        return $this->generateImageWithPredefinedSize($imageParams);
    }

    /**
     * Generate image with random dimensions.
     *
     * @param int $minWidth
     * @param int $maxWidth
     * @param int $minHeight
     * @param int $maxHeight
     * @return string
     */
    private function generateImageRandomSize($minWidth, $maxWidth, $minHeight, $maxHeight) {
        $width = mt_rand($minWidth, $maxWidth);
        $height = mt_rand($minHeight, $maxHeight);

        return $this->generateImage($width, $height);
    }

    /**
     * Generate image based on one of the predefined image sizes.
     *
     * @see self::$imageSizes
     * @param string $sizeIdentifier
     * @return string
     */
    private function generateImageWithPredefinedSize($sizeIdentifier) {
        if (!isset(self::$imageSizes[$sizeIdentifier])) {
            return '';
        }

        $size = self::$imageSizes[$sizeIdentifier];
        return $this->generateImage($size['w'], $size['h']);
    }

    /* Content generators */

    /**
     * Generate sample content containing headings, paragraphs, images, and similar tags,
     * similar to what can be generated via WordPress editor.
     *
     * @return string
     */
    private function generateContent() {
        $content = '';

        // Main heading
        if (mt_rand(0, 100) < 50) {
            $content .= '<h1>' . $this->generateSentence() . '</h1>';
        }

        // Few paragraphs
        for ($i = 0; $i < mt_rand(3, 7); $i++) {
            $content .= $this->generateContentParagraph();
        }

        return $content;
    }

    /**
     * Generate a random paragraph of text (with <p> tags).
     */
    private function generateContentParagraph() {
        // 50% chance of regular text paragraph
        if (mt_rand(0, 100) < 50) {
            return '<p>' . $this->generateSentences(mt_rand(5, 15)) . '</p>';
        }

        // Otherwise we will create something else, like a
        // paragraph with picture, or a blockquote, etc.
        $specialParagraph = mt_rand(1, 3);
        switch ($specialParagraph) {
            case 1:
                return $this->generateContentParagraphWithImage();

            case 2:
                return $this->generateContentHeading();

            default:
                return $this->generateContentBlockquote();
        }
    }

    /**
     * Generate a paragraph (with <p> tags) containing an image.
     * Image will have (random) WordPress alignment class.
     */
    private function generateContentParagraphWithImage() {
        $image = $this->generateImage(300, 225);

        $alignments = ['left', 'right', 'center'];
        $alignment = $alignments[array_rand($alignments)];
        $alignmentClass = "align${alignment}";

        $paragraph = '<p>';

        // Image
        $paragraph .= "<img src=\"$image\" />";

        // Paragraph text
        $paragraph .= $this->generateSentences(mt_rand(5, 15));

        // Close
        $paragraph .= '</p>';

        return $paragraph;
    }

    /**
     * Generate random heading from h2 to h6.
     *
     * h1 is reserved to be main page heading and will occur
     * only once.
     */
    private function generateContentHeading() {
        $size = mt_rand(2, 6);

        return "<h$size>" . $this->generateSentence() . "</h$size>";
    }

    /**
     * Generate a blockquote with few random sentences.
     */
    private function generateContentBlockquote() {
        return '<blockquote>' . $this->generateSentences(mt_rand(5, 15)) . '</blockquote>';
    }

    /* Other */

    /**
     * Generate a random URL.
     *
     * The URL will be made of one or few words separated by a dash.
     * It can also randomly have a path after a domain, and few query args.
     *
     * @return string
     */
    private function generateUrl() {
        // URL will be a random word from the dictionary, with a 50% chance
        // of two words.
        $words = [self::$vocabulary[array_rand(self::$vocabulary)]];
        if (mt_rand(0, 100) > 50) {
            $words[] = self::$vocabulary[array_rand(self::$vocabulary)];
        }

        $url = 'https://www.' . implode('-', $words) . '.com';

        // 50% chance that there will be some path, not root
        if (mt_rand(0, 100) > 50) {
            $path = [];
            for ($i = 0; $i < mt_rand(1, 3); $i++) {
                $path[] = self::$vocabulary[array_rand(self::$vocabulary)];
            }

            $path = implode('/', $path);

            $url = "$url/$path";
        }

        // 50% chance of query args
        if (mt_rand(0, 100) > 50) {
            $query = [];
            for ($i = 0; $i < mt_rand(1, 3); $i++) {
                $query[] = self::$vocabulary[array_rand(self::$vocabulary)] . '=' . self::$vocabulary[array_rand(self::$vocabulary)];
            }

            $query = implode('&', $query);
            $url = "$url?$query";
        }

        return $url;
    }
}
