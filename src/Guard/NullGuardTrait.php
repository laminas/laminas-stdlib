<?php

declare(strict_types=1);

namespace Laminas\Stdlib\Guard;

use Exception;
use Laminas\Stdlib\Exception\InvalidArgumentException;

use function sprintf;

/**
 * Provide a guard method against null data
 */
trait NullGuardTrait
{
    /**
     * Verify that the data is not null
     *
     * @param mixed  $data           the data to verify
     * @return void
     * @throws Exception
     */
    protected function guardAgainstNull(
        $data,
        string $dataName = 'Argument',
        string $exceptionClass = InvalidArgumentException::class
    ) {
        if (null === $data) {
            $message = sprintf('%s cannot be null', $dataName);
            throw new $exceptionClass($message);
        }
    }
}
