<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Exception;
use Laminas\Stdlib\ErrorHandler;
use Laminas\Stdlib\StringUtils;
use PHPUnit\Framework\TestCase;

use function defined;
use function extension_loaded;
use function preg_match;

class StringUtilsTest extends TestCase
{
    protected function tearDown() : void
    {
        StringUtils::resetRegisteredWrappers();
    }

    public function getSingleByEncodings()
    {
        return [
            // case-mix to check case-insensitivity
            ['AscII'],
            ['7bIt'],
            ['8Bit'],
            ['ISo-8859-1'],
            ['ISo-8859-2'],
            ['ISo-8859-3'],
            ['ISo-8859-4'],
            ['ISo-8859-5'],
            ['ISo-8859-6'],
            ['ISo-8859-7'],
            ['ISo-8859-8'],
            ['ISo-8859-9'],
            ['ISo-8859-10'],
            ['ISo-8859-11'],
            ['ISo-8859-13'],
            ['ISo-8859-14'],
            ['ISo-8859-15'],
            ['ISo-8859-16'],
        ];
    }

    /**
     * @dataProvider getSingleByEncodings
     * @param string $encoding
     */
    public function testIsSingleByteEncodingReturnsTrue($encoding): void
    {
        self::assertTrue(StringUtils::isSingleByteEncoding($encoding));
    }

    public function getNonSingleByteEncodings()
    {
        return [
            ['UTf-8'],
            ['UTf-16'],
            ['usC-2'],
            ['CESU-8'],
        ];
    }

    /**
     * @dataProvider getNonSingleByteEncodings
     * @param string $encoding
     */
    public function testIsSingleByteEncodingReturnsFalse($encoding): void
    {
        self::assertFalse(StringUtils::isSingleByteEncoding($encoding));
    }

    public function testGetWrapper(): void
    {
        $wrapper = StringUtils::getWrapper('ISO-8859-1');
        if (extension_loaded('mbstring')) {
            self::assertInstanceOf('Laminas\Stdlib\StringWrapper\MbString', $wrapper);
        } elseif (extension_loaded('iconv')) {
            self::assertInstanceOf('Laminas\Stdlib\StringWrapper\Iconv', $wrapper);
        } else {
            self::assertInstanceOf('Laminas\Stdlib\StringWrapper\Native', $wrapper);
        }

        try {
            $wrapper = StringUtils::getWrapper('UTF-8');
            if (extension_loaded('intl')) {
                self::assertInstanceOf('Laminas\Stdlib\StringWrapper\Intl', $wrapper);
            } elseif (extension_loaded('mbstring')) {
                self::assertInstanceOf('Laminas\Stdlib\StringWrapper\MbString', $wrapper);
            } elseif (extension_loaded('iconv')) {
                self::assertInstanceOf('Laminas\Stdlib\StringWrapper\Iconv', $wrapper);
            }
        } catch (Exception $e) {
            if (extension_loaded('intl')
                || extension_loaded('mbstring')
                || extension_loaded('iconv')
            ) {
                $this->fail("Failed to get intl, mbstring or iconv wrapper for UTF-8");
            }
        }

        try {
            $wrapper = StringUtils::getWrapper('UTF-8', 'ISO-8859-1');
            if (extension_loaded('mbstring')) {
                self::assertInstanceOf('Laminas\Stdlib\StringWrapper\MbString', $wrapper);
            } elseif (extension_loaded('iconv')) {
                self::assertInstanceOf('Laminas\Stdlib\StringWrapper\Iconv', $wrapper);
            }
        } catch (Exception $e) {
            if (extension_loaded('mbstring') || extension_loaded('iconv')) {
                $this->fail("Failed to get mbstring or iconv wrapper for UTF-8 and ISO-8859-1");
            }
        }
    }

    public function getUtf8StringValidity()
    {
        return [
            // valid
            ['', true],
            ["\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0A\x0B\x0C\x0D\x0E\x0F"
                . "\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1A\x1B\x1C\x1D\x1E\x1F"
                . ' !"#$%&\'()*+,-./0123456789:;<=>?'
                . '@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_'
                . '`abcdefghijklmnopqrstuvwxyz{|}~',
                true
            ],

            // invalid
            [true, false],
            [123, false],
            [123.45, false],
            ["\xFF", false],
            ["\x90a", false],
        ];
    }

    /**
     * @dataProvider getUtf8StringValidity
     * @param string $str
     * @param bool $valid
     */
    public function testIsValidUtf8($str, $valid): void
    {
        self::assertSame($valid, StringUtils::isValidUtf8($str));
    }

    public function testHasPcreUnicodeSupport(): void
    {
        ErrorHandler::start();
        $expected = defined('PREG_BAD_UTF8_OFFSET_ERROR') && preg_match('/\pL/u', 'a') == 1;
        ErrorHandler::stop();

        self::assertSame($expected, StringUtils::hasPcreUnicodeSupport());
    }
}
