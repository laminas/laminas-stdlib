<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\Guard;

use Laminas\Stdlib\Exception\InvalidArgumentException;
use Laminas\Stdlib\Guard\NullGuardTrait;
use LaminasTest\Stdlib\TestAsset\GuardedObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NullGuardTrait::class)]
class NullGuardTraitTest extends TestCase
{
    public function testGuardAgainstNullThrowsException(): void
    {
        $object = new GuardedObject();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument cannot be null');

        $object->setNotNull(null);
    }

    public function testGuardAgainstNullAllowsNonNull(): void
    {
        $object = new GuardedObject();
        self::assertNull($object->setNotNull('foo'));
    }
}
