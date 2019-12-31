<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use PHPUnit_Framework_TestCase as TestCase;

class HydratorDeprecationTest extends TestCase
{
    public function testPassingHydratorExtendingStdlibAbstractHydratorToTypehintedMethodShouldWork()
    {
        $hydratorInjected = new TestAsset\HydratorInjectedObject();
        $hydrator         = new TestAsset\ClassMethodsExtendsHydrator();
        set_error_handler(function ($errno, $errstr) {
            $this->fail('Catchable fatal error was triggered: ' . $errstr);
        }, E_RECOVERABLE_ERROR);
        $hydratorInjected->setHydrator($hydrator);
        $this->assertSame($hydrator, $hydratorInjected->hydrator);
    }

    public function testDeprecatedHydratorInterfaceIsAcceptedByMethodsTypehintedWithNewInterface()
    {
        $hydratorInjected = new TestAsset\HydratorInjectedObjectUsingDeprecatedInterfaceTypehint();
        $hydrator         = new TestAsset\DeprecatedInterfaceHydrator();
        set_error_handler(function ($errno, $errstr) {
            $this->fail('Catchable fatal error was triggered: ' . $errstr);
        }, E_RECOVERABLE_ERROR);
        $hydratorInjected->setHydrator($hydrator);
        $this->assertSame($hydrator, $hydratorInjected->hydrator);
    }

    public function testDeprecatedHydratorInterfaceIsAcceptedByMethodsTypehintedWithDeprecatedHydrationInterface()
    {
        $hydratorInjected = new TestAsset\HydratorInjectedObjectUsingDeprecatedHydrationInterfaceTypehint();
        $hydrator         = new TestAsset\DeprecatedInterfaceHydrator();
        set_error_handler(function ($errno, $errstr) {
            $this->fail('Catchable fatal error was triggered: ' . $errstr);
        }, E_RECOVERABLE_ERROR);
        $hydratorInjected->setHydrator($hydrator);
        $this->assertSame($hydrator, $hydratorInjected->hydrator);
    }

    public function testDeprecatedHydratorInterfaceIsAcceptedByMethodsTypehintedWithDeprecatedExtractionInterface()
    {
        $hydratorInjected = new TestAsset\HydratorInjectedObjectUsingDeprecatedExtractionInterfaceTypehint();
        $hydrator         = new TestAsset\DeprecatedInterfaceHydrator();
        set_error_handler(function ($errno, $errstr) {
            $this->fail('Catchable fatal error was triggered: ' . $errstr);
        }, E_RECOVERABLE_ERROR);
        $hydratorInjected->setExtractor($hydrator);
        $this->assertSame($hydrator, $hydratorInjected->extractor);
    }
}
