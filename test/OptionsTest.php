<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use ArrayObject;
use BadMethodCallException;
use InvalidArgumentException;
use Laminas\Stdlib\Exception;
use LaminasTest\Stdlib\TestAsset\TestOptions;
use LaminasTest\Stdlib\TestAsset\TestOptionsDerived;
use LaminasTest\Stdlib\TestAsset\TestOptionsNoStrict;
use LaminasTest\Stdlib\TestAsset\TestOptionsWithoutGetter;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    public function testConstructionWithArray(): void
    {
        $options = new TestOptions(['test_field' => 1]);

        self::assertEquals(1, $options->test_field);
    }

    public function testConstructionWithTraversable(): void
    {
        $config  = new ArrayObject(['test_field' => 1]);
        $options = new TestOptions($config);

        self::assertEquals(1, $options->test_field);
    }

    public function testConstructionWithOptions(): void
    {
        $options = new TestOptions(new TestOptions(['test_field' => 1]));

        self::assertEquals(1, $options->test_field);
    }

    public function testInvalidFieldThrowsException(): void
    {
        $this->expectException(BadMethodCallException::class);

        new TestOptions(['foo' => 'bar']);
    }

    public function testNonStrictOptionsDoesNotThrowException(): void
    {
        self::assertInstanceOf(
            TestOptionsNoStrict::class,
            new TestOptionsNoStrict(['foo' => 'bar'])
        );
    }

    public function testConstructionWithNull(): void
    {
        self::assertInstanceOf(TestOptions::class, new TestOptions(null));
    }

    public function testUnsetting(): void
    {
        $options = new TestOptions(['test_field' => 1]);

        self::assertTrue(isset($options->test_field));
        unset($options->testField);
        self::assertFalse(isset($options->test_field));
    }

    public function testUnsetThrowsInvalidArgumentException(): void
    {
        $options = new TestOptions();

        $this->expectException(InvalidArgumentException::class);

        unset($options->foobarField);
    }

    public function testGetThrowsBadMethodCallException(): void
    {
        $options = new TestOptions();

        $this->expectException(BadMethodCallException::class);

        $options->fieldFoobar;
    }

    public function testSetFromArrayAcceptsArray(): void
    {
        $array   = ['test_field' => 3];
        $options = new TestOptions();

        self::assertSame($options, $options->setFromArray($array));
        self::assertEquals(3, $options->test_field);
    }

    public function testSetFromArrayThrowsInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $options = new TestOptions();
        /** @psalm-suppress InvalidArgument */
        $options->setFromArray('asd');
    }

    public function testParentPublicProperty(): void
    {
        $options = new TestOptionsDerived(['parent_public' => 1]);

        self::assertEquals(1, $options->parent_public);
    }

    public function testParentProtectedProperty(): void
    {
        $options = new TestOptionsDerived(['parent_protected' => 1]);

        self::assertEquals(1, $options->parent_protected);
    }

    public function testParentPrivateProperty(): void
    {
        $this->expectException(Exception\BadMethodCallException::class);
        $this->expectExceptionMessage(
            'The option "parent_private" does not have a callable "setParentPrivate" ("setparentprivate")'
            . ' setter method which must be defined'
        );

        new TestOptionsDerived(['parent_private' => 1]);
    }

    public function testDerivedPublicProperty(): void
    {
        $options = new TestOptionsDerived(['derived_public' => 1]);

        self::assertEquals(1, $options->derived_public);
    }

    public function testDerivedProtectedProperty(): void
    {
        $options = new TestOptionsDerived(['derived_protected' => 1]);

        self::assertEquals(1, $options->derived_protected);
    }

    public function testDerivedPrivateProperty(): void
    {
        $this->expectException(Exception\BadMethodCallException::class);
        $this->expectExceptionMessage(
            'The option "derived_private" does not have a callable "setDerivedPrivate" ("setderivedprivate")'
            . ' setter method which must be defined'
        );

        new TestOptionsDerived(['derived_private' => 1]);
    }

    public function testExceptionMessageContainsActualUsedSetter(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage(
            'The option "foo bar" does not have a callable "setFooBar" ("setfoo bar")'
            . ' setter method which must be defined'
        );

        new TestOptions([
            'foo bar' => 'baz',
        ]);
    }

    #[Group('7287')]
    public function testIssetReturnsFalseWhenMatchingGetterDoesNotExist(): void
    {
        $options = new TestOptionsWithoutGetter([
            'foo' => 'bar',
        ]);
        self::assertFalse(isset($options->foo));
    }

    #[Group('7287')]
    public function testIssetDoesNotThrowExceptionWhenMatchingGetterDoesNotExist(): void
    {
        $options = new TestOptionsWithoutGetter();

        self::assertFalse(isset($options->foo));
    }

    #[Group('7287')]
    public function testIssetReturnsTrueWithValidDataWhenMatchingGetterDoesNotExist(): void
    {
        $options = new TestOptions([
            'test_field' => 1,
        ]);
        self::assertTrue(isset($options->testField));
    }
}
