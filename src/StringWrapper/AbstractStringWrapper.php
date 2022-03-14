<?php

declare(strict_types=1);

namespace Laminas\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception;
use Laminas\Stdlib\StringUtils;

use function floor;
use function in_array;
use function sprintf;
use function str_pad;
use function str_repeat;
use function strtoupper;
use function wordwrap;

use const STR_PAD_BOTH;
use const STR_PAD_LEFT;
use const STR_PAD_RIGHT;

abstract class AbstractStringWrapper implements StringWrapperInterface
{
    /**
     * The character encoding working on
     *
     * @var string|null
     */
    protected $encoding = 'UTF-8';

    /**
     * An optionally character encoding to convert to
     *
     * @var string|null
     */
    protected $convertEncoding;

    /**
     * Check if the given character encoding is supported by this wrapper
     * and the character encoding to convert to is also supported.
     *
     * @return bool
     */
    public static function isSupported(string $encoding, ?string $convertEncoding = null)
    {
        $supportedEncodings = static::getSupportedEncodings();

        if (! in_array(strtoupper($encoding), $supportedEncodings)) {
            return false;
        }

        if ($convertEncoding !== null && ! in_array(strtoupper($convertEncoding), $supportedEncodings)) {
            return false;
        }

        return true;
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

        if ($convertEncoding !== null) {
            $convertEncodingUpper = strtoupper($convertEncoding);
            if (! in_array($convertEncodingUpper, $supportedEncodings)) {
                throw new Exception\InvalidArgumentException(
                    'Wrapper doesn\'t support character encoding "' . $convertEncoding . '"'
                );
            }

            $this->convertEncoding = $convertEncodingUpper;
        } else {
            $this->convertEncoding = null;
        }
        $this->encoding = $encodingUpper;

        return $this;
    }

    /**
     * Get the defined character encoding to work with
     *
     * @return null|string
     * @throws Exception\LogicException If no encoding was defined.
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Get the defined character encoding to convert to
     *
     * @return string|null
     */
    public function getConvertEncoding()
    {
        return $this->convertEncoding;
    }

    /**
     * Convert a string from defined character encoding to the defined convert encoding
     *
     * @return string|false
     */
    public function convert(string $str, bool $reverse = false)
    {
        $encoding        = $this->getEncoding();
        $convertEncoding = $this->getConvertEncoding();
        if ($convertEncoding === null) {
            throw new Exception\LogicException(
                'No convert encoding defined'
            );
        }

        if ($encoding === $convertEncoding) {
            return $str;
        }

        $from = $reverse ? $convertEncoding : $encoding;
        $to   = $reverse ? $encoding : $convertEncoding;
        throw new Exception\RuntimeException(sprintf(
            'Converting from "%s" to "%s" isn\'t supported by this string wrapper',
            $from ?? '',
            $to ?? ''
        ));
    }

    /**
     * Wraps a string to a given number of characters
     *
     * @return string|false
     */
    public function wordWrap(string $string, int $width = 75, string $break = "\n", bool $cut = false)
    {
        if ($string === '') {
            return '';
        }

        if ($break === '') {
            throw new Exception\InvalidArgumentException('Break string cannot be empty');
        }

        if ($width === 0 && $cut) {
            throw new Exception\InvalidArgumentException('Cannot force cut when width is zero');
        }

        if (null === $this->getEncoding() || StringUtils::isSingleByteEncoding($this->getEncoding())) {
            return wordwrap($string, $width, $break, $cut);
        }

        $stringWidth = $this->strlen($string);
        $breakWidth  = $this->strlen($break);

        $result    = '';
        $lastStart = $lastSpace = 0;

        for ($current = 0; $current < $stringWidth; $current++) {
            $char = $this->substr($string, $current, 1);

            $possibleBreak = $char;
            if ($breakWidth !== 1) {
                $possibleBreak = $this->substr($string, $current, $breakWidth);
            }

            if ($possibleBreak === $break) {
                $result   .= $this->substr($string, $lastStart, $current - $lastStart + $breakWidth);
                $current  += $breakWidth - 1;
                $lastStart = $lastSpace = $current + 1;
                continue;
            }

            if ($char === ' ') {
                if ($current - $lastStart >= $width) {
                    $result   .= $this->substr($string, $lastStart, $current - $lastStart) . $break;
                    $lastStart = $current + 1;
                }

                $lastSpace = $current;
                continue;
            }

            if ($current - $lastStart >= $width && $cut && $lastStart >= $lastSpace) {
                $result   .= $this->substr($string, $lastStart, $current - $lastStart) . $break;
                $lastStart = $lastSpace = $current;
                continue;
            }

            if ($current - $lastStart >= $width && $lastStart < $lastSpace) {
                $result   .= $this->substr($string, $lastStart, $lastSpace - $lastStart) . $break;
                $lastStart = $lastSpace += 1;
                continue;
            }
        }

        if ($lastStart !== $current) {
            $result .= $this->substr($string, $lastStart, $current - $lastStart);
        }

        return $result;
    }

    /**
     * Pad a string to a certain length with another string
     *
     * @return string
     */
    public function strPad(string $input, int $padLength, string $padString = ' ', int $padType = STR_PAD_RIGHT)
    {
        if (null === $this->getEncoding() || StringUtils::isSingleByteEncoding($this->getEncoding())) {
            return str_pad($input, $padLength, $padString, $padType);
        }

        $lengthOfPadding = $padLength - $this->strlen($input);
        if ($lengthOfPadding <= 0) {
            return $input;
        }

        $padStringLength = $this->strlen($padString);
        if ($padStringLength === 0) {
            return $input;
        }

        $repeatCount = (int) floor($lengthOfPadding / $padStringLength);

        if ($padType === STR_PAD_BOTH) {
            $repeatCountLeft = $repeatCountRight = (int) ($repeatCount - $repeatCount % 2) / 2;

            $lastStringLength       = $lengthOfPadding - 2 * $repeatCountLeft * $padStringLength;
            $lastStringLeftLength   = $lastStringRightLength = (int) floor($lastStringLength / 2);
            $lastStringRightLength += $lastStringLength % 2;

            $lastStringLeft  = $this->substr($padString, 0, $lastStringLeftLength);
            $lastStringRight = $this->substr($padString, 0, $lastStringRightLength);

            return str_repeat($padString, $repeatCountLeft) . $lastStringLeft
                . $input
                . str_repeat($padString, $repeatCountRight) . $lastStringRight;
        }

        $lastString = $this->substr($padString, 0, $lengthOfPadding % $padStringLength);

        if ($padType === STR_PAD_LEFT) {
            return str_repeat($padString, $repeatCount) . $lastString . $input;
        }

        return $input . str_repeat($padString, $repeatCount) . $lastString;
    }
}
