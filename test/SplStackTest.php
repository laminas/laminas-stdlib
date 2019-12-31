<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\SplStack;

/**
 * @category   Laminas
 * @package    Laminas_Stdlib
 * @subpackage UnitTests
 * @group      Laminas_Stdlib
 */
class SplStackTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->stack = new SplStack();
        $this->stack->push('foo');
        $this->stack->push('bar');
        $this->stack->push('baz');
        $this->stack->push('bat');
    }

    public function testSerializationAndDeserializationShouldMaintainState()
    {
        $s = serialize($this->stack);
        $unserialized = unserialize($s);
        $count = count($this->stack);
        $this->assertSame($count, count($unserialized));

        $expected = array();
        foreach ($this->stack as $item) {
            $expected[] = $item;
        }
        $test = array();
        foreach ($unserialized as $item) {
            $test[] = $item;
        }
        $this->assertSame($expected, $test);
    }

    public function testCanRetrieveQueueAsArray()
    {
        $expected = array('bat', 'baz', 'bar', 'foo');
        $test     = $this->stack->toArray();
        $this->assertSame($expected, $test, var_export($test, 1));
    }
}
