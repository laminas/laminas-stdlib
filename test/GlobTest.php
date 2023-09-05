<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\Exception\RuntimeException;
use Laminas\Stdlib\Glob;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function count;
use function defined;
use function glob;
use function realpath;
use function str_repeat;

use const GLOB_BRACE;

class GlobTest extends TestCase
{
    public function testFallback(): void
    {
        if (! defined('GLOB_BRACE')) {
            $this->markTestSkipped('GLOB_BRACE not available');
        }

        $expected = glob(__DIR__ . '/_files/{alph,bet}a', GLOB_BRACE);
        $actual   = Glob::glob(
            __DIR__ . '/_files/{alph,bet}a',
            Glob::GLOB_BRACE,
            true
        );

        self::assertEquals($actual, $expected);

        $notExpectedPath = realpath(__DIR__ . '/_files/{alph,bet}a');

        self::assertNotContains(
            $notExpectedPath,
            $actual
        );
    }

    public function testNonMatchingGlobReturnsArray(): void
    {
        $result = Glob::glob(
            '/some/path/{,*.}{this,orthis}.php',
            Glob::GLOB_BRACE
        );
        self::assertIsArray($result);
    }

    public function testThrowExceptionOnError(): void
    {
        $this->expectException(RuntimeException::class);

        // run into a max path length error
        $path = '/' . str_repeat('a', 10000);
        Glob::glob($path);
    }

    /** @param list<non-empty-string> $expectedSequence */
    #[DataProvider('patternsProvider')]
    public function testPatterns(string $pattern, array $expectedSequence): void
    {
        $result = Glob::glob(__DIR__ . '/_files/' . $pattern, Glob::GLOB_BRACE);

        self::assertCount(count($expectedSequence), $result);

        foreach ($expectedSequence as $i => $expectedFileName) {
            self::assertStringEndsWith($expectedFileName, $result[$i]);
        }
    }

    /**
     * @psalm-return array<array-key, array{
     *     0: string,
     *     1: list<non-empty-string>
     * }>
     */
    public static function patternsProvider(): array
    {
        return [
            [
                "{{,*.}alph,{,*.}bet}a",
                [
                    'alpha',
                    'eta.alpha',
                    'zeta.alpha',
                    'beta',
                    'eta.beta',
                    'zeta.beta',
                ],
            ],
        ];
    }

    public function testGlobWithoutGlobBraceFlag(): void
    {
        $expected = [
            realpath(__DIR__ . '/_files/{alph,bet}a'),
        ];

        self::assertEquals(
            glob(__DIR__ . '/_files/{alph,bet}a', 0),
            $expected
        );
    }

    /**
     * @psalm-return array<array-key, array{
     *     int,
     *     int,
     *     bool
     * }>
     */
    public static function flagsIsEqualsToMethodDataProvider(): array
    {
        return [
            [
                Glob::GLOB_BRACE,
                Glob::GLOB_BRACE,
                true,
            ],
            [
                Glob::GLOB_BRACE,
                Glob::GLOB_NOSORT,
                false,
            ],
        ];
    }

    #[DataProvider('flagsIsEqualsToMethodDataProvider')]
    public function testFlagsIsEqualsToMethod(
        int $flags,
        int $otherFlags,
        bool $expected
    ): void {
        /**
         * @psalm-suppress InternalMethod this test is specifically testing the behavior of this method,
         *                                to prevent regressions
         */
        $actual = Glob::flagsIsEqualTo($flags, $otherFlags);

        $this->assertEquals($expected, $actual);
    }
}
