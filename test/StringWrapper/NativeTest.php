<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

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

        if (! Native::isSupported($encoding, $convertEncoding)) {
            return false;
        }

        $wrapper = new Native();
        $wrapper->setEncoding($encoding, $convertEncoding);
        return $wrapper;
    }
}
