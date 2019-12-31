<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator;

use Laminas\Stdlib\Hydrator\ClassMethods;
use LaminasTest\Stdlib\TestAsset\ArraySerializable;
use LaminasTest\Stdlib\TestAsset\ClassMethodsCamelCase;
use LaminasTest\Stdlib\TestAsset\ClassMethodsCamelCaseMissing;
use LaminasTest\Stdlib\TestAsset\ClassMethodsOptionalParameters;

/**
 * Unit tests for {@see \Laminas\Stdlib\Hydrator\ClassMethods}
 *
 * @covers \Laminas\Stdlib\Hydrator\ClassMethods
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
        $this->assertSame(['foo' => 'bar'], $this->hydrator->extract(new ClassMethodsOptionalParameters()));
    }

    /**
     * Verifies that the hydrator can act on different instance types
     */
    public function testCanHydratedPromiscuousInstances()
    {
        /* @var $classMethodsCamelCase ClassMethodsCamelCase */
        $classMethodsCamelCase = $this->hydrator->hydrate(
            ['fooBar' => 'baz-tab'],
            new ClassMethodsCamelCase()
        );
        /* @var $classMethodsCamelCaseMissing ClassMethodsCamelCaseMissing */
        $classMethodsCamelCaseMissing = $this->hydrator->hydrate(
            ['fooBar' => 'baz-tab'],
            new ClassMethodsCamelCaseMissing()
        );
        /* @var $arraySerializable ArraySerializable */
        $arraySerializable = $this->hydrator->hydrate(['fooBar' => 'baz-tab'], new ArraySerializable());

        $this->assertSame('baz-tab', $classMethodsCamelCase->getFooBar());
        $this->assertSame('baz-tab', $classMethodsCamelCaseMissing->getFooBar());
        $this->assertSame(
            [
                "foo" => "bar",
                "bar" => "foo",
                "blubb" => "baz",
                "quo" => "blubb"
            ],
            $arraySerializable->getArrayCopy()
        );
    }
}
