<?php // phpcs:disable WebimpressCodingStandard.PHP.CorrectClassNameCase.Invalid,SlevomatCodingStandard.TypeHints.DeclareStrictTypes.IncorrectWhitespaceBeforeDeclare

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\SplStack;
use PHPUnit\Framework\TestCase;

use function count;
use function iterator_to_array;
use function serialize;
use function unserialize;
use function var_export;

/**
 * @group      Laminas_Stdlib
 */
class SplStackTest extends TestCase
{
    /** @var SplStack */
    protected $stack;

    protected function setUp(): void
    {
        $this->stack = new SplStack();
        $this->stack->push('foo');
        $this->stack->push('bar');
        $this->stack->push('baz');
        $this->stack->push('bat');
    }

    public function testSerializationAndDeserializationShouldMaintainState(): void
    {
        $s            = serialize($this->stack);
        $unserialized = unserialize($s);
        $count        = count($this->stack);
        self::assertSame($count, count($unserialized));

        $expected = iterator_to_array($this->stack);
        $test     = iterator_to_array($unserialized);
        self::assertSame($expected, $test);
    }

    public function testCanRetrieveQueueAsArray(): void
    {
        $expected = ['bat', 'baz', 'bar', 'foo'];
        $test     = $this->stack->toArray();
        self::assertSame($expected, $test, var_export($test, true));
    }
}
