<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception;
use Laminas\Stdlib\StringWrapper\Intl;

class IntlTest extends CommonStringWrapperTest
{
    public function setUp()
    {
        if (! extension_loaded('intl')) {
            try {
                new Intl('utf-8');
                $this->fail('Missing expected Laminas\Stdlib\Exception\ExtensionNotLoadedException');
            } catch (Exception\ExtensionNotLoadedException $e) {
                $this->markTestSkipped('Missing ext/intl');
            }
        }

        parent::setUp();
    }

    protected function getWrapper($encoding = null, $convertEncoding = null)
    {
        if ($encoding === null) {
            $supportedEncodings = Intl::getSupportedEncodings();
            $encoding = array_shift($supportedEncodings);
        }

        if (! Intl::isSupported($encoding, $convertEncoding)) {
            return false;
        }

        $wrapper = new Intl();
        $wrapper->setEncoding($encoding, $convertEncoding);
        return $wrapper;
    }
}
