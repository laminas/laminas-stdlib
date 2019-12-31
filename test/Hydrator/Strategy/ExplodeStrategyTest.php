<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator\Strategy;

use Laminas\Stdlib\Hydrator\Strategy\ExplodeStrategy;

/**
 * Tests for {@see \Laminas\Stdlib\Hydrator\Strategy\ExplodeStrategy}
 *
 * @covers \Laminas\Stdlib\Hydrator\Strategy\ExplodeStrategy
 */
class ExplodeStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getValidHydratedValues
     *
     * @param string   $expected
     * @param string   $delimiter
     * @param string[] $extractValue
     */
    public function testExtract($expected, $delimiter, $extractValue)
    {
        $strategy = new ExplodeStrategy($delimiter);

        if (is_numeric($expected)) {
            $this->assertEquals($expected, $strategy->extract($extractValue));
        } else {
            $this->assertSame($expected, $strategy->extract($extractValue));
        }
    }

    public function testGetExceptionWithInvalidArgumentOnExtraction()
    {
        $strategy = new ExplodeStrategy();

        $this->setExpectedException('Laminas\Stdlib\Hydrator\Strategy\Exception\InvalidArgumentException');

        $strategy->extract('');
    }

    public function testGetEmptyArrayWhenHydratingNullValue()
    {
        $strategy = new ExplodeStrategy();

        $this->assertSame(array(), $strategy->hydrate(null));
    }

    public function testGetExceptionWithEmptyDelimiter()
    {
        $this->setExpectedException('Laminas\Stdlib\Hydrator\Strategy\Exception\InvalidArgumentException');

        new ExplodeStrategy('');
    }

    public function testGetExceptionWithInvalidDelimiter()
    {
        $this->setExpectedException('Laminas\Stdlib\Hydrator\Strategy\Exception\InvalidArgumentException');

        new ExplodeStrategy(array());
    }

    public function testHydrateWithExplodeLimit()
    {
        $strategy = new ExplodeStrategy('-', 2);
        $this->assertSame(array('foo', 'bar-baz-bat'), $strategy->hydrate('foo-bar-baz-bat'));

        $strategy = new ExplodeStrategy('-', '3');
        $this->assertSame(array('foo', 'bar', 'baz-bat'), $strategy->hydrate('foo-bar-baz-bat'));
    }

    public function testHydrateWithInvalidScalarType()
    {
        $strategy = new ExplodeStrategy();

        $this->setExpectedException(
            'Laminas\Stdlib\Hydrator\Strategy\Exception\InvalidArgumentException',
            'Laminas\Stdlib\Hydrator\Strategy\ExplodeStrategy::hydrate expects argument 1 to be string,'
            . ' array provided instead'
        );

        $strategy->hydrate(array());
    }

    public function testHydrateWithInvalidObjectType()
    {
        $strategy = new ExplodeStrategy();

        $this->setExpectedException(
            'Laminas\Stdlib\Hydrator\Strategy\Exception\InvalidArgumentException',
            'Laminas\Stdlib\Hydrator\Strategy\ExplodeStrategy::hydrate expects argument 1 to be string,'
            . ' stdClass provided instead'
        );

        $strategy->hydrate(new \stdClass());
    }

    public function testExtractWithInvalidObjectType()
    {
        $strategy = new ExplodeStrategy();

        $this->setExpectedException(
            'Laminas\Stdlib\Hydrator\Strategy\Exception\InvalidArgumentException',
            'Laminas\Stdlib\Hydrator\Strategy\ExplodeStrategy::extract expects argument 1 to be array,'
            . ' stdClass provided instead'
        );

        $strategy->extract(new \stdClass());
    }

    /**
     * @dataProvider getValidHydratedValues
     *
     * @param mixed    $value
     * @param string   $delimiter
     * @param string[] $expected
     */
    public function testHydration($value, $delimiter, array $expected)
    {
        $strategy = new ExplodeStrategy($delimiter);

        $this->assertSame($expected, $strategy->hydrate($value));
    }

    /**
     * Data provider
     *
     * @return mixed[][]
     */
    public function getValidHydratedValues()
    {
        return array(
            array(null, ',', array()),
            array('', ',', array('')),
            array('foo', ',', array('foo')),
            array('foo,bar', ',', array('foo', 'bar')),
            array('foo.bar', '.', array('foo', 'bar')),
            array('foo.bar', ',', array('foo.bar')),
            array(123, ',', array('123')),
            array(123, '2', array('1', '3')),
            array(123.456, ',', array('123.456')),
            array(123.456, '.', array('123', '456')),
            array('foo,bar,dev,null', ',', array('foo', 'bar', 'dev', 'null')),
            array('foo;bar;dev;null', ';', array('foo', 'bar', 'dev', 'null')),
            array('', ',', array('')),
        );
    }
}
