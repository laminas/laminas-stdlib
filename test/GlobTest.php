<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

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
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

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
     * @param string $pattern
     * @param string[] $expectedSequence
     * @param int $flags
     * @param int $systemFlags
     *
     * @dataProvider patternsProvider
     */
    public function testFallbackResultsIsSameOfSystemGlob(
        string $pattern,
        array $expectedSequence,
        int $flags = 0,
        int $systemFlags = 0
    ) {

        if (($flags & Glob::GLOB_BRACE) && ! defined('GLOB_BRACE')) {
            $this->markTestSkipped('GLOB_BRACE not available');
        }

        $result = Glob::glob(__DIR__ . '/_files/' . $pattern, $flags, true);
        $systemResult = glob(__DIR__ . '/_files/' . $pattern, $systemFlags);

        self::assertTrue($this->pathsAreInFileChunks($expectedSequence, $result));
        self::assertTrue($this->pathsAreInFileChunks($expectedSequence, $systemResult));
    }

    /**
     * @param string $pattern
     * @param string[][] $expectedSequence
     * @param int $flags
     *
     * @dataProvider patternsProvider
     */
    public function testPatternsWithFallback(string $pattern, array $expectedSequence, int $flags = 0)
    {
        $result = Glob::glob(__DIR__ . '/_files/' . $pattern, $flags, true);

        self::assertTrue($this->pathsAreInFileChunks($expectedSequence, $result));
    }

    public function patternsProvider(): array
    {
        return [
            'GLOB_BRACE' => [
                "{{,*.}alph,{,*.}bet}a",
                [
                    'alpha', 'beta', 'eta.alpha', 'eta.beta', 'zeta.alpha', 'zeta.beta',
                ],
                Glob::GLOB_BRACE,
                defined('GLOB_BRACE') ? GLOB_BRACE : 0,
            ],
            'GLOB_BRACE | GLOB_NOSORT' => [
                "{{,*.}alph,{,*.}bet}a",
                [
                    ['alpha', 'eta.alpha', 'zeta.alpha'],
                    ['beta', 'eta.beta', 'zeta.beta'],
                ],
                Glob::GLOB_BRACE | Glob::GLOB_NOSORT,
                (defined('GLOB_BRACE') ? GLOB_BRACE : 0) | GLOB_NOSORT,
            ],
        ];
    }

    /**
     * Compare files using chunks of files to avoid comparing the order of chunk values.
     * Passing a flat array of files will compare it using the same order.
     *
     * @param string[]|string[][] $chunks Array of chunks or array of files
     * @param string[] $paths
     * @return bool
     */
    private function pathsAreInFileChunks(array $chunks, array $paths): bool
    {
        $files = array_map('basename', $paths);

        foreach ($chunks as $chunkFiles) {
            if (is_string($chunkFiles)) {
                if ($chunkFiles !== array_shift($files)) {
                    return false;
                }
                continue;
            }
            $pathsToCompare = array_slice($files, 0, count($chunkFiles));
            $files = array_slice($files, count($chunkFiles));
            if (count(array_intersect($pathsToCompare, $chunkFiles)) !== count($chunkFiles)) {
                return false;
            }
        }

        return 0 === count($files);
    }
}
