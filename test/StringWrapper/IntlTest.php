<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception;
use Laminas\Stdlib\StringWrapper\Intl;

use function array_shift;
use function extension_loaded;

class IntlTest extends CommonStringWrapperTestCase
{
    protected function setUp(): void
    {
        if (! extension_loaded('intl')) {
            try {
                new Intl();
                $this->fail('Missing expected Laminas\Stdlib\Exception\ExtensionNotLoadedException');
            } catch (Exception\ExtensionNotLoadedException) {
                $this->markTestSkipped('Missing ext/intl');
            }
        }

        parent::setUp();
    }

    protected function getWrapper(
        string|null $encoding = null,
        string|null $convertEncoding = null,
    ): Intl|false {
        if ($encoding === null) {
            $supportedEncodings = Intl::getSupportedEncodings();
            $encoding           = array_shift($supportedEncodings);
        }

        self::assertIsString($encoding);

        if (! Intl::isSupported($encoding, $convertEncoding)) {
            return false;
        }

        $wrapper = new Intl();
        $wrapper->setEncoding($encoding, $convertEncoding);
        return $wrapper;
    }
}
