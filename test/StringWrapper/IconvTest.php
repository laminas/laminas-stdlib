<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception;
use Laminas\Stdlib\StringWrapper\Iconv;
use Laminas\Stdlib\StringWrapper\StringWrapperInterface;

use function array_shift;
use function extension_loaded;

class IconvTest extends CommonStringWrapperTest
{
    protected function setUp(): void
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

    /**
     * @param null|string $encoding
     * @param null|string $convertEncoding
     * @return false|StringWrapperInterface
     */
    protected function getWrapper($encoding = null, $convertEncoding = null)
    {
        if ($encoding === null) {
            $supportedEncodings = Iconv::getSupportedEncodings();
            $encoding           = array_shift($supportedEncodings);
        }

        if (! Iconv::isSupported($encoding, $convertEncoding)) {
            return false;
        }

        $wrapper = new Iconv();
        $wrapper->setEncoding($encoding, $convertEncoding);
        return $wrapper;
    }
}
