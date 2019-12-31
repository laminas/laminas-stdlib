<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\Hydrator\Filter\GetFilter;
use Laminas\Stdlib\Hydrator\Filter\HasFilter;
use Laminas\Stdlib\Hydrator\Filter\IsFilter;

class FilterTest extends \PHPUnit_Framework_TestCase
{
    public function testHasValidation()
    {
        $hasValidation = new HasFilter();
        $this->assertTrue($hasValidation->filter('hasFoo'));
        $this->assertTrue($hasValidation->filter('Foo::hasFoo'));
        $this->assertFalse($hasValidation->filter('FoohasFoo'));
        $this->assertFalse($hasValidation->filter('Bar::FoohasFoo'));
        $this->assertFalse($hasValidation->filter('hAsFoo'));
        $this->assertFalse($hasValidation->filter('Blubb::hAsFoo'));
        $this->assertFalse($hasValidation->filter(get_class($this). '::hAsFoo'));
    }

    public function testGetValidation()
    {
        $hasValidation = new GetFilter();
        $this->assertTrue($hasValidation->filter('getFoo'));
        $this->assertTrue($hasValidation->filter('Bar::getFoo'));
        $this->assertFalse($hasValidation->filter('GetFooBar'));
        $this->assertFalse($hasValidation->filter('Foo::GetFooBar'));
        $this->assertFalse($hasValidation->filter('GETFoo'));
        $this->assertFalse($hasValidation->filter('Blubb::GETFoo'));
        $this->assertFalse($hasValidation->filter(get_class($this).'::GETFoo'));
    }

    public function testIsValidation()
    {
        $hasValidation = new IsFilter();
        $this->assertTrue($hasValidation->filter('isFoo'));
        $this->assertTrue($hasValidation->filter('Blubb::isFoo'));
        $this->assertFalse($hasValidation->filter('IsFooBar'));
        $this->assertFalse($hasValidation->filter('Foo::IsFooBar'));
        $this->assertFalse($hasValidation->filter('ISFoo'));
        $this->assertFalse($hasValidation->filter('Bar::ISFoo'));
        $this->assertFalse($hasValidation->filter(get_class($this).'::ISFoo'));
    }

}
