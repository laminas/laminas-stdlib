<?php

declare(strict_types=1);

namespace Laminas\Stdlib\Guard;

use Exception;
use Laminas\Stdlib\Exception\InvalidArgumentException;

use function get_class;
use function gettype;
use function is_iterable;
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
     * @param mixed  $data           the data to verify
     * @return void
     * @throws Exception
     */
    protected function guardForArrayOrTraversable(
        $data,
        string $dataName = 'Argument',
        string $exceptionClass = InvalidArgumentException::class
    ) {
        if (! is_iterable($data)) {
            $message = sprintf(
                "%s must be an array or Traversable, [%s] given",
                $dataName,
                is_object($data) ? get_class($data) : gettype($data)
            );
            throw new $exceptionClass($message);
        }
    }
}
