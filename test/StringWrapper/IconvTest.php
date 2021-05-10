<?php

namespace LaminasTest\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception;
use Laminas\Stdlib\StringWrapper\Iconv;

class IconvTest extends CommonStringWrapperTest
{
    public function setUp()
    {
        if (!extension_loaded('iconv')) {
            try {
                new Iconv('utf-8');
                $this->fail('Missing expected Laminas\Stdlib\Exception\ExtensionNotLoadedException');
            } catch (Exception\ExtensionNotLoadedException $e) {
                $this->markTestSkipped('Missing ext/iconv');
            }
        }

        parent::setUp();
    }

    protected function getWrapper($encoding = null, $convertEncoding = null)
    {
        if ($encoding === null) {
            $supportedEncodings = Iconv::getSupportedEncodings();
            $encoding = array_shift($supportedEncodings);
        }

        if (!Iconv::isSupported($encoding, $convertEncoding)) {
            return false;
        }

        $wrapper = new Iconv();
        $wrapper->setEncoding($encoding, $convertEncoding);
        return $wrapper;
    }
}
