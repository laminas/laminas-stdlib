<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use ArrayAccess;
use ArrayObject;
use Countable;
use Laminas\Stdlib\Parameters;
use Laminas\Stdlib\ParametersInterface;
use PHPUnit\Framework\TestCase;
use Serializable;
use Traversable;

class ParametersTest extends TestCase
{
    public function testParametersConstructionAndClassStructure(): void
    {
        $parameters = new Parameters();
        self::assertInstanceOf(ParametersInterface::class, $parameters);
        self::assertInstanceOf(ArrayObject::class, $parameters);
        self::assertInstanceOf(ArrayAccess::class, $parameters);
        self::assertInstanceOf(Countable::class, $parameters);
        self::assertInstanceOf(Serializable::class, $parameters);
        self::assertInstanceOf(Traversable::class, $parameters);
    }

    public function testParametersPersistNameAndValues(): void
    {
        /** @var Parameters<string, mixed> $parameters */
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

    public function testParametersOffsetGetReturnsNullIfNonexistentKeyIsProvided(): void
    {
        $parameters = new Parameters();
        self::assertNull($parameters->foo);
    }

    public function testParametersGetReturnsDefaultValueIfNonExistent(): void
    {
        $parameters = new Parameters();

        self::assertEquals(5, $parameters->get('nonExistentProp', 5));
    }
}
