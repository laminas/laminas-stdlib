<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator;

use Laminas\Stdlib\Exception;

class ObjectProperty extends AbstractHydrator
{
    /**
     * Extract values from an object
     *
     * Extracts the accessible non-static properties of the given $object.
     *
     * @param  object $object
     * @return array
     * @throws Exception\BadMethodCallException for a non-object $object
     */
    public function extract($object)
    {
        if (!is_object($object)) {
            throw new Exception\BadMethodCallException(sprintf(
                '%s expects the provided $object to be a PHP object)', __METHOD__
            ));
        }

        $data = get_object_vars($object);

        $filter = $this->getFilter();
        foreach ($data as $name => $value) {
            // Filter keys, removing any we don't want
            if (!$filter->filter($name)) {
                unset($data[$name]);
                continue;
            }
            // Extract data
            $data[$name] = $this->extractValue($name, $value);
        }

        return $data;
    }

    /**
     * Hydrate an object by populating public properties
     *
     * Hydrates an object by setting public properties of the object.
     *
     * @param  array $data
     * @param  object $object
     * @return object
     * @throws Exception\BadMethodCallException for a non-object $object
     */
    public function hydrate(array $data, $object)
    {
        if (!is_object($object)) {
            throw new Exception\BadMethodCallException(sprintf(
                '%s expects the provided $object to be a PHP object)', __METHOD__
            ));
        }
        foreach ($data as $property => $value) {
            $object->$property = $this->hydrateValue($property, $value, $data);
        }
        return $object;
    }
}
