<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator;

use ArrayObject;
use Laminas\Stdlib\Hydrator\Strategy\StrategyInterface;
use Laminas\Stdlib\Hydrator\StrategyEnabledInterface;

/**
 * @category   Laminas
 * @package    Laminas_Stdlib
 * @subpackage Hydrator
 */
abstract class AbstractHydrator implements HydratorInterface, StrategyEnabledInterface
{
    /**
     * The list with strategies that this hydrator has.
     *
     * @var ArrayObject
     */
    protected $strategies;

    /**
     * Initializes a new instance of this class.
     */
    public function __construct()
    {
        $this->strategies = new ArrayObject();
    }

    /**
     * Gets the strategy with the given name.
     *
     * @param string $name The name of the strategy to get.
     * @return StrategyInterface
     */
    public function getStrategy($name)
    {
        return $this->strategies[$name];
    }

    /**
     * Checks if the strategy with the given name exists.
     *
     * @param string $name The name of the strategy to check for.
     * @return bool
     */
    public function hasStrategy($name)
    {
        return array_key_exists($name, $this->strategies);
    }

    /**
     * Adds the given strategy under the given name.
     *
     * @param string $name The name of the strategy to register.
     * @param StrategyInterface $strategy The strategy to register.
     * @return HydratorInterface
     */
    public function addStrategy($name, StrategyInterface $strategy)
    {
        $this->strategies[$name] = $strategy;
        return $this;
    }

    /**
     * Removes the strategy with the given name.
     *
     * @param string $name The name of the strategy to remove.
     * @return HydratorInterface
     */
    public function removeStrategy($name)
    {
        unset($this->strategies[$name]);
        return $this;
    }

    /**
     * Converts a value for extraction. If no strategy exists the plain value is returned.
     *
     * @param string $name The name of the strategy to use.
     * @param mixed $value The value that should be converted.
     * @return mixed
     */
    public function extractValue($name, $value)
    {
        if ($this->hasStrategy($name)) {
            $strategy = $this->getStrategy($name);
            $value = $strategy->extract($value);
        }
        return $value;
    }

    /**
     * Converts a value for hydration. If no strategy exists the plain value is returned.
     *
     * @param string $name The name of the strategy to use.
     * @param mixed $value The value that should be converted.
     * @return mixed
     */
    public function hydrateValue($name, $value)
    {
        if ($this->hasStrategy($name)) {
            $strategy = $this->getStrategy($name);
            $value = $strategy->hydrate($value);
        }
        return $value;
    }
}
