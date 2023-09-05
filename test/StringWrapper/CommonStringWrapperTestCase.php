<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception\InvalidArgumentException;
use Laminas\Stdlib\StringWrapper\StringWrapperInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use const STR_PAD_BOTH;
use const STR_PAD_LEFT;
use const STR_PAD_RIGHT;

// phpcs:ignore WebimpressCodingStandard.NamingConventions.AbstractClass.Prefix
abstract class CommonStringWrapperTestCase extends TestCase
{
    abstract protected function getWrapper(
        string|null $encoding = null,
        string|null $convertEncoding = null,
    ): StringWrapperInterface|false;

    /**
     * @psalm-return array<array-key, array{
     *     0: string,
     *     1: string,
     *     2: int
     * }>
     */
    public static function strlenProvider(): array
    {
        return [
            ['ascii', 'abcdefghijklmnopqrstuvwxyz', 26],
            ['utf-8', 'abcdefghijklmnopqrstuvwxyz', 26],
            ['utf-8', 'äöüß', 4],
        ];
    }

    #[DataProvider('strlenProvider')]
    public function testStrlen(string $encoding, string $str, int $expected): void
    {
        $wrapper = $this->getWrapper($encoding);
        if (! $wrapper) {
            self::markTestSkipped("Encoding {$encoding} not supported");
        }
        $result = $wrapper->strlen($str);
        self::assertSame($expected, $result);
    }

    /**
     * @psalm-return array<array-key, array{
     *     0: string,
     *     1: string,
     *     2: int,
     *     3: ?int,
     *     4: string
     * }>
     */
    public static function substrProvider(): array
    {
        return [
            ['ascii', 'abcdefghijkl', 1, 5, 'bcdef'],
            ['utf-8', 'abcdefghijkl', 1, null, 'bcdefghijkl'],
            ['utf-8', 'abcdefghijkl', 1, 5, 'bcdef'],
            ['utf-8', 'äöüß', 1, 2, 'öü'],
        ];
    }

    #[DataProvider('substrProvider')]
    public function testSubstr(string $encoding, string $str, int $offset, ?int $length, string $expected): void
    {
        $wrapper = $this->getWrapper($encoding);
        if (! $wrapper) {
            $this->markTestSkipped("Encoding {$encoding} not supported");
        }
        $result = $wrapper->substr($str, $offset, $length);
        self::assertSame($expected, $result);
    }

    /**
     * @psalm-return array<array-key, array{
     *     0: string,
     *     1: string,
     *     2: string,
     *     3: int,
     *     4: int
     * }>
     */
    public static function strposProvider(): array
    {
        return [
            ['ascii', 'abcdefghijkl', 'g', 3, 6],
            ['utf-8', 'abcdefghijkl', 'g', 3, 6],
            ['utf-8', 'äöüß', 'ü', 1, 2],
        ];
    }

    #[DataProvider('strposProvider')]
    public function testStrpos(string $encoding, string $haystack, string $needle, int $offset, int $expected): void
    {
        $wrapper = $this->getWrapper($encoding);
        if (! $wrapper) {
            $this->markTestSkipped("Encoding {$encoding} not supported");
        }
        $result = $wrapper->strpos($haystack, $needle, $offset);
        self::assertSame($expected, $result);
    }

    /**
     * @psalm-return array<array-key, array{
     *     0: string,
     *     1: string,
     *     2: string,
     *     3: string
     * }>
     */
    public static function convertProvider(): array
    {
        return [
            ['ascii', 'ascii', 'abc', 'abc'],
            ['ascii', 'utf-8', 'abc', 'abc'],
            ['utf-8', 'ascii', 'abc', 'abc'],
            ['utf-8', 'iso-8859-15', '€', "\xa4"],
            ['utf-8', 'iso-8859-16', '€', "\xa4"],
        ];
    }

