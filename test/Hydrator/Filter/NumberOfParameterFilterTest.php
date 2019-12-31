<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator\Filter;

use Laminas\Stdlib\Hydrator\Filter\NumberOfParameterFilter;

/**
 * Unit tests for {@see \Laminas\Stdlib\Hydrator\Filter\NumberOfParameterFilter}
 *
 * @covers \Laminas\Stdlib\Hydrator\Filter\NumberOfParameterFilter
 */
class NumberOfParameterFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group 6083
     */
    public function testArityZero()
    {
        $filter = new NumberOfParameterFilter();
        $this->assertTrue($filter->filter(__CLASS__ . '::methodWithNoParameters'));
        $this->assertFalse($filter->filter(__CLASS__ . '::methodWithOptionalParameters'));
    }

    /**
     * @group 6083
     */
    public function testArityOne()
    {
        $filter = new NumberOfParameterFilter(1);
        $this->assertFalse($filter->filter(__CLASS__ . '::methodWithNoParameters'));
        $this->assertTrue($filter->filter(__CLASS__ . '::methodWithOptionalParameters'));
    }

    /**
     * Test asset method
     */
    public function methodWithOptionalParameters($parameter = 'foo')
    {
    }

    /**
     * Test asset method
     */
    public function methodWithNoParameters()
    {
    }
}
