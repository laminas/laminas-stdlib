<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception;
use Laminas\Stdlib\StringWrapper\Iconv;

use function array_shift;
use function extension_loaded;

class IconvTest extends CommonStringWrapperTest
{
    protected function setUp() : void
    {
        if (! extension_loaded('iconv')) {
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

        if (! Iconv::isSupported($encoding, $convertEncoding)) {
            return false;
        }

        $wrapper = new Iconv();
        $wrapper->setEncoding($encoding, $convertEncoding);
        return $wrapper;
    }
}
