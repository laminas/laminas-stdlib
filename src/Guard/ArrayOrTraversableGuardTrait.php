<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Guard;

use Laminas\Stdlib\Exception\InvalidArgumentException;
use Traversable;

use function get_class;
use function gettype;
use function is_array;
use function is_object;
use function sprintf;

/**
 * Provide a guard method for array or Traversable data
 */
trait ArrayOrTraversableGuardTrait
{
    /**
     * Verifies that the data is an array or Traversable
     *
     * @param  mixed  $data           the data to verify
     * @param  string $dataName       the data name
     * @param  string $exceptionClass FQCN for the exception
     * @throws \Exception
     *
     * @template TKey
     * @template TValue
     * @psalm-param iterable<TKey, TValue>|mixed
     * @psalm-param class-string<\Exception> $exceptionClass
     * @psalm-assert iterable<TKey, TValue> $data
     */
    protected function guardForArrayOrTraversable(
        $data,
        $dataName = 'Argument',
        $exceptionClass = InvalidArgumentException::class
    ) {
        if (! is_array($data) && ! ($data instanceof Traversable)) {
            $message = sprintf(
                "%s must be an array or Traversable, [%s] given",
                $dataName,
                is_object($data) ? get_class($data) : gettype($data)
            );
            throw new $exceptionClass($message);
        }
    }
}
