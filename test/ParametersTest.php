<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\Parameters;
use PHPUnit\Framework\TestCase;

class ParametersTest extends TestCase
{
    public function testParametersConstructionAndClassStructure()
    {
        $parameters = new Parameters();
        self::assertInstanceOf('Laminas\Stdlib\ParametersInterface', $parameters);
        self::assertInstanceOf('ArrayObject', $parameters);
        self::assertInstanceOf('ArrayAccess', $parameters);
        self::assertInstanceOf('Countable', $parameters);
        self::assertInstanceOf('Serializable', $parameters);
        self::assertInstanceOf('Traversable', $parameters);
    }

    public function testParametersPersistNameAndValues()
    {
        $parameters = new Parameters(['foo' => 'bar']);
        self::assertEquals('bar', $parameters['foo']);
        self::assertEquals('bar', $parameters->foo);
        $parameters->offsetSet('baz', 5);
        self::assertEquals(5, $parameters->baz);

        $parameters->fromArray(['bar' => 'foo']);
        self::assertEquals('foo', $parameters->bar);

        $parameters->fromString('bar=foo&five=5');
        self::assertEquals('foo', $parameters->bar);
        self::assertEquals('5', $parameters->five);
        self::assertEquals(['bar' => 'foo', 'five' => '5'], $parameters->toArray());
        self::assertEquals('bar=foo&five=5', $parameters->toString());

        $parameters->fromArray([]);
        $parameters->set('foof', 'barf');
        self::assertEquals('barf', $parameters->get('foof'));
        self::assertEquals('barf', $parameters->foof);
    }

    public function testParametersOffsetgetReturnsNullIfNonexistentKeyIsProvided()
    {
        $parameters = new Parameters;
        self::assertNull($parameters->foo);
    }

    public function testParametersGetReturnsDefaultValueIfNonExistent()
    {
        $parameters = new Parameters();

        self::assertEquals(5, $parameters->get('nonExistentProp', 5));
    }
}
