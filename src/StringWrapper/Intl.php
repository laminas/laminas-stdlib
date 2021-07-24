<?php

declare(strict_types=1);

namespace Laminas\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception;

use function extension_loaded;
use function grapheme_strlen;
use function grapheme_strpos;
use function grapheme_substr;

class Intl extends AbstractStringWrapper
{
    /**
     * List of supported character sets (upper case)
     *
     * @var string[]
     */
    protected static $encodings = ['UTF-8'];

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
        if (! extension_loaded('intl')) {
            throw new Exception\ExtensionNotLoadedException(
                'PHP extension "intl" is required for this wrapper'
            );
        }
    }

    /**
     * Returns the length of the given string
     *
     * @return false|int
     */
    public function strlen(string $str)
    {
        $len = grapheme_strlen($str);
        return $len ?? false;
    }

    /**
     * Returns the portion of string specified by the start and length parameters
     *
     * @return string|false
     */
    public function substr(string $str, int $offset = 0, ?int $length = null)
    {
        // Due fix of PHP #62759 The third argument returns an empty string if is 0 or null.
        if ($length !== null) {
            return grapheme_substr($str, $offset, $length);
        }

        return grapheme_substr($str, $offset);
    }

    /**
     * Find the position of the first occurrence of a substring in a string
     *
     * @return int|false
     */
    public function strpos(string $haystack, string $needle, int $offset = 0)
    {
        return grapheme_strpos($haystack, $needle, $offset);
    }
}
