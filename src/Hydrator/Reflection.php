<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator;

use Laminas\Stdlib\Exception;
use ReflectionClass;

/**
 * @category   Laminas
 * @package    Laminas_Stdlib
 * @subpackage Hydrator
 */
class Reflection extends AbstractHydrator
{
    /**
     * Simple in-memory array cache of ReflectionProperties used.
     * @var array
     */
    protected static $reflProperties = array();

    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object)
    {
        $result = array();
        foreach (self::getReflProperties($object) as $property) {
            $propertyName = $property->getName();

            $value = $property->getValue($object);
            $result[$propertyName] = $this->extractValue($propertyName, $value);
        }

        return $result;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $reflProperties = self::getReflProperties($object);
        foreach ($data as $key => $value) {
            if (isset($reflProperties[$key])) {
                $reflProperties[$key]->setValue($object, $this->hydrateValue($key, $value));
            }
        }
        return $object;
    }

    /**
     * Get a reflection properties from in-memory cache and lazy-load if
     * class has not been loaded.
     *
     * @static
     * @param string|object $input
     * @throws Exception\InvalidArgumentException
     * @return array
     */
    protected static function getReflProperties($input)
    {
        if (is_object($input)) {
            $input = get_class($input);
        } elseif (!is_string($input)) {
            throw new Exception\InvalidArgumentException('Input must be a string or an object.');
        }

        if (!isset(self::$reflProperties[$input])) {
            $reflClass      = new ReflectionClass($input);
            $reflProperties = $reflClass->getProperties();

            foreach ($reflProperties as $property) {
                $property->setAccessible(true);
                self::$reflProperties[$input][$property->getName()] = $property;
            }
        }

        return self::$reflProperties[$input];
    }
}
