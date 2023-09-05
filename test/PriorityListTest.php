<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\PriorityList;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use stdClass;

use function array_keys;
use function iterator_to_array;

class PriorityListTest extends TestCase
{
    /** @var PriorityList<string, mixed> */
    protected $list;

    protected function setUp(): void
    {
        $this->list = new PriorityList();
    }

    public function testInsert(): void
    {
        $this->list->insert('foo', new stdClass(), 0);

        self::assertCount(1, $this->list);

        $values = $this->list->toArray();
        self::assertArrayHasKey('foo', $values);
    }

    public function testInsertDuplicates(): void
    {
        $this->list->insert('foo', new stdClass());
        $this->list->insert('bar', new stdClass());

        self::assertCount(2, $this->list);

        $this->list->insert('foo', new stdClass());
        $this->list->insert('foo', new stdClass());
        $this->list->insert('bar', new stdClass());

        self::assertCount(2, $this->list);

        $this->list->remove('foo');

        self::assertCount(1, $this->list);
    }

    public function testRemove(): void
    {
        $this->list->insert('foo', new stdClass(), 0);
        $this->list->insert('bar', new stdClass(), 0);

        self::assertCount(2, $this->list);

        $this->list->remove('foo');

        self::assertCount(1, $this->list);
    }

    public function testRemovingNonExistentRouteDoesNotYieldError(): void
    {
        $this->list->remove('foo');

        self::assertEmpty($this->list->toArray());
    }

    public function testClear(): void
    {
        $this->list->insert('foo', new stdClass(), 0);
        $this->list->insert('bar', new stdClass(), 0);

        self::assertCount(2, $this->list);

        $this->list->clear();

        self::assertCount(0, $this->list);
        self::assertSame(false, $this->list->current());
    }

    public function testGet(): void
    {
        $route = new stdClass();

        $this->list->insert('foo', $route, 0);

        self::assertEquals($route, $this->list->get('foo'));
        self::assertNull($this->list->get('bar'));
    }

    public function testLIFOOnly(): void
    {
        $this->list->insert('foo', new stdClass());
        $this->list->insert('bar', new stdClass());
        $this->list->insert('baz', new stdClass());
        $this->list->insert('foobar', new stdClass());
        $this->list->insert('barbaz', new stdClass());

        $orders = array_keys(iterator_to_array($this->list));

        self::assertEquals(['barbaz', 'foobar', 'baz', 'bar', 'foo'], $orders);
    }

    public function testPriorityOnly(): void
    {
        $this->list->insert('foo', new stdClass(), 1);
        $this->list->insert('bar', new stdClass(), 0);
        $this->list->insert('baz', new stdClass(), 2);

        $orders = array_keys(iterator_to_array($this->list));

        self::assertEquals(['baz', 'foo', 'bar'], $orders);
    }

    public function testLIFOWithPriority(): void
    {
        $this->list->insert('foo', new stdClass(), 0);
        $this->list->insert('bar', new stdClass(), 0);
        $this->list->insert('baz', new stdClass(), 1);

        $orders = array_keys(iterator_to_array($this->list));

        self::assertEquals(['baz', 'bar', 'foo'], $orders);
    }

    public function testFIFOWithPriority(): void
    {
        $this->list->isLIFO(false);
        $this->list->insert('foo', new stdClass(), 0);
        $this->list->insert('bar', new stdClass(), 0);
        $this->list->insert('baz', new stdClass(), 1);

        $orders = array_keys(iterator_to_array($this->list));

        self::assertEquals(['baz', 'foo', 'bar'], $orders);
    }

    public function testFIFOOnly(): void
    {
        $this->list->isLIFO(false);
        $this->list->insert('foo', new stdClass());
        $this->list->insert('bar', new stdClass());
        $this->list->insert('baz', new stdClass());
        $this->list->insert('foobar', new stdClass());
        $this->list->insert('barbaz', new stdClass());

        $orders = array_keys(iterator_to_array($this->list));

        self::assertEquals(['foo', 'bar', 'baz', 'foobar', 'barbaz'], $orders);
    }

    public function testPriorityWithNegativesAndNull(): void
    {
        $this->list->insert('foo', new stdClass());
        $this->list->insert('bar', new stdClass(), 1);
        $this->list->insert('baz', new stdClass(), -1);

        $orders = array_keys(iterator_to_array($this->list));

        self::assertEquals(['bar', 'foo', 'baz'], $orders);
    }

    public function testCurrent(): void
    {
        $this->list->insert('foo', 'foo_value');
        $this->list->insert('bar', 'bar_value', 1);
        $this->list->insert('baz', 'baz_value', -1);

        self::assertEquals('bar', $this->list->key());
        self::assertEquals('bar_value', $this->list->current());
    }

    public function testIterator(): void
    {
        $this->list->insert('foo', 'foo_value');
        $iterator = $this->list->getIterator();
        self::assertEquals($iterator, $this->list);

        $this->list->insert('bar', 'bar_value');
        self::assertNotEquals($iterator, $this->list);
    }

    public function testToArray(): void
    {
        $this->list->insert('foo', 'foo_value');
        $this->list->insert('bar', 'bar_value', 1);
        $this->list->insert('baz', 'baz_value', -1);

        self::assertEquals(
            [
                'bar' => 'bar_value',
                'foo' => 'foo_value',
                'baz' => 'baz_value',
            ],
            $this->list->toArray()
        );

        self::assertEquals(
            [
                'bar' => ['data' => 'bar_value', 'priority' => 1, 'serial' => 1],
                'foo' => ['data' => 'foo_value', 'priority' => 0, 'serial' => 0],
                'baz' => ['data' => 'baz_value', 'priority' => -1, 'serial' => 2],
            ],
            $this->list->toArray(PriorityList::EXTR_BOTH)
        );
    }

    /** @psalm-suppress MixedAssignment */
    #[Group('6768')]
    #[Group('6773')]
    public function testBooleanValuesAreValid(): void
    {
        $this->list->insert('null', null);
        $this->list->insert('false', false);
        $this->list->insert('string', 'test', 1);
        $this->list->insert('true', true, -1);

        $orders1 = [];
        $orders2 = [];

        foreach ($this->list as $key => $value) {
            $orders1[$this->list->key()] = $this->list->current();
            $orders2[$key]               = $value;
        }
        self::assertEquals($orders1, $orders2);
        self::assertEquals(
            [
                'null'   => null,
                'false'  => false,
                'string' => 'test',
                'true'   => true,
            ],
            $orders2
        );
    }
}
