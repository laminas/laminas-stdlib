<?php // phpcs:disable WebimpressCodingStandard.PHP.CorrectClassNameCase.Invalid,SlevomatCodingStandard.TypeHints.DeclareStrictTypes.IncorrectWhitespaceBeforeDeclare

declare(strict_types=1);

namespace LaminasTest\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception;
use Laminas\Stdlib\StringWrapper\StringWrapperInterface;
use PHPUnit\Framework\TestCase;

use const STR_PAD_BOTH;
use const STR_PAD_LEFT;
use const STR_PAD_RIGHT;

// phpcs:ignore WebimpressCodingStandard.NamingConventions.AbstractClass.Prefix
abstract class CommonStringWrapperTest extends TestCase
{
    /**
     * @param null|string $encoding
     * @param null|string $convertEncoding
     * @return false|StringWrapperInterface
     */
    abstract protected function getWrapper($encoding = null, $convertEncoding = null);

    /**
     * @psalm-return array<array-key, array{
     *     0: string,
     *     1: string,
     *     2: int
     * }>
     */
    public function strlenProvider(): array
    {
        return [
            ['ascii', 'abcdefghijklmnopqrstuvwxyz', 26],
            ['utf-8', 'abcdefghijklmnopqrstuvwxyz', 26],
            ['utf-8', 'äöüß',                       4],
        ];
    }

    /**
     * @dataProvider strlenProvider
     */
    public function testStrlen(string $encoding, string $str, int $expected): void
    {
        $wrapper = $this->getWrapper($encoding);
        if (! $wrapper) {
            $this->markTestSkipped("Encoding {$encoding} not supported");
        }

        $result = $wrapper->strlen($str);
        self::assertSame($expected, $result);
    }

    /**
     * @psalm-return array<array-key, array{
     *     0: string,
     *     1: string,
     *     2: int,
     *     3: int,
     *     4: string
     * }>
     */
    public function substrProvider(): array
    {
        return [
            ['ascii', 'abcdefghijkl', 1, 5, 'bcdef'],
            ['utf-8', 'abcdefghijkl', 1, 5, 'bcdef'],
            ['utf-8', 'äöüß',         1, 2, 'öü'],
        ];
    }

    /**
     * @dataProvider substrProvider
     */
    public function testSubstr(string $encoding, string $str, int $offset, int $length, string $expected): void
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
    public function strposProvider(): array
    {
        return [
            ['ascii', 'abcdefghijkl', 'g', 3, 6],
            ['utf-8', 'abcdefghijkl', 'g', 3, 6],
            ['utf-8', 'äöüß',         'ü', 1, 2],
        ];
    }

    /**
     * @dataProvider strposProvider
     */
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
    public function convertProvider(): array
    {
        return [
            ['ascii', 'ascii', 'abc', 'abc'],
            ['ascii', 'utf-8', 'abc', 'abc'],
            ['utf-8', 'ascii', 'abc', 'abc'],
            ['utf-8', 'iso-8859-15', '€', "\xA4"],
            ['utf-8', 'iso-8859-16', '€', "\xA4"], // ISO-8859-16 is wrong @ mbstring
        ];
    }

