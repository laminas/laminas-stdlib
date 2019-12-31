<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator;

use Laminas\Stdlib\Hydrator\Reflection;
use stdClass;

/**
 * Unit tests for {@see \Laminas\Stdlib\Hydrator\Reflection}
 *
 * @covers \Laminas\Stdlib\Hydrator\Reflection
 * @group Laminas_Stdlib
 */
class ReflectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Reflection
     */
    protected $hydrator;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->hydrator = new Reflection();
    }

    public function testCanExtract()
    {
        $this->assertSame(array(), $this->hydrator->extract(new stdClass()));
    }

    public function testCanHydrate()
    {
        $object = new stdClass();

        $this->assertSame($object, $this->hydrator->hydrate(array('foo' => 'bar'), $object));
    }
}
