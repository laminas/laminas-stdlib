<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use ArrayObject;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Stdlib\ArrayUtils\MergeRemoveKey;
use Laminas\Stdlib\ArrayUtils\MergeReplaceKey;
use Laminas\Stdlib\ArrayUtils\MergeReplaceKeyInterface;
use Laminas\Stdlib\Exception\InvalidArgumentException;
use Laminas\Stdlib\Parameters;
use LaminasTest\Stdlib\TestAsset\IteratorWithToArrayMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use stdClass;
use Traversable;

class ArrayUtilsTest extends TestCase
{
    /** @psalm-return array<array-key, array{0: array}> */
    public static function validHashTables(): array
    {
        return [
            [
                [
                    'foo' => 'bar',
                ],
            ],
            [
                [
                    '15',
                    'foo' => 'bar',
                    'baz' => ['baz'],
                ],
            ],
            [
                [
                    0 => false,
                    2 => null,
                ],
            ],
            [
                [
                    -100 => 'foo',
                    100  => 'bar',
                ],
            ],
            [
                [
                    1 => 0,
                ],
            ],
        ];
    }

    /** @psalm-return array<array-key, array{0: array}> */
    public static function validLists(): array
    {
        return [
            [[null]],
            [[true]],
            [[false]],
            [[0]],
            [[-0.9999]],
            [['string']],
            [[new stdClass()]],
            [
                [
                    0 => 'foo',
                    1 => 'bar',
                    2 => false,
                    3 => null,
                    4 => [],
                    5 => new stdClass(),
                ],
            ],
        ];
    }

    /** @psalm-return array<array-key, array{0: array}> */
    public static function validArraysWithStringKeys(): array
    {
        return [
            [
                [
                    'foo' => 'bar',
                ],
            ],
            [
                [
                    'bar',
                    'foo' => 'bar',
                    'baz',
                ],
            ],
        ];
    }

    /** @psalm-return array<array-key, array{0: array}> */
    public static function validArraysWithNumericKeys(): array
    {
        return [
            [
                [
                    'foo',
                    'bar',
                ],
            ],
            [
                [
                    '0' => 'foo',
                    '1' => 'bar',
                ],
            ],
            [
                [
                    'bar',
                    '1' => 'bar',
                    3   => 'baz',
                ],
            ],
            [
                [
                    -10000   => null,
                    '-10000' => null,
                ],
            ],
            [
                [
                    '-00000.00009' => 'foo',
                ],
            ],
            [
                [
                    1 => 0,
                ],
            ],
        ];
    }

    /** @psalm-return array<array-key, array{0: array}> */
    public static function validArraysWithIntegerKeys(): array
    {
        return [
            [
                [
                    'foo',
                    'bar,',
                ],
            ],
            [
                [
                    100 => 'foo',
                    200 => 'bar',
                ],
            ],
            [
                [
                    -100 => 'foo',
                    0    => 'bar',
                    100  => 'baz',
                ],
            ],
            [
                [
                    'foo',
                    'bar',
                    1000 => 'baz',
                ],
            ],
        ];
    }

    /** @psalm-return array<array-key, array{0: object|int|string}> */
    public static function invalidArrays(): array
    {
        return [
            [new stdClass()],
            [15],
            ['foo'],
            [new ArrayObject()],
        ];
    }

    /**
     * @psalm-return array<string, array{
     *     0: array,
     *     1: array,
     *     2: bool,
     *     3: array
     * }>
     */
    public static function mergeArrays(): array
    {
        return [
            'merge-integer-and-string-keys'                  => [
                [
                    'foo',
                    3     => 'bar',
                    'baz' => 'baz',
                    4     => [
                        'a',
                        1 => 'b',
                        'c',
                    ],
                ],
                [
                    'baz',
                    4 => [
                        'd' => 'd',
                    ],
                ],
                false,
                [
                    0     => 'foo',
                    3     => 'bar',
                    'baz' => 'baz',
                    4     => [
                        'a',
                        1 => 'b',
                        'c',
                    ],
                    5     => 'baz',
                    6     => [
                        'd' => 'd',
                    ],
                ],
            ],
            'merge-integer-and-string-keys-preserve-numeric' => [
                [
                    'foo',
                    3     => 'bar',
                    'baz' => 'baz',
                    4     => [
                        'a',
                        1 => 'b',
                        'c',
                    ],
                ],
                [
                    'baz',
                    4 => [
                        'd' => 'd',
                    ],
                ],
                true,
                [
                    0     => 'baz',
                    3     => 'bar',
                    'baz' => 'baz',
                    4     => [
                        'a',
                        1 => 'b',
                        'c',
                        'd' => 'd',
                    ],
                ],
            ],
            'merge-arrays-recursively'                       => [
                [
                    'foo' => [
                        'baz',
                    ],
                ],
                [
                    'foo' => [
                        'baz',
                    ],
                ],
                false,
                [
                    'foo' => [
                        0 => 'baz',
                        1 => 'baz',
                    ],
                ],
            ],
            'replace-string-keys'                            => [
                [
                    'foo' => 'bar',
                    'bar' => [],
                ],
                [
                    'foo' => 'baz',
                    'bar' => 'bat',
                ],
                false,
                [
                    'foo' => 'baz',
                    'bar' => 'bat',
                ],
            ],
            'merge-with-null'                                => [
                [
                    'foo' => null,
                    null  => 'rod',
                    'cat' => 'bar',
                    'god' => 'rad',
                ],
                [
                    'foo' => 'baz',
                    null  => 'zad',
                    'god' => null,
                ],
                false,
                [
                    'foo' => 'baz',
                    null  => 'zad',
                    'cat' => 'bar',
                    'god' => null,
                ],
            ],
        ];
    }

