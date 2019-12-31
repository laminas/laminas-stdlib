<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator\NamingStrategy;

use Laminas\Filter\FilterChain;

class UnderscoreNamingStrategy implements NamingStrategyInterface
{
    protected static $camelCaseToUnderscoreFilter;

    protected static $underscoreToCamelCaseFilter;

    /**
     * Remove underscores and capitalize letters
     *
     * @param  string $name
     * @return string
     */
    public function hydrate($name)
    {
        return lcfirst($this->getUnderscoreToCamelCaseFilter()->filter($name));
    }

    /**
     * Remove capitalized letters and prepend underscores.
     *
     * @param  string $name
     * @return string
     */
    public function extract($name)
    {
        return $this->getCamelCaseToUnderscoreFilter()->filter($name);
    }

    /**
     * @return FilterChain
     */
    protected function getUnderscoreToCamelCaseFilter()
    {
        if (static::$underscoreToCamelCaseFilter instanceof FilterChain) {
            return static::$underscoreToCamelCaseFilter;
        }

        $filter = new FilterChain();
        $filter->attachByName('WordUnderscoreToCamelCase');
        static::$underscoreToCamelCaseFilter = $filter;
        return $filter;
    }

    /**
     * @return FilterChain
     */
    protected function getCamelCaseToUnderscoreFilter()
    {
        if (static::$camelCaseToUnderscoreFilter instanceof FilterChain) {
            return static::$camelCaseToUnderscoreFilter;
        }

        $filter = new FilterChain();
        $filter->attachByName('WordCamelCaseToUnderscore');
        $filter->attachByName('StringToLower');
        static::$camelCaseToUnderscoreFilter = $filter;
        return $filter;
    }
}
