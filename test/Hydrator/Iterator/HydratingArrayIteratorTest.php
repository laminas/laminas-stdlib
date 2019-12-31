<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Hydrator\Iterator;

use ArrayObject;
use Laminas\Stdlib\Hydrator\ArraySerializable;
use Laminas\Stdlib\Hydrator\Iterator\HydratingArrayIterator;

class HydratingArrayIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testHydratesObjectAndClonesOnCurrent()
    {
        $data = [
            ['foo' => 'bar'],
            ['baz' => 'bat'],
        ];

        $object   = new ArrayObject();

        $hydratingIterator = new HydratingArrayIterator(new ArraySerializable(), $data, $object);

        $hydratingIterator->rewind();
        $this->assertEquals(new ArrayObject($data[0]), $hydratingIterator->current());
        $this->assertNotSame(
            $object,
            $hydratingIterator->current(),
            'Hydrating Iterator did not clone the object'
        );

        $hydratingIterator->next();
        $this->assertEquals(new ArrayObject($data[1]), $hydratingIterator->current());
    }

    public function testUsingStringForObjectName()
    {
        $data = [
            ['foo' => 'bar'],
        ];

        $hydratingIterator = new HydratingArrayIterator(new ArraySerializable(), $data, '\ArrayObject');

        $hydratingIterator->rewind();
        $this->assertEquals(new ArrayObject($data[0]), $hydratingIterator->current());
    }

    public function testThrowingInvalidArguementExceptionWhenSettingPrototypeToInvalidClass()
    {
        $this->setExpectedException('Laminas\Hydrator\Exception\InvalidArgumentException');
        $hydratingIterator = new HydratingArrayIterator(new ArraySerializable(), [], 'not a real class');
    }
}
