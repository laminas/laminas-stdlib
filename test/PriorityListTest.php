<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\PriorityList;
use PHPUnit\Framework\TestCase;

use function array_keys;
use function count;
use function iterator_to_array;

class PriorityListTest extends TestCase
{
    /**
     * @var PriorityList
     */
    protected $list;

    protected function setUp() : void
    {
        $this->list = new PriorityList();
    }

    public function testInsert()
    {
        $this->list->insert('foo', new \stdClass(), 0);

        self::assertEquals(1, count($this->list));

        foreach ($this->list as $key => $value) {
            self::assertEquals('foo', $key);
        }
    }

    public function testInsertDuplicates()
    {
        $this->list->insert('foo', new \stdClass());
        $this->list->insert('bar', new \stdClass());

        self::assertEquals(2, count($this->list));

        $this->list->insert('foo', new \stdClass());
        $this->list->insert('foo', new \stdClass());
        $this->list->insert('bar', new \stdClass());

        self::assertEquals(2, count($this->list));

        $this->list->remove('foo');

        self::assertEquals(1, count($this->list));
    }

    public function testRemove()
    {
        $this->list->insert('foo', new \stdClass(), 0);
        $this->list->insert('bar', new \stdClass(), 0);

        self::assertEquals(2, count($this->list));

        $this->list->remove('foo');

        self::assertEquals(1, count($this->list));
    }

    public function testRemovingNonExistentRouteDoesNotYieldError()
    {
        $this->list->remove('foo');

        self::assertEmpty($this->list);
    }

    public function testClear()
    {
        $this->list->insert('foo', new \stdClass(), 0);
        $this->list->insert('bar', new \stdClass(), 0);

        self::assertEquals(2, count($this->list));

        $this->list->clear();

        self::assertEquals(0, count($this->list));
        self::assertSame(false, $this->list->current());
    }

    public function testGet()
    {
        $route = new \stdClass();

        $this->list->insert('foo', $route, 0);

        self::assertEquals($route, $this->list->get('foo'));
        self::assertNull($this->list->get('bar'));
    }

    public function testLIFOOnly()
    {
        $this->list->insert('foo', new \stdClass());
        $this->list->insert('bar', new \stdClass());
        $this->list->insert('baz', new \stdClass());
        $this->list->insert('foobar', new \stdClass());
        $this->list->insert('barbaz', new \stdClass());

        $orders = array_keys(iterator_to_array($this->list));

        self::assertEquals(['barbaz', 'foobar', 'baz', 'bar', 'foo'], $orders);
    }

    public function testPriorityOnly()
    {
        $this->list->insert('foo', new \stdClass(), 1);
        $this->list->insert('bar', new \stdClass(), 0);
        $this->list->insert('baz', new \stdClass(), 2);

        $orders = array_keys(iterator_to_array($this->list));

        self::assertEquals(['baz', 'foo', 'bar'], $orders);
    }

    public function testLIFOWithPriority()
    {
        $this->list->insert('foo', new \stdClass(), 0);
        $this->list->insert('bar', new \stdClass(), 0);
        $this->list->insert('baz', new \stdClass(), 1);

        $orders = array_keys(iterator_to_array($this->list));

        self::assertEquals(['baz', 'bar', 'foo'], $orders);
    }

    public function testFIFOWithPriority()
    {
        $this->list->isLIFO(false);
        $this->list->insert('foo', new \stdClass(), 0);
        $this->list->insert('bar', new \stdClass(), 0);
        $this->list->insert('baz', new \stdClass(), 1);

        $orders = array_keys(iterator_to_array($this->list));

        self::assertEquals(['baz', 'foo', 'bar'], $orders);
    }

    public function testFIFOOnly()
    {
        $this->list->isLIFO(false);
        $this->list->insert('foo', new \stdClass());
        $this->list->insert('bar', new \stdClass());
        $this->list->insert('baz', new \stdClass());
        $this->list->insert('foobar', new \stdClass());
        $this->list->insert('barbaz', new \stdClass());

        $orders = array_keys(iterator_to_array($this->list));

        self::assertEquals(['foo', 'bar', 'baz', 'foobar', 'barbaz'], $orders);
    }

    public function testPriorityWithNegativesAndNull()
    {
        $this->list->insert('foo', new \stdClass(), null);
        $this->list->insert('bar', new \stdClass(), 1);
        $this->list->insert('baz', new \stdClass(), -1);

        $orders = array_keys(iterator_to_array($this->list));

        self::assertEquals(['bar', 'foo', 'baz'], $orders);
    }

    public function testCurrent()
    {
        $this->list->insert('foo', 'foo_value', null);
        $this->list->insert('bar', 'bar_value', 1);
        $this->list->insert('baz', 'baz_value', -1);

        self::assertEquals('bar', $this->list->key());
        self::assertEquals('bar_value', $this->list->current());
    }

    public function testIterator()
    {
        $this->list->insert('foo', 'foo_value');
        $iterator = $this->list->getIterator();
        self::assertEquals($iterator, $this->list);

        $this->list->insert('bar', 'bar_value');
        self::assertNotEquals($iterator, $this->list);
    }

    public function testToArray()
    {
        $this->list->insert('foo', 'foo_value', null);
        $this->list->insert('bar', 'bar_value', 1);
        $this->list->insert('baz', 'baz_value', -1);

        self::assertEquals(
            [
                'bar' => 'bar_value',
                'foo' => 'foo_value',
                'baz' => 'baz_value'
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

    /**
     * @group 6768
     * @group 6773
     */
    public function testBooleanValuesAreValid()
    {
        $this->list->insert('null', null, null);
        $this->list->insert('false', false, null);
        $this->list->insert('string', 'test', 1);
        $this->list->insert('true', true, -1);

        $orders1 = [];
        $orders2 = [];

        foreach ($this->list as $key => $value) {
            $orders1[$this->list->key()] = $this->list->current();
            $orders2[$key] = $value;
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
