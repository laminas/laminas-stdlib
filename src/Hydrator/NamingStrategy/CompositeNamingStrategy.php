<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator\NamingStrategy;

final class CompositeNamingStrategy implements NamingStrategyInterface
{
    /**
     * @var array
     */
    private $namingStrategies = [];

    /**
     * @var NamingStrategyInterface
     */
    private $defaultNamingStrategy;

    /**
     * @param NamingStrategyInterface[]    $strategies            indexed by the name they translate
     * @param NamingStrategyInterface|null $defaultNamingStrategy
     */
    public function __construct(array $strategies, NamingStrategyInterface $defaultNamingStrategy = null)
    {
        $this->namingStrategies = array_map(
            function (NamingStrategyInterface $strategy) {
                // this callback is here only to ensure type-safety
                return $strategy;
            },
            $strategies
        );

        $this->defaultNamingStrategy = $defaultNamingStrategy ?: new IdentityNamingStrategy();
    }

    /**
     * {@inheritDoc}
     */
    public function extract($name)
    {
        $strategy = isset($this->namingStrategies[$name])
            ? $this->namingStrategies[$name]
            : $this->defaultNamingStrategy;

        return $strategy->extract($name);
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate($name)
    {
        $strategy = isset($this->namingStrategies[$name])
            ? $this->namingStrategies[$name]
            : $this->defaultNamingStrategy;

        return $strategy->hydrate($name);
    }
}