    #[DataProvider('convertProvider')]
    public function testConvert(string $encoding, string $convertEncoding, string $str, string $expected): void
    {
        $wrapper = $this->getWrapper($encoding, $convertEncoding);
        if (! $wrapper) {
            $this->markTestSkipped("Encoding {$encoding} or {$convertEncoding} not supported");
        }
        $result = $wrapper->convert($str);
        self::assertSame($expected, $result);
        // backword
        $result = $wrapper->convert($expected, true);
        self::assertSame($str, $result);
    }

    /**
     * @psalm-return array<string, array{
     *     0: string,
     *     1: string,
     *     2: int,
     *     3: string,
     *     4: bool,
     *     5: string
     * }>
     */
    public static function wordWrapProvider(): array
    {
        // @codingStandardsIgnoreStart
        return [
            // Standard cut tests
            'cut-single-line' => ['utf-8', 'äbüöcß', 2, ' ', true, 'äb üö cß'],
            'cut-multi-line' => ['utf-8', 'äbüöc ß äbüöcß', 2, ' ', true, 'äb üö c ß äb üö cß'],
            'cut-multi-line-short-words' => ['utf-8', 'Ä very long wöööööööööööörd.', 8, "\n", true, "Ä very\nlong\nwööööööö\nööööörd."],
            'cut-multi-line-with-previous-new-lines' => ['utf-8', "Ä very\nlong wöööööööööööörd.", 8, "\n", false, "Ä very\nlong\nwöööööööööööörd."],
            'long-break' => ['utf-8', "Ä very<br>long wöö<br>öööööööö<br>öörd.", 8, '<br>', false, "Ä very<br>long wöö<br>öööööööö<br>öörd."],
            // Alternative cut tests
            'cut-beginning-single-space' => ['utf-8', ' äüöäöü', 3, ' ', true, ' äüö äöü'],
            'cut-ending-single-space' => ['utf-8', 'äüöäöü ', 3, ' ', true, 'äüö äöü '],
            'cut-ending-single-space-with-non-space-divider' => ['utf-8', 'äöüäöü ', 3, '-', true, 'äöü-äöü-'],
            'cut-ending-two-spaces' => ['utf-8', 'äüöäöü  ', 3, ' ', true, 'äüö äöü  '],
            'no-cut-ending-single-space' => ['utf-8', '12345 ', 5, '-', false, '12345-'],
            'no-cut-ending-two-spaces' => ['utf-8', '12345  ', 5, '-', false, '12345- '],
            'cut-ending-three-spaces' => ['utf-8', 'äüöäöü  ', 3, ' ', true, 'äüö äöü  '],
            'cut-ending-two-breaks' => ['utf-8', 'äüöäöü--', 3, '-', true, 'äüö-äöü--'],
            'cut-tab' => ['utf-8', "äbü\töcß", 3, ' ', true, "äbü \töc ß"],
            'cut-new-line-with-space' => ['utf-8', "äbü\nößt", 3, ' ', true, "äbü \nöß t"],
            'cut-new-line-with-new-line' => ['utf-8', "äbü\nößte", 3, "\n", true, "äbü\nößt\ne"],
            // Break cut tests
            'cut-break-before' => ['ascii', 'foobar-foofoofoo', 8, '-', true, 'foobar-foofoofo-o'],
            'cut-break-with' => ['ascii', 'foobar-foobar', 6, '-', true, 'foobar-foobar'],
            'cut-break-within' => ['ascii', 'foobar-foobar', 7, '-', true, 'foobar-foobar'],
            'cut-break-within-end' => ['ascii', 'foobar-', 7, '-', true, 'foobar-'],
            'cut-break-after' => ['ascii', 'foobar-foobar', 5, '-', true, 'fooba-r-fooba-r'],
            // Standard no-cut tests
            'no-cut-single-line' => ['utf-8', 'äbüöcß', 2, ' ', false, 'äbüöcß'],
            'no-cut-multi-line' => ['utf-8', 'äbüöc ß äbüöcß', 2, "\n", false, "äbüöc\nß\näbüöcß"],
            'no-cut-multi-word' => ['utf-8', 'äöü äöü äöü', 5, "\n", false, "äöü\näöü\näöü"],
            // Break no-cut tests
            'no-cut-break-before' => ['ascii', 'foobar-foofoofoo', 8, '-', false, 'foobar-foofoofoo'],
            'no-cut-break-with' => ['ascii', 'foobar-foobar', 6, '-', false, 'foobar-foobar'],
            'no-cut-break-within' => ['ascii', 'foobar-foobar', 7, '-', false, 'foobar-foobar'],
            'no-cut-break-within-end' => ['ascii', 'foobar-', 7, '-', false, 'foobar-'],
            'no-cut-break-after' => ['ascii', 'foobar-foobar', 5, '-', false, 'foobar-foobar'],
        ];
        // @codingStandardsIgnoreEnd
    }

