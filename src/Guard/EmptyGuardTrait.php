<?php

declare(strict_types=1);

namespace Laminas\Stdlib\Guard;

use Exception;
use Laminas\Stdlib\Exception\InvalidArgumentException;

use function sprintf;

/**
 * Provide a guard method against empty data
 */
trait EmptyGuardTrait
{
    /**
     * Verify that the data is not empty
     *
     * @param mixed  $data           the data to verify
     * @return void
     * @throws Exception
     */
    protected function guardAgainstEmpty(
        $data,
        string $dataName = 'Argument',
        string $exceptionClass = InvalidArgumentException::class
    ) {
        if (empty($data)) {
            $message = sprintf('%s cannot be empty', $dataName);
            throw new $exceptionClass($message);
        }
    }
}
