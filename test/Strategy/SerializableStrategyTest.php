<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Strategy;

use Laminas\Serializer\Serializer;
use Laminas\Stdlib\Hydrator\Strategy\SerializableStrategy;
use PHPUnit_Framework_TestCase as TestCase;

class SerializableStrategyTest extends TestCase
{
    public function testCannotUseBadArgumentSerilizer()
    {
        $this->setExpectedException('Laminas\Hydrator\Exception\InvalidArgumentException');
        $serializerStrategy = new SerializableStrategy(false);
    }

    public function testUseBadSerilizerObject()
    {
        $serializer = Serializer::factory('phpserialize');
        $serializerStrategy = new SerializableStrategy($serializer);
        $this->assertEquals($serializer, $serializerStrategy->getSerializer());
    }

    public function testUseBadSerilizerString()
    {
        $serializerStrategy = new SerializableStrategy('phpserialize');
        $this->assertEquals('Laminas\Serializer\Adapter\PhpSerialize', get_class($serializerStrategy->getSerializer()));
    }

    public function testCanSerialize()
    {
        $serializer = Serializer::factory('phpserialize');
        $serializerStrategy = new SerializableStrategy($serializer);
        $serialized = $serializerStrategy->extract('foo');
        $this->assertEquals($serialized, 's:3:"foo";');
    }

    public function testCanUnserialize()
    {
        $serializer = Serializer::factory('phpserialize');
        $serializerStrategy = new SerializableStrategy($serializer);
        $serialized = $serializerStrategy->hydrate('s:3:"foo";');
        $this->assertEquals($serialized, 'foo');
    }
}