    /**
     * @dataProvider convertProvider
     * @param string $str
     * @param string $encoding
     * @param string $convertEncoding
     * @param mixed  $expected
     */
    public function testConvert($encoding, $convertEncoding, $str, $expected): void
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
    public function wordWrapProvider(): array
    {
        // phpcs:disable Generic.Files.LineLength.TooLong
        return [
            // Standard cut tests
            'cut-single-line'                        => ['utf-8', 'äbüöcß', 2, ' ', true, 'äb üö cß'],
            'cut-multi-line'                         => ['utf-8', 'äbüöc ß äbüöcß', 2, ' ', true, 'äb üö c ß äb üö cß'],
            'cut-multi-line-short-words'             => ['utf-8', 'Ä very long wöööööööööööörd.', 8, "\n", true, "Ä very\nlong\nwööööööö\nööööörd."],
            'cut-multi-line-with-previous-new-lines' => ['utf-8', "Ä very\nlong wöööööööööööörd.", 8, "\n", false, "Ä very\nlong\nwöööööööööööörd."],
            'long-break'                             => ['utf-8', "Ä very<br>long wöö<br>öööööööö<br>öörd.", 8, '<br>', false, "Ä very<br>long wöö<br>öööööööö<br>öörd."],

            // Alternative cut tests
            'cut-beginning-single-space'                     => ['utf-8', ' äüöäöü', 3, ' ', true, ' äüö äöü'],
            'cut-ending-single-space'                        => ['utf-8', 'äüöäöü ', 3, ' ', true, 'äüö äöü '],
            'cut-ending-single-space-with-non-space-divider' => ['utf-8', 'äöüäöü ', 3, '-', true, 'äöü-äöü-'],
            'cut-ending-two-spaces'                          => ['utf-8', 'äüöäöü  ', 3, ' ', true, 'äüö äöü  '],
            'no-cut-ending-single-space'                     => ['utf-8', '12345 ', 5, '-', false, '12345-'],
            'no-cut-ending-two-spaces'                       => ['utf-8', '12345  ', 5, '-', false, '12345- '],
            'cut-ending-three-spaces'                        => ['utf-8', 'äüöäöü  ', 3, ' ', true, 'äüö äöü  '],
            'cut-ending-two-breaks'                          => ['utf-8', 'äüöäöü--', 3, '-', true, 'äüö-äöü--'],
            'cut-tab'                                        => ['utf-8', "äbü\töcß", 3, ' ', true, "äbü \töc ß"],
            'cut-new-line-with-space'                        => ['utf-8', "äbü\nößt", 3, ' ', true, "äbü \nöß t"],
            'cut-new-line-with-new-line'                     => ['utf-8', "äbü\nößte", 3, "\n", true, "äbü\nößt\ne"],

            // Break cut tests
            'cut-break-before'     => ['ascii', 'foobar-foofoofoo', 8, '-', true, 'foobar-foofoofo-o'],
            'cut-break-with'       => ['ascii', 'foobar-foobar', 6, '-', true, 'foobar-foobar'],
            'cut-break-within'     => ['ascii', 'foobar-foobar', 7, '-', true, 'foobar-foobar'],
            'cut-break-within-end' => ['ascii', 'foobar-', 7, '-', true, 'foobar-'],
            'cut-break-after'      => ['ascii', 'foobar-foobar', 5, '-', true, 'fooba-r-fooba-r'],

            // Standard no-cut tests
            'no-cut-single-line' => ['utf-8', 'äbüöcß', 2, ' ', false, 'äbüöcß'],
            'no-cut-multi-line'  => ['utf-8', 'äbüöc ß äbüöcß', 2, "\n", false, "äbüöc\nß\näbüöcß"],
            'no-cut-multi-word'  => ['utf-8', 'äöü äöü äöü', 5, "\n", false, "äöü\näöü\näöü"],

            // Break no-cut tests
            'no-cut-break-before'     => ['ascii', 'foobar-foofoofoo', 8, '-', false, 'foobar-foofoofoo'],
            'no-cut-break-with'       => ['ascii', 'foobar-foobar', 6, '-', false, 'foobar-foobar'],
            'no-cut-break-within'     => ['ascii', 'foobar-foobar', 7, '-', false, 'foobar-foobar'],
            'no-cut-break-within-end' => ['ascii', 'foobar-', 7, '-', false, 'foobar-'],
            'no-cut-break-after'      => ['ascii', 'foobar-foobar', 5, '-', false, 'foobar-foobar'],
        ];
        // phpcs:enable
    }

    /**
     * @dataProvider wordWrapProvider
     * @param string $encoding
     * @param string $string
     * @param int    $width
     * @param string $break
     * @param bool   $cut
     * @param mixed  $expected
     */
    public function testWordWrap($encoding, $string, $width, $break, $cut, $expected): void
    {
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
        $this->expectException(Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "Cannot force cut when width is zero"
        );
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
    public function strPadProvider(): array
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

    /**
     * @dataProvider strPadProvider
     * @param string $encoding
     * @param string $input
     * @param int    $padLength
     * @param string $padString
     * @param int    $padType
     * @param mixed  $expected
     * @group Laminas-12186
     */
    public function testStrPad($encoding, $input, $padLength, $padString, $padType, $expected): void
    {
        $wrapper = $this->getWrapper($encoding);
        if (! $wrapper) {
            $this->markTestSkipped("Encoding {$encoding} not supported");
        }

        $result = $wrapper->strPad($input, $padLength, $padString, $padType);
        self::assertSame($expected, $result);
    }
}
