<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator;

use Laminas\Stdlib\Hydrator\ClassMethods;
use LaminasTest\Stdlib\TestAsset\ClassMethodsOptionalParameters;

/**
 * Unit tests for {@see \Laminas\Stdlib\Hydrator\ClassMethods}
 *
 * @covers \Laminas\Stdlib\Hydrator\ClassMethods
 * @group Laminas_Stdlib
 */
class ClassMethodsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClassMethods
     */
    protected $hydrator;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->hydrator = new ClassMethods();
    }

    /**
     * Verifies that extraction can happen even when a getter has parameters if those are all optional
     */
    public function testCanExtractFromMethodsWithOptionalParameters()
    {
        $this->assertSame(array('foo' => 'bar'), $this->hydrator->extract(new ClassMethodsOptionalParameters()));
    }
}
