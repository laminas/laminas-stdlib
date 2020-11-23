<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use ArrayObject;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Stdlib\ArrayUtils\MergeRemoveKey;
use Laminas\Stdlib\Exception\InvalidArgumentException;
use Laminas\Stdlib\Parameters;
use PHPUnit\Framework\TestCase;
use stdClass;

class ArrayUtilsTest extends TestCase
{
    /**
     * @psalm-return array<array-key, array<array-key, mixed>>
     */
    public static function validHashTables(): array
    {
        return [
            [[
                'foo' => 'bar'
            ]],
            [[
                '15',
                'foo' => 'bar',
                'baz' => ['baz']
            ]],
            [[
                0 => false,
                2 => null
            ]],
            [[
                -100 => 'foo',
                100  => 'bar'
            ]],
            [[
                1 => 0
            ]],
        ];
    }

    /**
     * @psalm-return array<array-key, array<array-key, mixed>>
     */
    public static function validLists(): array
    {
        return [
            [[null]],
            [[true]],
            [[false]],
            [[0]],
            [[-0.9999]],
            [['string']],
            [[new stdClass]],
            [[
                0 => 'foo',
                1 => 'bar',
                2 => false,
                3 => null,
                4 => [],
                5 => new stdClass()
            ]]
        ];
    }

    /**
     * @psalm-return array<array-key, array<array-key, mixed>>
     */
    public static function validArraysWithStringKeys(): array
    {
        return [
            [[
                'foo' => 'bar',
            ]],
            [[
                'bar',
                'foo' => 'bar',
                'baz',
            ]],
        ];
    }

    /**
     * @psalm-return array<array-key, array<array-key, mixed>>
     */
    public static function validArraysWithNumericKeys(): array
    {
        return [
            [[
                'foo',
                'bar'
            ]],
            [[
                '0' => 'foo',
                '1' => 'bar',
            ]],
            [[
                'bar',
                '1' => 'bar',
                 3  => 'baz'
            ]],
            [[
                -10000   => null,
                '-10000' => null,
            ]],
            [[
                '-00000.00009' => 'foo'
            ]],
            [[
                1 => 0
            ]],
        ];
    }

    /**
     * @psalm-return array<array-key, array<array-key, mixed>>
     */
    public static function validArraysWithIntegerKeys(): array
    {
        return [
            [[
                'foo',
                'bar,'
            ]],
            [[
                100 => 'foo',
                200 => 'bar'
            ]],
            [[
                -100 => 'foo',
                0    => 'bar',
                100  => 'baz'
            ]],
            [[
                'foo',
                'bar',
                1000 => 'baz'
            ]],
        ];
    }

