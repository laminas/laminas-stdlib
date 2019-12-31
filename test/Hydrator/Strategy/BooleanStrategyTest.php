<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator\Strategy;

use Laminas\Stdlib\Hydrator\Strategy\BooleanStrategy;

/**
 * Tests for {@see \Laminas\Stdlib\Hydrator\Strategy\BooleanStrategy}
 *
 * @covers \Laminas\Stdlib\Hydrator\Strategy\BooleanStrategy
 */
class BooleanStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorWithValidInteger()
    {
        $this->assertInstanceOf('Laminas\Stdlib\Hydrator\Strategy\BooleanStrategy', new BooleanStrategy(1, 0));
    }

    public function testConstructorWithValidString()
    {
        $this->assertInstanceOf('Laminas\Stdlib\Hydrator\Strategy\BooleanStrategy', new BooleanStrategy('true', 'false'));
    }

    public function testExceptionOnWrongTrueValueInConstructor()
    {
        $this->setExpectedException(
            'Laminas\Hydrator\Exception\InvalidArgumentException',
            'Expected int or string as $trueValue.'
        );

        new BooleanStrategy(true, 0);
    }

    public function testExceptionOnWrongFalseValueInConstructor()
    {
        $this->setExpectedException(
            'Laminas\Hydrator\Exception\InvalidArgumentException',
            'Expected int or string as $falseValue.'
        );

        new BooleanStrategy(1, false);
    }

    public function testExtractString()
    {
        $hydrator = new BooleanStrategy('true', 'false');
        $this->assertEquals('true', $hydrator->extract(true));
        $this->assertEquals('false', $hydrator->extract(false));
    }

    public function testExtractInteger()
    {
        $hydrator = new BooleanStrategy(1, 0);

        $this->assertEquals(1, $hydrator->extract(true));
        $this->assertEquals(0, $hydrator->extract(false));
    }

    public function testExtractThrowsExceptionOnUnknownValue()
    {
        $hydrator = new BooleanStrategy(1, 0);

        $this->setExpectedException('Laminas\Hydrator\Exception\InvalidArgumentException', 'Unable to extract');

        $hydrator->extract(5);
    }

    public function testHydrateString()
    {
        $hydrator = new BooleanStrategy('true', 'false');
        $this->assertEquals(true, $hydrator->hydrate('true'));
        $this->assertEquals(false, $hydrator->hydrate('false'));
    }

    public function testHydrateInteger()
    {
        $hydrator = new BooleanStrategy(1, 0);
        $this->assertEquals(true, $hydrator->hydrate(1));
        $this->assertEquals(false, $hydrator->hydrate(0));
    }

    public function testHydrateUnexpectedValueThrowsException()
    {
        $this->setExpectedException('Laminas\Hydrator\Exception\InvalidArgumentException', 'Unexpected value');
        $hydrator = new BooleanStrategy(1, 0);
        $hydrator->hydrate(2);
    }

    public function testHydrateInvalidArgument()
    {
        $this->setExpectedException('Laminas\Hydrator\Exception\InvalidArgumentException', 'Unable to hydrate');
        $hydrator = new BooleanStrategy(1, 0);
        $hydrator->hydrate(new \stdClass());
    }
}
