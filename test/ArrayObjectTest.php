<?php // phpcs:disable WebimpressCodingStandard.PHP.CorrectClassNameCase.Invalid,SlevomatCodingStandard.TypeHints.DeclareStrictTypes.IncorrectWhitespaceBeforeDeclare

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use ArrayIterator;
use InvalidArgumentException;
use Laminas\Stdlib\ArrayObject;
use PHPUnit\Framework\TestCase;
use TypeError;

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

use const PHP_MAJOR_VERSION;

class ArrayObjectTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $ar = new ArrayObject();
        self::assertEquals(ArrayObject::STD_PROP_LIST, $ar->getFlags());
        self::assertEquals('ArrayIterator', $ar->getIteratorClass());
        self::assertInstanceOf('ArrayIterator', $ar->getIterator());
        self::assertSame([], $ar->getArrayCopy());
        self::assertEquals(0, $ar->count());
    }

    public function testConstructorParameters(): void
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

    public function testStdPropList(): void
    {
        $ar      = new ArrayObject();
        $ar->foo = 'bar';
        $ar->bar = 'baz';
        self::assertSame('bar', $ar->foo);
        self::assertSame('baz', $ar->bar);
        self::assertFalse(isset($ar['foo']));
        self::assertFalse(isset($ar['bar']));
        self::assertEquals(0, $ar->count());
        self::assertSame([], $ar->getArrayCopy());
    }

    public function testStdPropListCannotAccessObjectVars(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $ar = new ArrayObject();
        $ar->flag;
    }

    public function testStdPropListStillHandlesArrays(): void
    {
        $ar        = new ArrayObject();
        $ar->foo   = 'bar';
        $ar['foo'] = 'baz';

        self::assertSame('bar', $ar->foo);
        self::assertSame('baz', $ar['foo']);
        self::assertEquals(1, $ar->count());
    }

    public function testArrayAsProps(): void
    {
        $ar        = new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);
        $ar->foo   = 'bar';
        $ar['foo'] = 'baz';
        $ar->bar   = 'foo';
        $ar['baz'] = 'bar';

        self::assertSame('baz', $ar->foo);
        self::assertSame('baz', $ar['foo']);
        self::assertSame($ar->foo, $ar['foo']);
        self::assertEquals(3, $ar->count());
    }

    public function testAppend(): void
    {
        $ar = new ArrayObject(['one', 'two']);
        self::assertEquals(2, $ar->count());

        $ar->append('three');

        self::assertSame('three', $ar[2]);
        self::assertEquals(3, $ar->count());
    }

    public function testAsort(): void
    {
        $ar     = new ArrayObject(['d' => 'lemon', 'a' => 'orange', 'b' => 'banana', 'c' => 'apple']);
        $sorted = $ar->getArrayCopy();
        asort($sorted);
        $ar->asort();
        self::assertSame($sorted, $ar->getArrayCopy());
    }

    public function testCountRaisesWarningUnderPhpSeven(): void
    {
        if (PHP_MAJOR_VERSION > 7) {
            $this->markTestSkipped('This test only makes sense under PHP 7');
        }

        $ar = new ArrayObject(new TestAsset\ArrayObjectObjectVars());

        $this->expectWarning();
        $this->expectExceptionMessage('Parameter must be an array or an object that implements Countable');
        self::assertCount(1, $ar);
    }

    public function testCountRaisesTypeErrorUnderPhpEight(): void
    {
        if (PHP_MAJOR_VERSION < 8) {
            $this->markTestSkipped('This test only makes sense under PHP 8 and above');
        }

        $ar = new ArrayObject(new TestAsset\ArrayObjectObjectVars());

        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Countable|array');
        self::assertCount(1, $ar);
    }

    public function testCountable(): void
    {
        $ar = new ArrayObject(new TestAsset\ArrayObjectObjectCount());
        self::assertCount(42, $ar);
    }

    public function testExchangeArray(): void
    {
        $ar  = new ArrayObject(['foo' => 'bar']);
        $old = $ar->exchangeArray(['bar' => 'baz']);

        self::assertSame(['foo' => 'bar'], $old);
        self::assertSame(['bar' => 'baz'], $ar->getArrayCopy());
    }

    public function testExchangeArrayPhpArrayObject(): void
    {
        $ar  = new ArrayObject(['foo' => 'bar']);
        $old = $ar->exchangeArray(new \ArrayObject(['bar' => 'baz']));

        self::assertSame(['foo' => 'bar'], $old);
        self::assertSame(['bar' => 'baz'], $ar->getArrayCopy());
    }

    public function testExchangeArrayStdlibArrayObject(): void
    {
        $ar  = new ArrayObject(['foo' => 'bar']);
        $old = $ar->exchangeArray(new ArrayObject(['bar' => 'baz']));

        self::assertSame(['foo' => 'bar'], $old);
        self::assertSame(['bar' => 'baz'], $ar->getArrayCopy());
    }

    public function testExchangeArrayTestAssetIterator(): void
    {
        $ar = new ArrayObject();
        $ar->exchangeArray(new TestAsset\ArrayObjectIterator(['foo' => 'bar']));

        // make sure it does what php array object does:
        $ar2 = new \ArrayObject();
        $ar2->exchangeArray(new TestAsset\ArrayObjectIterator(['foo' => 'bar']));

        self::assertEquals($ar2->getArrayCopy(), $ar->getArrayCopy());
    }

    public function testExchangeArrayArrayIterator(): void
    {
        $ar = new ArrayObject();
        $ar->exchangeArray(new ArrayIterator(['foo' => 'bar']));

        self::assertEquals(['foo' => 'bar'], $ar->getArrayCopy());
    }

    public function testExchangeArrayStringArgumentFail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $ar = new ArrayObject(['foo' => 'bar']);
        $ar->exchangeArray('Bacon');
    }

    public function testGetArrayCopy(): void
    {
        $ar = new ArrayObject(['foo' => 'bar']);
        self::assertSame(['foo' => 'bar'], $ar->getArrayCopy());
    }

    public function testFlags(): void
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

    public function testIterator(): void
    {
        $ar       = new ArrayObject(['1' => 'one', '2' => 'two', '3' => 'three']);
        $iterator = $ar->getIterator();
        self::assertInstanceOf(ArrayIterator::class, $iterator);
        $iterator2 = new ArrayIterator($ar->getArrayCopy());
        self::assertEquals($iterator2->getArrayCopy(), $iterator->getArrayCopy());
    }

    public function testIteratorClass(): void
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

    public function testInvalidIteratorClassThrowsInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ArrayObject([], ArrayObject::STD_PROP_LIST, 'InvalidArrayIterator');
    }

    public function testKsort(): void
    {
        $ar     = new ArrayObject(['d' => 'lemon', 'a' => 'orange', 'b' => 'banana', 'c' => 'apple']);
        $sorted = $ar->getArrayCopy();
        ksort($sorted);
        $ar->ksort();
        self::assertSame($sorted, $ar->getArrayCopy());
    }

    public function testNatcasesort(): void
    {
        $ar     = new ArrayObject(['IMG0.png', 'img12.png', 'img10.png', 'img2.png', 'img1.png', 'IMG3.png']);
        $sorted = $ar->getArrayCopy();
        natcasesort($sorted);
        $ar->natcasesort();
        self::assertSame($sorted, $ar->getArrayCopy());
    }

    public function testNatsort(): void
    {
        $ar     = new ArrayObject(['img12.png', 'img10.png', 'img2.png', 'img1.png']);
        $sorted = $ar->getArrayCopy();
        natsort($sorted);
        $ar->natsort();
        self::assertSame($sorted, $ar->getArrayCopy());
    }

    public function testOffsetExists(): void
    {
        $ar        = new ArrayObject();
        $ar['foo'] = 'bar';
        $ar->bar   = 'baz';

        self::assertTrue($ar->offsetExists('foo'));
        self::assertFalse($ar->offsetExists('bar'));
        self::assertTrue(isset($ar->bar));
        self::assertFalse(isset($ar->foo));
    }

    public function testOffsetExistsThrowsExceptionOnProtectedProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $ar = new ArrayObject();
        isset($ar->protectedProperties);
    }

    public function testOffsetGetOffsetSet(): void
    {
        $ar        = new ArrayObject();
        $ar['foo'] = 'bar';
        $ar->bar   = 'baz';

        self::assertSame('bar', $ar['foo']);
        self::assertSame('baz', $ar->bar);
        self::assertFalse(isset($ar->unknown));
        self::assertFalse(isset($ar['unknown']));
    }

    public function testOffsetGetThrowsExceptionOnProtectedProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $ar = new ArrayObject();
        $ar->protectedProperties;
    }

    public function testOffsetSetThrowsExceptionOnProtectedProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $ar                      = new ArrayObject();
        $ar->protectedProperties = null;
    }

    public function testOffsetUnset(): void
    {
        $ar        = new ArrayObject();
        $ar['foo'] = 'bar';
        $ar->bar   = 'foo';
        unset($ar['foo']);
        unset($ar->bar);
        self::assertFalse(isset($ar['foo']));
        self::assertFalse(isset($ar->bar));
        self::assertSame([], $ar->getArrayCopy());
    }

    public function testOffsetUnsetMultidimensional(): void
    {
        $ar        = new ArrayObject();
        $ar['foo'] = ['bar' => ['baz' => 'boo']];
        unset($ar['foo']['bar']['baz']);

        self::assertArrayNotHasKey('baz', $ar['foo']['bar']);
    }

    public function testOffsetUnsetThrowsExceptionOnProtectedProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $ar = new ArrayObject();
        unset($ar->protectedProperties);
    }

    public function testSerializeUnserialize(): void
    {
        $ar         = new ArrayObject();
        $ar->foo    = 'bar';
        $ar['bar']  = 'foo';
        $serialized = $ar->serialize();

        $ar = new ArrayObject();
        $ar->unserialize($serialized);

        self::assertSame('bar', $ar->foo);
        self::assertSame('foo', $ar['bar']);
    }

    public function testUasort(): void
    {
        $function = function ($a, $b): int {
            if ($a === $b) {
                return 0;
            }

            return $a < $b ? -1 : 1;
        };
        // phpcs:ignore Generic.Files.LineLength.TooLong
        $ar     = new ArrayObject(['a' => 4, 'b' => 8, 'c' => -1, 'd' => -9, 'e' => 2, 'f' => 5, 'g' => 3, 'h' => -4]);
        $sorted = $ar->getArrayCopy();
        uasort($sorted, $function);
        $ar->uasort($function);
        self::assertSame($sorted, $ar->getArrayCopy());
    }

    public function testUksort(): void
    {
        $function = function ($a, $b): int {
            $a = preg_replace('@^(a|an|the) @', '', $a);
            $b = preg_replace('@^(a|an|the) @', '', $b);

            return strcasecmp($a, $b);
        };

        $ar     = new ArrayObject(['John' => 1, 'the Earth' => 2, 'an apple' => 3, 'a banana' => 4]);
        $sorted = $ar->getArrayCopy();
        uksort($sorted, $function);
        $ar->uksort($function);
        self::assertSame($sorted, $ar->getArrayCopy());
    }

    /**
     * @group 6089
     */
    public function testSerializationRestoresProperties(): void
    {
        $ar        = new ArrayObject();
        $ar->foo   = 'bar';
        $ar['bar'] = 'foo';

        self::assertEquals($ar, unserialize(serialize($ar)));
    }
}
