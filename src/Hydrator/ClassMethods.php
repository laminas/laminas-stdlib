<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator;

use Laminas\Stdlib\ArrayUtils;
use Laminas\Stdlib\Exception;
use Laminas\Stdlib\Hydrator\Filter\FilterComposite;
use Laminas\Stdlib\Hydrator\Filter\FilterProviderInterface;
use Laminas\Stdlib\Hydrator\Filter\GetFilter;
use Laminas\Stdlib\Hydrator\Filter\HasFilter;
use Laminas\Stdlib\Hydrator\Filter\IsFilter;
use Laminas\Stdlib\Hydrator\Filter\MethodMatchFilter;
use Laminas\Stdlib\Hydrator\Filter\OptionalParametersFilter;
use ReflectionMethod;
use Traversable;

class ClassMethods extends AbstractHydrator implements HydratorOptionsInterface
{
    /**
     * Flag defining whether array keys are underscore-separated (true) or camel case (false)
     * @var bool
     */
    protected $underscoreSeparatedKeys = true;

    /**
     * @var \Laminas\Stdlib\Hydrator\Filter\FilterInterface
     */
    private $callableMethodFilter;

    /**
     * Define if extract values will use camel case or name with underscore
     * @param bool|array $underscoreSeparatedKeys
     */
    public function __construct($underscoreSeparatedKeys = true)
    {
        parent::__construct();
        $this->setUnderscoreSeparatedKeys($underscoreSeparatedKeys);

        $this->callableMethodFilter = new OptionalParametersFilter();

        $this->filterComposite->addFilter("is", new IsFilter());
        $this->filterComposite->addFilter("has", new HasFilter());
        $this->filterComposite->addFilter("get", new GetFilter());
        $this->filterComposite->addFilter("parameter", new OptionalParametersFilter(), FilterComposite::CONDITION_AND);
    }

    /**
     * @param  array|Traversable                 $options
     * @return ClassMethods
     * @throws Exception\InvalidArgumentException
     */
    public function setOptions($options)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new Exception\InvalidArgumentException(
                'The options parameter must be an array or a Traversable'
            );
        }
        if (isset($options['underscoreSeparatedKeys'])) {
            $this->setUnderscoreSeparatedKeys($options['underscoreSeparatedKeys']);
        }

        return $this;
    }

    /**
     * @param  bool      $underscoreSeparatedKeys
     * @return ClassMethods
     */
    public function setUnderscoreSeparatedKeys($underscoreSeparatedKeys)
    {
        $this->underscoreSeparatedKeys = $underscoreSeparatedKeys;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUnderscoreSeparatedKeys()
    {
        return $this->underscoreSeparatedKeys;
    }

    /**
     * Extract values from an object with class methods
     *
     * Extracts the getter/setter of the given $object.
     *
     * @param  object                           $object
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

        $filter = null;
        if ($object instanceof FilterProviderInterface) {
            $filter = new FilterComposite(
                array($object->getFilter()),
                array(new MethodMatchFilter("getFilter"))
            );
        } else {
            $filter = $this->filterComposite;
        }

        $transform = function ($letters) {
            $letter = array_shift($letters);

            return '_' . strtolower($letter);
        };
        $attributes = array();
        $methods = get_class_methods($object);

        foreach ($methods as $method) {
            if (
                !$filter->filter(
                    get_class($object) . '::' . $method
                )
            ) {
                continue;
            }

            if (!$this->callableMethodFilter->filter(get_class($object) . '::' . $method)) {
                continue;
            }

            $attribute = $method;
            if (preg_match('/^get/', $method)) {
                $attribute = substr($method, 3);
                if (!property_exists($object, $attribute)) {
                    $attribute = lcfirst($attribute);
                }
            }

            if ($this->underscoreSeparatedKeys) {
                $attribute = preg_replace_callback('/([A-Z])/', $transform, $attribute);
            }
            $attributes[$attribute] = $this->extractValue($attribute, $object->$method(), $object);
        }

        return $attributes;
    }

    /**
     * Hydrate an object by populating getter/setter methods
     *
     * Hydrates an object by getter/setter methods of the object.
     *
     * @param  array                            $data
     * @param  object                           $object
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

        $transform = function ($letters) {
            $letter = substr(array_shift($letters), 1, 1);

            return ucfirst($letter);
        };

        foreach ($data as $property => $value) {
            $method = 'set' . ucfirst($property);
            if ($this->underscoreSeparatedKeys) {
                $method = preg_replace_callback('/(_[a-z])/', $transform, $method);
            }
            if (is_callable(array($object, $method))) {
                $value = $this->hydrateValue($property, $value, $data);
                $object->$method($value);
            }
        }

        return $object;
    }
}
