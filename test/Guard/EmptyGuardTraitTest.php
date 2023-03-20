<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\Guard;

use Laminas\Stdlib\Exception\InvalidArgumentException;
use Laminas\Stdlib\Guard\EmptyGuardTrait;
use LaminasTest\Stdlib\TestAsset\GuardedObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EmptyGuardTrait::class)]
class EmptyGuardTraitTest extends TestCase
{
    public function testGuardAgainstEmptyThrowsException(): void
    {
        $object = new GuardedObject();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument cannot be empty');
        $object->setNotEmpty('');
    }

    public function testGuardAgainstEmptyAllowsNonEmptyString(): void
    {
        $object = new GuardedObject();
        self::assertNull($object->setNotEmpty('foo'));
    }
}
