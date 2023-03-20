<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\ArrayStack;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function iterator_to_array;

#[CoversClass(ArrayStack::class)]
final class ArrayStackTest extends TestCase
{
    public function testIteration(): void
    {
        self::assertSame(
            ['c', 'b', 'a'],
            iterator_to_array(new ArrayStack(['a', 'b', 'c']))
        );
    }
}
