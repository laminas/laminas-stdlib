<?php

namespace LaminasTest\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception;
use Laminas\Stdlib\StringWrapper\MbString;

class MbStringTest extends CommonStringWrapperTest
{
    public function setUp()
    {
        if (!extension_loaded('mbstring')) {
            try {
                new MbString('utf-8');
                $this->fail('Missing expected Laminas\Stdlib\Exception\ExtensionNotLoadedException');
            } catch (Exception\ExtensionNotLoadedException $e) {
                $this->markTestSkipped('Missing ext/mbstring');
            }
        }

        parent::setUp();
    }

    protected function getWrapper($encoding = null, $convertEncoding = null)
    {
        if ($encoding === null) {
            $supportedEncodings = MbString::getSupportedEncodings();
            $encoding = array_shift($supportedEncodings);
        }

        if (!MbString::isSupported($encoding, $convertEncoding)) {
            return false;
        }

        $wrapper = new MbString();
        $wrapper->setEncoding($encoding, $convertEncoding);
        return $wrapper;
    }
}
