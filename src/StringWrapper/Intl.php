<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception;

class Intl extends AbstractStringWrapper
{
    /**
     * List of supported character sets (upper case)
     *
     * @var string[]
     */
    protected static $encodings = array('UTF-8');

    /**
     * Get a list of supported character encodings
     *
     * @return string[]
     */
    public static function getSupportedEncodings()
    {
        return static::$encodings;
    }

    /**
     * Constructor
     *
     * @throws Exception\ExtensionNotLoadedException
     */
    public function __construct()
    {
        if (!extension_loaded('intl')) {
            throw new Exception\ExtensionNotLoadedException(
                'PHP extension "intl" is required for this wrapper'
            );
        }
    }

    /**
     * Returns the length of the given string
     *
     * @param string $str
     * @param string $encoding
     * @return int|false
     */
    public function strlen($str)
    {
        return grapheme_strlen($str);
    }

    /**
     * Returns the portion of string specified by the start and length parameters
     *
     * @param string   $str
     * @param int      $offset
     * @param int|null $length
     * @param string   $encoding
     * @return string|false
     */
    public function substr($str, $offset = 0, $length = null)
    {
        return grapheme_substr($str, $offset, $length);
    }

    /**
     * Find the position of the first occurrence of a substring in a string
     *
     * @param string $haystack
     * @param string $needle
     * @param int    $offset
     * @param string $encoding
     * @return int|false
     */
    public function strpos($haystack, $needle, $offset = 0)
    {
        return grapheme_strpos($haystack, $needle, $offset);
    }
}