    #[Group('6903')]
    public function testMergeReplaceKey(): void
    {
        $expected = [
            'car' => [
                'met' => 'bet',
            ],
            'new' => [
                'foo' => 'get',
            ],
        ];
        $a        = [
            'car' => [
                'boo' => 'foo',
                'doo' => 'moo',
            ],
        ];
        $b        = [
            'car' => new MergeReplaceKey([
                'met' => 'bet',
            ]),
            'new' => new MergeReplaceKey([
                'foo' => 'get',
            ]),
        ];
        self::assertInstanceOf(MergeReplaceKeyInterface::class, $b['car']);
        self::assertEquals($expected, ArrayUtils::merge($a, $b));
    }

    #[Group('6899')]
    public function testAllowsRemovingKeys(): void
    {
        $a        = [
            'foo' => 'bar',
            'bar' => 'bat',
        ];
        $b        = [
            'foo' => new MergeRemoveKey(),
            'baz' => new MergeRemoveKey(),
        ];
        $expected = [
            'bar' => 'bat',
        ];
        self::assertEquals($expected, ArrayUtils::merge($a, $b));
    }

    /** @psalm-return array<string, array{0: iterable, 1: array}> */
    public static function validIterators(): array
    {
        $array       = [
            'foo' => [
                'bar' => [
                    'baz' => [
                        'baz' => 'bat',
                    ],
                ],
            ],
        ];
        $arrayAccess = new ArrayObject($array);
        $toArray     = new Parameters($array);

        return [
            // Description => [input, expected array]
            'array'                   => [$array, $array],
            'Traversable'             => [$arrayAccess, $array],
            'Traversable and toArray' => [$toArray, $array],
        ];
    }

    /** @psalm-return array<array-key, array{0: mixed}> */
    public static function invalidIterators(): array
    {
        return [
            [null],
            [true],
            [false],
            [0],
            [1],
            [0.0],
            [1.0],
            ['string'],
            [new stdClass()],
        ];
    }

    #[DataProvider('validArraysWithStringKeys')]
    public function testValidArraysWithStringKeys(array $test): void
    {
        self::assertTrue(ArrayUtils::hasStringKeys($test));
    }

    #[DataProvider('validArraysWithIntegerKeys')]
    public function testValidArraysWithIntegerKeys(array $test): void
    {
        self::assertTrue(ArrayUtils::hasIntegerKeys($test));
    }

    #[DataProvider('validArraysWithNumericKeys')]
    public function testValidArraysWithNumericKeys(array $test): void
    {
        self::assertTrue(ArrayUtils::hasNumericKeys($test));
    }

    #[DataProvider('invalidArrays')]
    public function testInvalidArraysAlwaysReturnFalse(mixed $test): void
    {
        self::assertFalse(ArrayUtils::hasStringKeys($test, false));
        self::assertFalse(ArrayUtils::hasIntegerKeys($test, false));
        self::assertFalse(ArrayUtils::hasNumericKeys($test, false));
        self::assertFalse(ArrayUtils::isList($test, false));
        self::assertFalse(ArrayUtils::isHashTable($test, false));

        self::assertFalse(ArrayUtils::hasStringKeys($test, false));
        self::assertFalse(ArrayUtils::hasIntegerKeys($test, false));
        self::assertFalse(ArrayUtils::hasNumericKeys($test, false));
        self::assertFalse(ArrayUtils::isList($test, false));
        self::assertFalse(ArrayUtils::isHashTable($test, false));
    }

