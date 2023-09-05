<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\StringWrapper;

use Laminas\Stdlib\StringWrapper\Native;

use function array_shift;

class NativeTest extends CommonStringWrapperTestCase
{
    protected function getWrapper(
        string|null $encoding = null,
        string|null $convertEncoding = null,
    ): Native|false {
        if ($encoding === null) {
            $supportedEncodings = Native::getSupportedEncodings();
            $encoding           = array_shift($supportedEncodings);
        }

        self::assertIsString($encoding);

        if (! Native::isSupported($encoding, $convertEncoding)) {
            return false;
        }

        $wrapper = new Native();
        $wrapper->setEncoding($encoding, $convertEncoding);
        return $wrapper;
    }
}
