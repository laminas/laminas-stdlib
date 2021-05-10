<?php

namespace LaminasTest\Stdlib\StringWrapper;

use Laminas\Stdlib\StringWrapper\Native;

class NativeTest extends CommonStringWrapperTest
{
    protected function getWrapper($encoding = null, $convertEncoding = null)
    {
        if ($encoding === null) {
            $supportedEncodings = Native::getSupportedEncodings();
            $encoding = array_shift($supportedEncodings);
        }

        if (!Native::isSupported($encoding, $convertEncoding)) {
            return false;
        }

        $wrapper = new Native();
        $wrapper->setEncoding($encoding, $convertEncoding);
        return $wrapper;
    }
}