    #[DataProvider('validLists')]
    public function testLists(array $test): void
    {
        self::assertTrue(ArrayUtils::isList($test));
        self::assertTrue(ArrayUtils::hasIntegerKeys($test));
        self::assertTrue(ArrayUtils::hasNumericKeys($test));
        self::assertFalse(ArrayUtils::hasStringKeys($test));
        self::assertFalse(ArrayUtils::isHashTable($test));
    }

    #[DataProvider('validHashTables')]
    public function testHashTables(array $test): void
    {
        self::assertTrue(ArrayUtils::isHashTable($test));
        self::assertFalse(ArrayUtils::isList($test));
    }

    public function testEmptyArrayReturnsTrue(): void
    {
        $test = [];
        self::assertTrue(ArrayUtils::hasStringKeys($test, true));
        self::assertTrue(ArrayUtils::hasIntegerKeys($test, true));
        self::assertTrue(ArrayUtils::hasNumericKeys($test, true));
        self::assertTrue(ArrayUtils::isList($test, true));
        self::assertTrue(ArrayUtils::isHashTable($test, true));
    }

    public function testEmptyArrayReturnsFalse(): void
    {
        $test = [];
        self::assertFalse(ArrayUtils::hasStringKeys($test, false));
        self::assertFalse(ArrayUtils::hasIntegerKeys($test, false));
        self::assertFalse(ArrayUtils::hasNumericKeys($test, false));
        self::assertFalse(ArrayUtils::isList($test, false));
        self::assertFalse(ArrayUtils::isHashTable($test, false));
    }

    #[DataProvider('mergeArrays')]
    public function testMerge(array $a, array $b, bool $preserveNumericKeys, array $expected): void
    {
        self::assertEquals($expected, ArrayUtils::merge($a, $b, $preserveNumericKeys));
    }

    /**
     * @param Traversable|array $test
     */
    #[DataProvider('validIterators')]
    public function testValidIteratorsReturnArrayRepresentation(iterable $test, array $expected): void
    {
        $result = ArrayUtils::iteratorToArray($test);
        self::assertEquals($expected, $result);
    }

    #[DataProvider('invalidIterators')]
    public function testInvalidIteratorsRaiseInvalidArgumentException(mixed $test): void
    {
        $this->expectException(InvalidArgumentException::class);
        self::assertFalse(ArrayUtils::iteratorToArray($test));
    }

    /**
     * @psalm-return list<array{
     *     0: array<string, string>,
     *     1: callable(string, int|string=):bool,
     *     2: null|int,
     *     3: array<string, string>
     * }>
     */
    public static function filterArrays(): array
    {
        return [
            [
                ['foo' => 'bar', 'fiz' => 'buz'],
                static function (string $value): bool {
                    if ($value === 'bar') {
                        return false;
                    }
                    return true;
                },
                null,
                ['fiz' => 'buz'],
            ],
            [
                ['foo' => 'bar', 'fiz' => 'buz'],
                static function (string $value, int|string $key): bool {
                    if ($value === 'buz') {
                        return false;
                    }
                    if ($key === 'foo') {
                        return false;
                    }
                    return true;
                },
                ArrayUtils::ARRAY_FILTER_USE_BOTH,
                [],
            ],
            [
                ['foo' => 'bar', 'fiz' => 'buz'],
                static function (string $key): bool {
                    if ($key === 'foo') {
                        return false;
                    }
                    return true;
                },
                ArrayUtils::ARRAY_FILTER_USE_KEY,
                ['fiz' => 'buz'],
            ],
        ];
    }

    #[DataProvider('filterArrays')]
    public function testFiltersArray(array $data, callable $callback, ?int $flag, array $result): void
    {
        self::assertEquals($result, ArrayUtils::filter($data, $callback, $flag));
    }

    public function testInvalidCallableRaiseInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ArrayUtils::filter([], "INVALID");
    }

    /**
     * @link https://github.com/laminas/laminas-stdlib/issues/18
     */
    public function testIteratorToArrayWithIteratorHavingMethodToArrayAndRecursiveIsFalse(): void
    {
        $arrayB    = [
            'foo' => 'bar',
        ];
        $iteratorB = new IteratorWithToArrayMethod($arrayB);

        $arrayA   = [
            'iteratorB' => $iteratorB,
        ];
        $iterator = new IteratorWithToArrayMethod($arrayA);

        $result = ArrayUtils::iteratorToArray($iterator, true);

        $expectedResult = [
            'iteratorB' => [
                'foo' => 'bar',
            ],
        ];

        $this->assertEquals($expectedResult, $result);
    }
}
