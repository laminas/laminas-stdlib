<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\Stdlib\Hydrator\DelegatingHydratorFactory;

class DelegatingHydratorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $hydratorManager = $this->prophesize(ServiceLocatorInterface::class);
        $hydratorManager->willImplement(ContainerInterface::class);

        $factory = new DelegatingHydratorFactory();
        $this->assertInstanceOf(
            'Laminas\Hydrator\DelegatingHydrator',
            $factory->createService($hydratorManager->reveal())
        );
    }
}
