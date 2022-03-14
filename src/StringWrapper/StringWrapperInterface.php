<?php

declare(strict_types=1);

namespace Laminas\Stdlib\StringWrapper;

use const STR_PAD_RIGHT;

interface StringWrapperInterface
{
    /**
     * Check if the given character encoding is supported by this wrapper
     * and the character encoding to convert to is also supported.
     */
    public static function isSupported(string $encoding, ?string $convertEncoding = null);

    /**
     * Get a list of supported character encodings
     *
     * @return string[]
     */
    public static function getSupportedEncodings();

    /**
     * Set character encoding working with and convert to
     *
     * @return StringWrapperInterface
     */
    public function setEncoding(string $encoding, ?string $convertEncoding = null);

    /**
     * Get the defined character encoding to work with (upper case)
     *
     * @return string|null
     */
    public function getEncoding();

    /**
     * Get the defined character encoding to convert to (upper case)
     *
     * @return string|null
     */
    public function getConvertEncoding();

    /**
     * Returns the length of the given string
     *
     * @return int|false
     */
    public function strlen(string $str);

    /**
     * Returns the portion of string specified by the start and length parameters
     *
     * @return string|false
     */
    public function substr(string $str, int $offset = 0, ?int $length = null);

    /**
     * Find the position of the first occurrence of a substring in a string
     *
     * @return int|false
     */
    public function strpos(string $haystack, string $needle, int $offset = 0);

    /**
     * Convert a string from defined encoding to the defined convert encoding
     *
     * @return string|false
     */
    public function convert(string $str, bool $reverse = false);

    /**
     * Wraps a string to a given number of characters
     *
     * @return string
     */
    public function wordWrap(string $str, int $width = 75, string $break = "\n", bool $cut = false);

    /**
     * Pad a string to a certain length with another string
     *
     * @return string
     */
    public function strPad(string $input, int $padLength, string $padString = ' ', int $padType = STR_PAD_RIGHT);
}
