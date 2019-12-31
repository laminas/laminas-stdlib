<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */
namespace Laminas\Stdlib\Hydrator\Filter;

use InvalidArgumentException;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Filter that includes methods which have no parameters or only optional parameters
 */
class OptionalParametersFilter implements FilterInterface
{
    /**
     * Map of methods already analyzed
     * by {@see \Laminas\Stdlib\Hydrator\Filter\OptionalParametersFilter::filter()},
     * cached for performance reasons
     *
     * @var bool[]
     */
    private static $propertiesCache = array();

    /**
     * {@inheritDoc}
     */
    public function filter($property)
    {
        if (isset(self::$propertiesCache[$property])) {
            return self::$propertiesCache[$property];
        }

        try {
            $reflectionMethod = new ReflectionMethod($property);
        } catch (ReflectionException $exception) {
            throw new InvalidArgumentException(sprintf('Method %s doesn\'t exist', $property));
        }

        $mandatoryParameters = array_filter(
            $reflectionMethod->getParameters(),
            function (ReflectionParameter $parameter) {
                return ! $parameter->isOptional();
            }
        );

        return self::$propertiesCache[$property] = empty($mandatoryParameters);
    }
}
