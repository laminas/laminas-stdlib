<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use InvalidArgumentException;
use Laminas\Stdlib\ArrayObject;
use PHPUnit\Framework\TestCase;

use function asort;
use function ksort;
use function natcasesort;
use function natsort;
use function preg_replace;
use function serialize;
use function strcasecmp;
use function uasort;
use function uksort;
use function unserialize;

class ArrayObjectTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $ar = new ArrayObject();
        self::assertEquals(ArrayObject::STD_PROP_LIST, $ar->getFlags());
        self::assertEquals('ArrayIterator', $ar->getIteratorClass());
        self::assertInstanceOf('ArrayIterator', $ar->getIterator());
        self::assertSame([], $ar->getArrayCopy());
        self::assertEquals(0, $ar->count());
    }

    public function testConstructorParameters()
    {
        $ar = new ArrayObject(['foo' => 'bar'], ArrayObject::ARRAY_AS_PROPS, 'RecursiveArrayIterator');
        self::assertEquals(ArrayObject::ARRAY_AS_PROPS, $ar->getFlags());
        self::assertEquals('RecursiveArrayIterator', $ar->getIteratorClass());
        self::assertInstanceOf('RecursiveArrayIterator', $ar->getIterator());
        self::assertSame(['foo' => 'bar'], $ar->getArrayCopy());
        self::assertEquals(1, $ar->count());
        self::assertSame('bar', $ar->foo);
        self::assertSame('bar', $ar['foo']);
    }

    public function testStdPropList()
    {
        $ar = new ArrayObject();
        $ar->foo = 'bar';
        $ar->bar = 'baz';
        self::assertSame('bar', $ar->foo);
        self::assertSame('baz', $ar->bar);
        self::assertFalse(isset($ar['foo']));
        self::assertFalse(isset($ar['bar']));
        self::assertEquals(0, $ar->count());
        self::assertSame([], $ar->getArrayCopy());
    }

    public function testStdPropListCannotAccessObjectVars()
    {
        $this->expectException(InvalidArgumentException::class);
        $ar = new ArrayObject();
        $ar->flag;
    }

    public function testStdPropListStillHandlesArrays()
    {
        $ar = new ArrayObject();
        $ar->foo = 'bar';
        $ar['foo'] = 'baz';

        self::assertSame('bar', $ar->foo);
        self::assertSame('baz', $ar['foo']);
        self::assertEquals(1, $ar->count());
    }

    public function testArrayAsProps()
    {
        $ar = new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);
        $ar->foo = 'bar';
        $ar['foo'] = 'baz';
        $ar->bar = 'foo';
        $ar['baz'] = 'bar';

        self::assertSame('baz', $ar->foo);
        self::assertSame('baz', $ar['foo']);
        self::assertSame($ar->foo, $ar['foo']);
        self::assertEquals(3, $ar->count());
    }

    public function testAppend()
    {
        $ar = new ArrayObject(['one', 'two']);
        self::assertEquals(2, $ar->count());

        $ar->append('three');

        self::assertSame('three', $ar[2]);
        self::assertEquals(3, $ar->count());
    }

    public function testAsort()
    {
        $ar = new ArrayObject(['d' => 'lemon', 'a' => 'orange', 'b' => 'banana', 'c' => 'apple']);
        $sorted = $ar->getArrayCopy();
        asort($sorted);
        $ar->asort();
        self::assertSame($sorted, $ar->getArrayCopy());
    }

    public function testCount()
    {
        $this->expectWarning();
        $this->expectExceptionMessage('Parameter must be an array or an object that implements Countable');
        $ar = new ArrayObject(new TestAsset\ArrayObjectObjectVars());
        self::assertCount(1, $ar);
    }

    public function testCountable()
    {
        $ar = new ArrayObject(new TestAsset\ArrayObjectObjectCount());
        self::assertCount(42, $ar);
    }

    public function testExchangeArray()
    {
        $ar = new ArrayObject(['foo' => 'bar']);
        $old = $ar->exchangeArray(['bar' => 'baz']);

        self::assertSame(['foo' => 'bar'], $old);
        self::assertSame(['bar' => 'baz'], $ar->getArrayCopy());
    }

    public function testExchangeArrayPhpArrayObject()
    {
        $ar = new ArrayObject(['foo' => 'bar']);
        $old = $ar->exchangeArray(new \ArrayObject(['bar' => 'baz']));

        self::assertSame(['foo' => 'bar'], $old);
        self::assertSame(['bar' => 'baz'], $ar->getArrayCopy());
    }

    public function testExchangeArrayStdlibArrayObject()
    {
        $ar = new ArrayObject(['foo' => 'bar']);
        $old = $ar->exchangeArray(new ArrayObject(['bar' => 'baz']));

        self::assertSame(['foo' => 'bar'], $old);
        self::assertSame(['bar' => 'baz'], $ar->getArrayCopy());
    }

    public function testExchangeArrayTestAssetIterator()
    {
        $ar = new ArrayObject();
        $ar->exchangeArray(new TestAsset\ArrayObjectIterator(['foo' => 'bar']));

        // make sure it does what php array object does:
        $ar2 = new \ArrayObject();
        $ar2->exchangeArray(new TestAsset\ArrayObjectIterator(['foo' => 'bar']));

        self::assertEquals($ar2->getArrayCopy(), $ar->getArrayCopy());
    }

    public function testExchangeArrayArrayIterator()
    {
        $ar = new ArrayObject();
        $ar->exchangeArray(new \ArrayIterator(['foo' => 'bar']));

        self::assertEquals(['foo' => 'bar'], $ar->getArrayCopy());
    }

    public function testExchangeArrayStringArgumentFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $ar     = new ArrayObject(['foo' => 'bar']);
        $old    = $ar->exchangeArray('Bacon');
    }

    public function testGetArrayCopy()
    {
        $ar = new ArrayObject(['foo' => 'bar']);
        self::assertSame(['foo' => 'bar'], $ar->getArrayCopy());
    }

    public function testFlags()
    {
        $ar = new ArrayObject();
        self::assertEquals(ArrayObject::STD_PROP_LIST, $ar->getFlags());
        $ar = new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);
        self::assertEquals(ArrayObject::ARRAY_AS_PROPS, $ar->getFlags());

        $ar->setFlags(ArrayObject::STD_PROP_LIST);
        self::assertEquals(ArrayObject::STD_PROP_LIST, $ar->getFlags());
        $ar->setFlags(ArrayObject::ARRAY_AS_PROPS);
        self::assertEquals(ArrayObject::ARRAY_AS_PROPS, $ar->getFlags());
    }

    public function testIterator()
    {
        $ar = new ArrayObject(['1' => 'one', '2' => 'two', '3' => 'three']);
        $iterator = $ar->getIterator();
        $iterator2 = new \ArrayIterator($ar->getArrayCopy());
        self::assertEquals($iterator2->getArrayCopy(), $iterator->getArrayCopy());
    }

    public function testIteratorClass()
    {
        $ar = new ArrayObject([], ArrayObject::STD_PROP_LIST, 'RecursiveArrayIterator');
        self::assertEquals('RecursiveArrayIterator', $ar->getIteratorClass());
        $ar = new ArrayObject([], ArrayObject::STD_PROP_LIST, 'ArrayIterator');
        self::assertEquals('ArrayIterator', $ar->getIteratorClass());
        $ar->setIteratorClass('RecursiveArrayIterator');
        self::assertEquals('RecursiveArrayIterator', $ar->getIteratorClass());
        $ar->setIteratorClass('ArrayIterator');
        self::assertEquals('ArrayIterator', $ar->getIteratorClass());
    }

    public function testInvalidIteratorClassThrowsInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);
        $ar = new ArrayObject([], ArrayObject::STD_PROP_LIST, 'InvalidArrayIterator');
    }

    public function testKsort()
    {
        $ar = new ArrayObject(['d' => 'lemon', 'a' => 'orange', 'b' => 'banana', 'c' => 'apple']);
        $sorted = $ar->getArrayCopy();
        ksort($sorted);
        $ar->ksort();
        self::assertSame($sorted, $ar->getArrayCopy());
    }

    public function testNatcasesort()
    {
        $ar = new ArrayObject(['IMG0.png', 'img12.png', 'img10.png', 'img2.png', 'img1.png', 'IMG3.png']);
        $sorted = $ar->getArrayCopy();
        natcasesort($sorted);
        $ar->natcasesort();
        self::assertSame($sorted, $ar->getArrayCopy());
    }

    public function testNatsort()
    {
        $ar = new ArrayObject(['img12.png', 'img10.png', 'img2.png', 'img1.png']);
        $sorted = $ar->getArrayCopy();
        natsort($sorted);
        $ar->natsort();
        self::assertSame($sorted, $ar->getArrayCopy());
    }

    public function testOffsetExists()
    {
        $ar = new ArrayObject();
        $ar['foo'] = 'bar';
        $ar->bar = 'baz';

        self::assertTrue($ar->offsetExists('foo'));
        self::assertFalse($ar->offsetExists('bar'));
        self::assertTrue(isset($ar->bar));
        self::assertFalse(isset($ar->foo));
    }

    public function testOffsetExistsThrowsExceptionOnProtectedProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $ar = new ArrayObject();
        isset($ar->protectedProperties);
    }

    public function testOffsetGetOffsetSet()
    {
        $ar = new ArrayObject();
        $ar['foo'] = 'bar';
        $ar->bar = 'baz';

        self::assertSame('bar', $ar['foo']);
        self::assertSame('baz', $ar->bar);
        self::assertFalse(isset($ar->unknown));
        self::assertFalse(isset($ar['unknown']));
    }

    public function testOffsetGetThrowsExceptionOnProtectedProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $ar = new ArrayObject();
        $ar->protectedProperties;
    }

    public function testOffsetSetThrowsExceptionOnProtectedProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $ar = new ArrayObject();
        $ar->protectedProperties = null;
    }

    public function testOffsetUnset()
    {
        $ar = new ArrayObject();
        $ar['foo'] = 'bar';
        $ar->bar = 'foo';
        unset($ar['foo']);
        unset($ar->bar);
        self::assertFalse(isset($ar['foo']));
        self::assertFalse(isset($ar->bar));
        self::assertSame([], $ar->getArrayCopy());
    }

    public function testOffsetUnsetMultidimensional()
    {
        $ar = new ArrayObject();
        $ar['foo'] = ['bar' => ['baz' => 'boo']];
        unset($ar['foo']['bar']['baz']);

        self::assertArrayNotHasKey('baz', $ar['foo']['bar']);
    }

    public function testOffsetUnsetThrowsExceptionOnProtectedProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $ar = new ArrayObject();
        unset($ar->protectedProperties);
    }

    public function testSerializeUnserialize()
    {
        $ar = new ArrayObject();
        $ar->foo = 'bar';
        $ar['bar'] = 'foo';
        $serialized = $ar->serialize();

        $ar = new ArrayObject();
        $ar->unserialize($serialized);

        self::assertSame('bar', $ar->foo);
        self::assertSame('foo', $ar['bar']);
    }

    public function testUasort()
    {
        $function = function ($a, $b) {
            if ($a == $b) {
                return 0;
            }

            return ($a < $b) ? -1 : 1;
        };
        $ar = new ArrayObject(['a' => 4, 'b' => 8, 'c' => -1, 'd' => -9, 'e' => 2, 'f' => 5, 'g' => 3, 'h' => -4]);
        $sorted = $ar->getArrayCopy();
        uasort($sorted, $function);
        $ar->uasort($function);
        self::assertSame($sorted, $ar->getArrayCopy());
    }

    public function testUksort()
    {
        $function = function ($a, $b) {
            $a = preg_replace('@^(a|an|the) @', '', $a);
            $b = preg_replace('@^(a|an|the) @', '', $b);

            return strcasecmp($a, $b);
        };

        $ar = new ArrayObject(['John' => 1, 'the Earth' => 2, 'an apple' => 3, 'a banana' => 4]);
        $sorted = $ar->getArrayCopy();
        uksort($sorted, $function);
        $ar->uksort($function);
        self::assertSame($sorted, $ar->getArrayCopy());
    }

    /**
     * @group 6089
     */
    public function testSerializationRestoresProperties()
    {
        $ar        = new ArrayObject();
        $ar->foo   = 'bar';
        $ar['bar'] = 'foo';

        self::assertEquals($ar, unserialize(serialize($ar)));
    }
}
