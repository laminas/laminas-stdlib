<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\Hydrator\ClassMethods;
use Laminas\Stdlib\Hydrator\Reflection;
use LaminasTest\Stdlib\TestAsset\ClassMethodsCamelCase;
use LaminasTest\Stdlib\TestAsset\ClassMethodsCamelCaseMissing;
use LaminasTest\Stdlib\TestAsset\ClassMethodsInvalidParameter;
use LaminasTest\Stdlib\TestAsset\ClassMethodsUnderscore;
use LaminasTest\Stdlib\TestAsset\Reflection as ReflectionAsset;

/**
 * @category   Laminas
 * @package    Laminas_Stdlib
 * @subpackage UnitTests
 * @group      Laminas_Stdlib
 */
class HydratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ClassMethodsCamelCase
     */
    protected $classMethodsCamelCase;

    /**
     * @var ClassMethodsCamelCaseMissing
     */
    protected $classMethodsCamelCaseMissing;

    /**
     * @var ClassMethodsUnderscore
     */
    protected $classMethodsUnderscore;

    /**
     * @var ClassMethodsInvalidParameter
     */
    protected $classMethodsInvalidParameter;

    /**
     * @var ReflectionAsset
     */
    protected $reflection;

    public function setUp()
    {
        $this->classMethodsCamelCase = new ClassMethodsCamelCase();
        $this->classMethodsCamelCaseMissing = new ClassMethodsCamelCaseMissing();
        $this->classMethodsUnderscore = new ClassMethodsUnderscore();
        $this->classMethodsInvalidParameter = new ClassMethodsInvalidParameter();
        $this->reflection = new ReflectionAsset;
    }

    public function testInitiateValues()
    {
        $this->assertEquals($this->classMethodsCamelCase->getFooBar(), '1');
        $this->assertEquals($this->classMethodsCamelCase->getFooBarBaz(), '2');
        $this->assertEquals($this->classMethodsCamelCase->getIsFoo(), true);
        $this->assertEquals($this->classMethodsCamelCase->isBar(), true);
        $this->assertEquals($this->classMethodsCamelCase->getHasFoo(), true);
        $this->assertEquals($this->classMethodsCamelCase->hasBar(), true);
        $this->assertEquals($this->classMethodsUnderscore->getFooBar(), '1');
        $this->assertEquals($this->classMethodsUnderscore->getFooBarBaz(), '2');
        $this->assertEquals($this->classMethodsUnderscore->getIsFoo(), true);
        $this->assertEquals($this->classMethodsUnderscore->isBar(), true);
        $this->assertEquals($this->classMethodsUnderscore->getHasFoo(), true);
        $this->assertEquals($this->classMethodsUnderscore->hasBar(), true);
    }

    public function testHydratorReflection()
    {
        $hydrator = new Reflection;
        $datas    = $hydrator->extract($this->reflection);
        $this->assertTrue(isset($datas['foo']));
        $this->assertEquals($datas['foo'], '1');
        $this->assertTrue(isset($datas['fooBar']));
        $this->assertEquals($datas['fooBar'], '2');
        $this->assertTrue(isset($datas['fooBarBaz']));
        $this->assertEquals($datas['fooBarBaz'], '3');

        $test = $hydrator->hydrate(array('foo' => 'foo', 'fooBar' => 'bar', 'fooBarBaz' => 'baz'), $this->reflection);
        $this->assertEquals($test->foo, 'foo');
        $this->assertEquals($test->getFooBar(), 'bar');
        $this->assertEquals($test->getFooBarBaz(), 'baz');
    }

    public function testHydratorClassMethodsCamelCase()
    {
        $hydrator = new ClassMethods(false);
        $datas = $hydrator->extract($this->classMethodsCamelCase);
        $this->assertTrue(isset($datas['fooBar']));
        $this->assertEquals($datas['fooBar'], '1');
        $this->assertTrue(isset($datas['fooBarBaz']));
        $this->assertFalse(isset($datas['foo_bar']));
        $this->assertTrue(isset($datas['isFoo']));
        $this->assertEquals($datas['isFoo'], true);
        $this->assertTrue(isset($datas['isBar']));
        $this->assertEquals($datas['isBar'], true);
        $this->assertTrue(isset($datas['hasFoo']));
        $this->assertEquals($datas['hasFoo'], true);
        $this->assertTrue(isset($datas['hasBar']));
        $this->assertEquals($datas['hasBar'], true);
        $test = $hydrator->hydrate(
            array(
                'fooBar' => 'foo',
                'fooBarBaz' => 'bar',
                'isFoo' => false,
                'isBar' => false,
                'hasFoo' => false,
                'hasBar' => false,
            ),
            $this->classMethodsCamelCase
        );
        $this->assertSame($this->classMethodsCamelCase, $test);
        $this->assertEquals($test->getFooBar(), 'foo');
        $this->assertEquals($test->getFooBarBaz(), 'bar');
        $this->assertEquals($test->getIsFoo(), false);
        $this->assertEquals($test->isBar(), false);
        $this->assertEquals($test->getHasFoo(), false);
        $this->assertEquals($test->hasBar(), false);
    }

    public function testHydratorClassMethodsUnderscore()
    {
        $hydrator = new ClassMethods(true);
        $datas = $hydrator->extract($this->classMethodsUnderscore);
        $this->assertTrue(isset($datas['foo_bar']));
        $this->assertEquals($datas['foo_bar'], '1');
        $this->assertTrue(isset($datas['foo_bar_baz']));
        $this->assertFalse(isset($datas['fooBar']));
        $this->assertTrue(isset($datas['is_foo']));
        $this->assertFalse(isset($datas['isFoo']));
        $this->assertEquals($datas['is_foo'], true);
        $this->assertTrue(isset($datas['is_bar']));
        $this->assertFalse(isset($datas['isBar']));
        $this->assertEquals($datas['is_bar'], true);
        $this->assertTrue(isset($datas['has_foo']));
        $this->assertFalse(isset($datas['hasFoo']));
        $this->assertEquals($datas['has_foo'], true);
        $this->assertTrue(isset($datas['has_bar']));
        $this->assertFalse(isset($datas['hasBar']));
        $this->assertEquals($datas['has_bar'], true);
        $test = $hydrator->hydrate(
            array(
                'foo_bar' => 'foo',
                'foo_bar_baz' => 'bar',
                'is_foo' => false,
                'is_bar' => false,
                'has_foo' => false,
                'has_bar' => false,
            ),
            $this->classMethodsUnderscore
        );
        $this->assertSame($this->classMethodsUnderscore, $test);
        $this->assertEquals($test->getFooBar(), 'foo');
        $this->assertEquals($test->getFooBarBaz(), 'bar');
        $this->assertEquals($test->getIsFoo(), false);
        $this->assertEquals($test->isBar(), false);
        $this->assertEquals($test->getHasFoo(), false);
        $this->assertEquals($test->hasBar(), false);
    }

    public function testHydratorClassMethodsIgnoresInvalidValues()
    {
        $hydrator = new ClassMethods(true);
        $data = array(
            'foo_bar' => '1',
            'foo_bar_baz' => '2',
            'invalid' => 'value'
        );
        $test = $hydrator->hydrate($data, $this->classMethodsUnderscore);
        $this->assertSame($this->classMethodsUnderscore, $test);
    }

    public function testHydratorClassMethodsDefaultBehaviorIsConvertUnderscoreToCamelCase()
    {
        $hydrator = new ClassMethods();
        $datas = $hydrator->extract($this->classMethodsUnderscore);
        $this->assertTrue(isset($datas['foo_bar']));
        $this->assertEquals($datas['foo_bar'], '1');
        $this->assertTrue(isset($datas['foo_bar_baz']));
        $this->assertFalse(isset($datas['fooBar']));
        $test = $hydrator->hydrate(array('foo_bar' => 'foo', 'foo_bar_baz' => 'bar'), $this->classMethodsUnderscore);
        $this->assertSame($this->classMethodsUnderscore, $test);
        $this->assertEquals($test->getFooBar(), 'foo');
        $this->assertEquals($test->getFooBarBaz(), 'bar');
    }

    public function testHydratorClassMethodsCamelCaseWithSetterMissing()
    {
        $hydrator = new ClassMethods(false);

        $datas = $hydrator->extract($this->classMethodsCamelCaseMissing);
        $this->assertTrue(isset($datas['fooBar']));
        $this->assertEquals($datas['fooBar'], '1');
        $this->assertTrue(isset($datas['fooBarBaz']));
        $this->assertFalse(isset($datas['foo_bar']));
        $test = $hydrator->hydrate(array('fooBar' => 'foo', 'fooBarBaz' => 1), $this->classMethodsCamelCaseMissing);
        $this->assertSame($this->classMethodsCamelCaseMissing, $test);
        $this->assertEquals($test->getFooBar(), 'foo');
        $this->assertEquals($test->getFooBarBaz(), '2');
    }

    public function testHydratorClassMethodsWithServiceManager()
    {
        $hydrator = new ClassMethods(false);
        $datas = $hydrator->extract($this->classMethodsInvalidParameter);

        $this->assertTrue($datas['hasBar']);
        $this->assertEquals('Bar', $datas['foo']);
        $this->assertFalse($datas['isBla']);
    }
}
