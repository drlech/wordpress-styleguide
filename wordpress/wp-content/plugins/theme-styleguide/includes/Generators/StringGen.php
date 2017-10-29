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
     * @inheritdoc
     */
    public function generate() {
        $comment = trim($this->comment);

        // Two words 'Lorem ipsum' are a default if no text format
        // is requested in the comment.
        // They are ideal for displaying common things like button texts,
        // labels, input placeholders, etc.
        if (!$comment) {
            $comment = 'Lorem ipsum';
        }

        if ('lorem ipsum' === strtolower($comment)) {
            return 'Lorem ipsum';
        }

        return $this->generateSentence();
    }

    /**
     * Generate a random sentence.
     *
     * @return string
     */
    private function generateSentence() {
        $sentenceLength = mt_rand(5, 15);
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
        $wordsWithCommas = mt_rand(7, 18) / 10;
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
}