    /**
     * @psalm-return array<array-key, array<array-key, mixed>>
     */
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
     * @psalm-return array<array-key, array<array-key, mixed>>
     */
    public static function mergeArrays(): array
    {
        return [
            'merge-integer-and-string-keys' => [
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
                ]
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
                    4 => [
                        'a',
                        1 => 'b',
                        'c',
                        'd' => 'd',
                    ],
                ]
            ],
            'merge-arrays-recursively' => [
                [
                    'foo' => [
                        'baz'
                    ]
                ],
                [
                    'foo' => [
                        'baz'
                    ]
                ],
                false,
                [
                    'foo' => [
                        0 => 'baz',
                        1 => 'baz'
                    ]
                ]
            ],
            'replace-string-keys' => [
                [
                    'foo' => 'bar',
                    'bar' => []
                ],
                [
                    'foo' => 'baz',
                    'bar' => 'bat'
                ],
                false,
                [
                    'foo' => 'baz',
                    'bar' => 'bat'
                ]
            ],
            'merge-with-null' => [
                [
                    'foo' => null,
                    null  => 'rod',
                    'cat' => 'bar',
                    'god' => 'rad'
                ],
                [
                    'foo' => 'baz',
                    null  => 'zad',
                    'god' => null
                ],
                false,
                [
                    'foo' => 'baz',
                    null  => 'zad',
                    'cat' => 'bar',
                    'god' => null
                ]
            ],
        ];
    }

    /**
     * @group 6903
     */
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
        $a = [
            'car' => [
                'boo' => 'foo',
                'doo' => 'moo',
            ],
        ];
        $b = [
            'car' => new \Laminas\Stdlib\ArrayUtils\MergeReplaceKey([
                'met' => 'bet',
            ]),
            'new' => new \Laminas\Stdlib\ArrayUtils\MergeReplaceKey([
                'foo' => 'get',
            ]),
        ];
        self::assertInstanceOf(ArrayUtils\MergeReplaceKeyInterface::class, $b['car']);
        self::assertEquals($expected, ArrayUtils::merge($a, $b));
    }

    /**
     * @group 6899
     */
    public function testAllowsRemovingKeys(): void
    {
        $a = [
            'foo' => 'bar',
            'bar' => 'bat'
        ];
        $b = [
            'foo' => new MergeRemoveKey(),
            'baz' => new MergeRemoveKey(),
        ];
        $expected = [
            'bar' => 'bat'
        ];
        self::assertEquals($expected, ArrayUtils::merge($a, $b));
    }

    /**
     * @psalm-return array<array-key, array<array-key, mixed>>
     */
    public static function validIterators(): array
    {
        $array = [
            'foo' => [
                'bar' => [
                    'baz' => [
                        'baz' => 'bat',
                    ],
                ],
            ],
        ];
        $arrayAccess = new ArrayObject($array);
        $toArray = new Parameters($array);

        return [
            // Description => [input, expected array]
            'array' => [$array, $array],
            'Traversable' => [$arrayAccess, $array],
            'Traversable and toArray' => [$toArray, $array],
        ];
    }

    /**
     * @psalm-return array<array-key, array<array-key, mixed>>
     */
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
            [new stdClass],
        ];
    }

    /**
     * @dataProvider validArraysWithStringKeys
     */
    public function testValidArraysWithStringKeys($test): void
    {
        self::assertTrue(ArrayUtils::hasStringKeys($test));
    }

    /**
     * @dataProvider validArraysWithIntegerKeys
     */
    public function testValidArraysWithIntegerKeys($test): void
    {
        self::assertTrue(ArrayUtils::hasIntegerKeys($test));
    }

    /**
     * @dataProvider validArraysWithNumericKeys
     */
    public function testValidArraysWithNumericKeys($test): void
    {
        self::assertTrue(ArrayUtils::hasNumericKeys($test));
    }

    /**
     * @dataProvider invalidArrays
     */
    public function testInvalidArraysAlwaysReturnFalse($test): void
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

    /**
     * @dataProvider validLists
     */
    public function testLists($test): void
    {
        self::assertTrue(ArrayUtils::isList($test));
        self::assertTrue(ArrayUtils::hasIntegerKeys($test));
        self::assertTrue(ArrayUtils::hasNumericKeys($test));
        self::assertFalse(ArrayUtils::hasStringKeys($test));
        self::assertFalse(ArrayUtils::isHashTable($test));
    }

    /**
     * @dataProvider validHashTables
     */
    public function testHashTables($test): void
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

    /**
     * @dataProvider mergeArrays
     */
    public function testMerge($a, $b, $preserveNumericKeys, $expected): void
    {
        self::assertEquals($expected, ArrayUtils::merge($a, $b, $preserveNumericKeys));
    }

    /**
     * @dataProvider validIterators
     */
    public function testValidIteratorsReturnArrayRepresentation($test, $expected): void
    {
        $result = ArrayUtils::iteratorToArray($test);
        self::assertEquals($expected, $result);
    }

    /**
     * @dataProvider invalidIterators
     */
    public function testInvalidIteratorsRaiseInvalidArgumentException($test): void
    {
        $this->expectException(InvalidArgumentException::class);
        self::assertFalse(ArrayUtils::iteratorToArray($test));
    }

    /**
     * @psalm-return array<array-key, array<array-key, mixed>>
     */
    public function filterArrays(): array
    {
        return [
            [
                ['foo' => 'bar', 'fiz' => 'buz'],
                function ($value) {
                    if ($value == 'bar') {
                        return false;
                    }
                    return true;
                },
                null,
                ['fiz' => 'buz']
            ],
            [
                ['foo' => 'bar', 'fiz' => 'buz'],
                function ($value, $key) {
                    if ($value == 'buz') {
                        return false;
                    }

                    if ($key == 'foo') {
                        return false;
                    }

                    return true;
                },
                ArrayUtils::ARRAY_FILTER_USE_BOTH,
                []
            ],
            [
                ['foo' => 'bar', 'fiz' => 'buz'],
                function ($key) {
                    if ($key == 'foo') {
                        return false;
                    }
                    return true;
                },
                ArrayUtils::ARRAY_FILTER_USE_KEY,
                ['fiz' => 'buz']
            ],
        ];
    }

    /**
     * @dataProvider filterArrays
     */
    public function testFiltersArray($data, $callback, $flag, $result): void
    {
        self::assertEquals($result, ArrayUtils::filter($data, $callback, $flag));
    }

    public function testInvalidCallableRaiseInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ArrayUtils::filter([], "INVALID");
    }
}
