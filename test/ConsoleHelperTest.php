<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\ConsoleHelper;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

use function feof;
use function fopen;
use function fread;
use function rewind;

class ConsoleHelperTest extends TestCase
{
    protected function setUp() : void
    {
        $this->helper = new ConsoleHelper();
    }

    public function disableColorSupport()
    {
        $r = new ReflectionProperty($this->helper, 'supportsColor');
        $r->setAccessible(true);
        $r->setValue($this->helper, false);
    }

    public function enableColorSupport()
    {
        $r = new ReflectionProperty($this->helper, 'supportsColor');
        $r->setAccessible(true);
        $r->setValue($this->helper, true);
    }

    public function overrideEolSequence($newSequence)
    {
        $r = new ReflectionProperty($this->helper, 'eol');
        $r->setAccessible(true);
        $r->setValue($this->helper, $newSequence);
    }

    public function overrideStderrResource($stderr)
    {
        $r = new ReflectionProperty($this->helper, 'stderr');
        $r->setAccessible(true);
        $r->setValue($this->helper, $stderr);
    }

    public function retrieveStreamContents($stream)
    {
        rewind($stream);
        $contents = '';
        while (! feof($stream)) {
            $contents .= fread($stream, 4096);
        }
        return $contents;
    }

    public function testCanColorizeInfoString()
    {
        $string = '  <info>-h|--help</info>    This help message';
        $this->enableColorSupport();
        $colorized = $this->helper->colorize($string);

        self::assertEquals("  \033[32m-h|--help\033[0m    This help message", $colorized);
    }

    public function testCanColorizeErrorString()
    {
        $string = '<error>NOT OK</error> An error occurred';
        $this->enableColorSupport();
        $colorized = $this->helper->colorize($string);

        self::assertEquals("\033[31mNOT OK\033[0m An error occurred", $colorized);
    }

    public function testCanColorizeMixedStrings()
    {
        $this->enableColorSupport();
        $string = "<error>NOT OK</error>\n\n<info>Usage:</info> foo";
        $colorized = $this->helper->colorize($string);

        self::assertStringContainsString("\033[31mNOT OK\033[0m", $colorized, 'Colorized error string not found');
        self::assertStringContainsString("\033[32mUsage:\033[0m", $colorized, 'Colorized info string not found');
    }

    public function testColorizationWillReplaceTagsWithEmptyStringsWhenColorSupportIsNotDetected()
    {
        $this->disableColorSupport();
        $string = "<error>NOT OK</error>\n\n<info>Usage:</info> foo";
        $colorized = $this->helper->colorize($string);

        self::assertStringNotContainsString("\033[31m", $colorized, 'Colorized error string discovered');
        self::assertStringNotContainsString("\033[32m", $colorized, 'Colorized info string discovered');
        self::assertStringNotContainsString("\033[0m", $colorized, 'Color reset sequence discovered');
        self::assertDoesNotMatchRegularExpression("/<\/?error>/", $colorized, 'Error template string discovered');
        self::assertDoesNotMatchRegularExpression("/<\/?info>/", $colorized, 'Info template string discovered');
    }

    public function testWriteFormatsLinesToPhpEolSequenceAndWritesToProvidedStream()
    {
        $this->overrideEolSequence("\r\n");
        $string = "foo bar\nbaz bat";
        $stream = fopen('php://temp', 'w+');

        $this->helper->write($string, false, $stream);

        $contents = $this->retrieveStreamContents($stream);
        self::assertStringContainsString("\r\n", $contents);
    }

    public function testWriteWillColorizeOutputIfRequested()
    {
        $this->enableColorSupport();
        $string = 'foo <info>bar</info>';
        $stream = fopen('php://temp', 'w+');

        $this->helper->write($string, true, $stream);

        $contents = $this->retrieveStreamContents($stream);
        self::assertStringContainsString("\033[32mbar\033[0m", $contents);
    }

    public function testWriteLineAppendsPhpEolSequenceToString()
    {
        $this->overrideEolSequence("\r\n");
        $string = 'foo bar';
        $stream = fopen('php://temp', 'w+');

        $this->helper->writeLine($string, false, $stream);

        $contents = $this->retrieveStreamContents($stream);
        self::assertMatchesRegularExpression("/bar\r\n$/", $contents);
    }

    public function testWriteErrorMessageWritesColorizedOutputToStderr()
    {
        $stderr = fopen('php://temp', 'w+');
        $this->overrideStderrResource($stderr);
        $this->enableColorSupport();
        $this->overrideEolSequence("\r\n");

        $this->helper->writeErrorMessage('an error occurred');

        $contents = $this->retrieveStreamContents($stderr);
        self::assertEquals("\033[31man error occurred\033[0m\r\n\r\n", $contents);
    }
}
