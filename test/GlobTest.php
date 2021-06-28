<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\Exception\RuntimeException;
use Laminas\Stdlib\Glob;
use PHPUnit\Framework\TestCase;

use function count;
use function defined;
use function glob;
use function str_repeat;

use const GLOB_BRACE;

class GlobTest extends TestCase
{
    public function testFallback()
    {
        if (! defined('GLOB_BRACE')) {
            $this->markTestSkipped('GLOB_BRACE not available');
        }

        self::assertEquals(
            glob(__DIR__ . '/_files/{alph,bet}a', GLOB_BRACE),
            Glob::glob(__DIR__ . '/_files/{alph,bet}a', Glob::GLOB_BRACE, true)
        );
    }

    public function testNonMatchingGlobReturnsArray()
    {
        $result = Glob::glob('/some/path/{,*.}{this,orthis}.php', Glob::GLOB_BRACE);
        self::assertIsArray($result);
    }

    public function testThrowExceptionOnError()
    {
        $this->expectException(RuntimeException::class);

        // run into a max path length error
        $path = '/' . str_repeat('a', 10000);
        Glob::glob($path);
    }

    /**
     * @dataProvider patternsProvider
     */
    public function testPatterns(string $pattern, array $expectedSequence)
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
     *     1: string[]
     * }>
     */
    public function patternsProvider(): array
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
}
