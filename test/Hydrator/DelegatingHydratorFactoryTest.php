<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator;

use Laminas\Stdlib\Hydrator\DelegatingHydratorFactory;

class DelegatingHydratorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $hydratorManager = $this->getMock('Laminas\ServiceManager\ServiceLocatorInterface');
        $factory = new DelegatingHydratorFactory();
        $this->assertInstanceOf(
            'Laminas\Stdlib\Hydrator\DelegatingHydrator',
            $factory->createService($hydratorManager)
        );
    }
}
