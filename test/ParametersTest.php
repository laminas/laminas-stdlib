<?php // phpcs:disable WebimpressCodingStandard.PHP.CorrectClassNameCase.Invalid,SlevomatCodingStandard.TypeHints.DeclareStrictTypes.IncorrectWhitespaceBeforeDeclare

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\Parameters;
use Laminas\Stdlib\ParametersInterface;
use PHPUnit\Framework\TestCase;

class ParametersTest extends TestCase
{
    public function testParametersConstructionAndClassStructure(): void
    {
        $parameters = new Parameters();
        self::assertInstanceOf(ParametersInterface::class, $parameters);
        self::assertInstanceOf('ArrayObject', $parameters);
        self::assertInstanceOf('ArrayAccess', $parameters);
        self::assertInstanceOf('Countable', $parameters);
        self::assertInstanceOf('Serializable', $parameters);
        self::assertInstanceOf('Traversable', $parameters);
    }

    public function testParametersPersistNameAndValues(): void
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

    public function testParametersOffsetgetReturnsNullIfNonexistentKeyIsProvided(): void
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
