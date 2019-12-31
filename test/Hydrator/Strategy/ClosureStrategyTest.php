<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator\Strategy;

use Laminas\Stdlib\Hydrator\Strategy\ClosureStrategy;

class ClosureStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function extractDataProvider()
    {
        return array(
            array(
                function ($value) { return strtoupper($value); },
                new \ArrayObject(array('foo' => 'foo', 'bar' => 'bar')),
                array('foo' => 'FOO', 'bar' => 'BAR'),
            ),
            array(
                function ($value, $data) { return isset($data['bar']) ? strtoupper($value) : $value; },
                new \ArrayObject(array('foo' => 'foo', 'bar' => 'bar')),
                array('foo' => 'FOO', 'bar' => 'BAR'),
            ),
            array(
                function ($value, $data) { return isset($data['bar']) ? strtoupper($value) : $value; },
                new \ArrayObject(array('foo' => 'foo', 'baz' => 'baz')),
                array('foo' => 'foo', 'baz' => 'baz'),
            ),
        );
    }

    /**
     * @return array
     */
    public function hydrateDataProvider()
    {
        return array(
            array(
                function ($value) { return strtoupper($value); },
                array('foo' => 'foo', 'bar' => 'bar'),
                array('foo' => 'FOO', 'bar' => 'BAR'),
            ),
            array(
                function ($value, $data) { return strtoupper($value); },
                array('foo' => 'foo', 'bar' => 'bar'),
                array('foo' => 'FOO', 'bar' => 'BAR'),
            ),
            array(
                function ($value, $data) { return isset($data['bar']) ? strtoupper($value) : $value; },
                array('foo' => 'foo', 'bar' => 'bar'),
                array('foo' => 'FOO', 'bar' => 'BAR'),
            ),
            array(
                function ($value, $data) { return isset($data['bar']) ? strtoupper($value) : $value; },
                array('foo' => 'foo', 'baz' => 'baz'),
                array('foo' => 'foo', 'baz' => 'baz'),
            ),
        );
    }

    /**
     * @covers \Laminas\Stdlib\Hydrator\Strategy\ClosureStrategy::extract()
     * @dataProvider extractDataProvider
     *
     * @param Callable $extractFunc
     * @param array    $data
     * @param array    $expected
     */
    public function testExtract($extractFunc, $data, $expected)
    {
        $strategy = new ClosureStrategy($extractFunc);

        $actual = array();
        foreach ($data as $k => $value) {
            $actual[$k] = $strategy->extract($value, $data);
        }

        $this->assertSame($actual, $expected);
    }

    /**
     * @covers \Laminas\Stdlib\Hydrator\Strategy\ClosureStrategy::hydrate()
     * @dataProvider hydrateDataProvider
     *
     * @param Callable $hydrateFunc
     * @param array    $data
     * @param array    $expected
     */
    public function testHydrate($hydrateFunc, $data, $expected)
    {
        $strategy = new ClosureStrategy(null, $hydrateFunc);

        $actual = array();
        foreach ($data as $k => $value) {
            $actual[$k] = $strategy->hydrate($value, $data);
        }

        $this->assertSame($actual, $expected);
    }
}
