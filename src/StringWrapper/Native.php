<?php

declare(strict_types=1);

namespace Laminas\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception;
use Laminas\Stdlib\StringUtils;

use function in_array;
use function strlen;
use function strpos;
use function strtoupper;
use function substr;

class Native extends AbstractStringWrapper
{
    /**
     * The character encoding working on
     * (overwritten to change default encoding)
     *
     * @var string
     */
    protected $encoding = 'ASCII';

    /**
     * Check if the given character encoding is supported by this wrapper
     * and the character encoding to convert to is also supported.
     *
     * @return bool
     */
    public static function isSupported(string $encoding, ?string $convertEncoding = null)
    {
        $encodingUpper      = strtoupper($encoding);
        $supportedEncodings = static::getSupportedEncodings();

        if (! in_array($encodingUpper, $supportedEncodings)) {
            return false;
        }

        // This adapter doesn't support to convert between encodings
        if ($convertEncoding !== null && $encodingUpper !== strtoupper($convertEncoding)) {
            return false;
        }

        return true;
    }

    /**
     * Get a list of supported character encodings
     *
     * @return string[]
     */
    public static function getSupportedEncodings()
    {
        return StringUtils::getSingleByteEncodings();
    }

    /**
     * Set character encoding working with and convert to
     *
     * @return StringWrapperInterface
     */
    public function setEncoding(string $encoding, ?string $convertEncoding = null)
    {
        $supportedEncodings = static::getSupportedEncodings();

        $encodingUpper = strtoupper($encoding);
        if (! in_array($encodingUpper, $supportedEncodings)) {
            throw new Exception\InvalidArgumentException(
                'Wrapper doesn\'t support character encoding "' . $encoding . '"'
            );
        }

        if (null !== $convertEncoding && $encodingUpper !== strtoupper($convertEncoding)) {
            $this->convertEncoding = $encodingUpper;
        }

        if ($convertEncoding !== null) {
            if ($encodingUpper !== strtoupper($convertEncoding)) {
                throw new Exception\InvalidArgumentException(
                    'Wrapper doesn\'t support to convert between character encodings'
                );
            }

            $this->convertEncoding = $encodingUpper;
        } else {
            $this->convertEncoding = null;
        }
        $this->encoding = $encodingUpper;

        return $this;
    }

    /**
     * Returns the length of the given string
     *
     * @return int|false
     */
    public function strlen(string $str)
    {
        return strlen($str);
    }

    /**
     * Returns the portion of string specified by the start and length parameters
     *
     * @return string|false
     */
    public function substr(string $str, int $offset = 0, ?int $length = null)
    {
        return substr($str, $offset, $length);
    }

    /**
     * Find the position of the first occurrence of a substring in a string
     *
     * @return int|false
     */
    public function strpos(string $haystack, string $needle, int $offset = 0)
    {
        return strpos($haystack, $needle, $offset);
    }
}