    #[DataProvider('wordWrapProvider')]
    public function testWordWrap(
        string $encoding,
        string $string,
        int $width,
        string $break,
        bool $cut,
        mixed $expected,
    ): void {
        $wrapper = $this->getWrapper($encoding);
        if (! $wrapper) {
            $this->markTestSkipped("Encoding {$encoding} not supported");
        }
        $result = $wrapper->wordWrap($string, $width, $break, $cut);
        self::assertSame($expected, $result);
    }

    public function testWordWrapInvalidArgument(): void
    {
        $wrapper = $this->getWrapper();
        if (! $wrapper) {
            $this->fail("Can't instantiate wrapper");
        }
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Cannot force cut when width is zero");
        $wrapper->wordWrap('a', 0, "\n", true);
    }

    /**
     * @psalm-return array<string, array{
     *     0: string,
     *     1: string,
     *     2: int,
     *     3: string,
     *     4: int,
     *     5: string
     * }>
     */
    public static function strPadProvider(): array
    {
        return [
            // single-byte
            'left-padding_single-byte'   => ['ascii', 'aaa', 5, 'o', STR_PAD_LEFT, 'ooaaa'],
            'center-padding_single-byte' => ['ascii', 'aaa', 6, 'o', STR_PAD_BOTH, 'oaaaoo'],
            'right-padding_single-byte'  => ['ascii', 'aaa', 5, 'o', STR_PAD_RIGHT, 'aaaoo'],
            // multi-byte
            'left-padding_multi-byte'   => ['utf-8', 'äää', 5, 'ö', STR_PAD_LEFT, 'ööäää'],
            'center-padding_multi-byte' => ['utf-8', 'äää', 6, 'ö', STR_PAD_BOTH, 'öäääöö'],
            'right-padding_multi-byte'  => ['utf-8', 'äää', 5, 'ö', STR_PAD_RIGHT, 'äääöö'],
            // Laminas-12186
            'input-longer-than-pad-length' => ['utf-8', 'äääöö', 2, 'ö', STR_PAD_RIGHT, 'äääöö'],
            'input-same-as-pad-length'     => ['utf-8', 'äääöö', 5, 'ö', STR_PAD_RIGHT, 'äääöö'],
            'negative-pad-length'          => ['utf-8', 'äääöö', -2, 'ö', STR_PAD_RIGHT, 'äääöö'],
        ];
    }

    #[Group('Laminas-12186')]
    #[DataProvider('strPadProvider')]
    public function testStrPad(
        string $encoding,
        string $input,
        int $padLength,
        string $padString,
        int $padType,
        mixed $expected,
    ): void {
        $wrapper = $this->getWrapper($encoding);
        if (! $wrapper) {
            $this->markTestSkipped("Encoding {$encoding} not supported");
        }
        $result = $wrapper->strPad($input, $padLength, $padString, $padType);
        self::assertSame($expected, $result);
    }
}
