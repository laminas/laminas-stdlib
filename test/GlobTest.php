<?php

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\Glob;
use PHPUnit_Framework_TestCase as TestCase;

class GlobTest extends TestCase
{
    public function testFallback()
    {
        if (!defined('GLOB_BRACE')) {
            $this->markTestSkipped('GLOB_BRACE not available');
        }

        $this->assertEquals(
            glob(__DIR__ . '/_files/{alph,bet}a', GLOB_BRACE),
            Glob::glob(__DIR__ . '/_files/{alph,bet}a', Glob::GLOB_BRACE, true)
        );
    }

    public function testNonMatchingGlobReturnsArray()
    {
        $result = Glob::glob('/some/path/{,*.}{this,orthis}.php', Glob::GLOB_BRACE);
        $this->assertInternalType('array', $result);
    }

    public function testThrowExceptionOnError()
    {
        $this->setExpectedException('Laminas\Stdlib\Exception\RuntimeException');

        // run into a max path lengh error
        $path = '/' . str_repeat('a', 10000);
        Glob::glob($path);
    }

    /**
     * @param string $pattern
     *
     * @dataProvider patternsProvider
     */
    public function testPatterns($pattern, $expectedSequence)
    {
        $result = Glob::glob(__DIR__ . '/_files/' . $pattern, Glob::GLOB_BRACE);

        $this->assertCount(count($expectedSequence), $result);

        foreach ($expectedSequence as $i => $expectedFileName) {
            $this->assertStringEndsWith($expectedFileName, $result[$i]);
        }
    }

    public function patternsProvider()
    {
        return [
            [
                "{{,*.}alph,{,*.}bet}a",
                [
                    'alpha', 'eta.alpha', 'zeta.alpha', 'beta', 'eta.beta',
                    'zeta.beta'
                ]
            ]
        ];
    }
}
