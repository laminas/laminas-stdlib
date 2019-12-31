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
        $this->assertInstanceOf('Laminas\Stdlib\ParametersInterface', $parameters);
        $this->assertInstanceOf('ArrayObject', $parameters);
        $this->assertInstanceOf('ArrayAccess', $parameters);
        $this->assertInstanceOf('Countable', $parameters);
        $this->assertInstanceOf('Serializable', $parameters);
        $this->assertInstanceOf('Traversable', $parameters);
    }

    public function testParametersPersistNameAndValues()
    {
        $parameters = new Parameters(['foo' => 'bar']);
        $this->assertEquals('bar', $parameters['foo']);
        $this->assertEquals('bar', $parameters->foo);
        $parameters->offsetSet('baz', 5);
        $this->assertEquals(5, $parameters->baz);

        $parameters->fromArray(['bar' => 'foo']);
        $this->assertEquals('foo', $parameters->bar);

        $parameters->fromString('bar=foo&five=5');
        $this->assertEquals('foo', $parameters->bar);
        $this->assertEquals('5', $parameters->five);
        $this->assertEquals(['bar' => 'foo', 'five' => '5'], $parameters->toArray());
        $this->assertEquals('bar=foo&five=5', $parameters->toString());

        $parameters->fromArray([]);
        $parameters->set('foof', 'barf');
        $this->assertEquals('barf', $parameters->get('foof'));
        $this->assertEquals('barf', $parameters->foof);
    }

    public function testParametersOffsetgetReturnsNullIfNonexistentKeyIsProvided()
    {
        $parameters = new Parameters;
        $this->assertNull($parameters->foo);
    }

    public function testParametersGetReturnsDefaultValueIfNonExistent()
    {
        $parameters = new Parameters();

        $this->assertEquals(5, $parameters->get('nonExistentProp', 5));
    }
}
