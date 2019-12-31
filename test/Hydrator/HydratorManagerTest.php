<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator;

use Laminas\ServiceManager\ServiceManager;
use Laminas\Stdlib\Hydrator\HydratorPluginManager;

/**
 * @group Laminas_Stdlib
 */
class HydratorManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HydratorPluginManager
     */
    protected $manager;

    public function setUp()
    {
        $this->manager = new HydratorPluginManager();
    }

    public function testRegisteringInvalidElementRaisesException()
    {
        $this->setExpectedException('Laminas\Stdlib\Exception\RuntimeException');
        $this->manager->setService('test', $this);
    }

    public function testLoadingInvalidElementRaisesException()
    {
        $this->manager->setInvokableClass('test', get_class($this));
        $this->setExpectedException('Laminas\Stdlib\Exception\RuntimeException');
        $this->manager->get('test');
    }
}
