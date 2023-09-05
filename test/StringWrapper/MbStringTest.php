<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception;
use Laminas\Stdlib\StringWrapper\MbString;

use function array_shift;
use function extension_loaded;

class MbStringTest extends CommonStringWrapperTestCase
{
    protected function setUp(): void
    {
        if (! extension_loaded('mbstring')) {
            try {
                new MbString();
                $this->fail('Missing expected Laminas\Stdlib\Exception\ExtensionNotLoadedException');
            } catch (Exception\ExtensionNotLoadedException) {
                $this->markTestSkipped('Missing ext/mbstring');
            }
        }

        parent::setUp();
    }

    protected function getWrapper(
        string|null $encoding = null,
        string|null $convertEncoding = null,
    ): MbString|false {
        if ($encoding === null) {
            $supportedEncodings = MbString::getSupportedEncodings();
            $encoding           = array_shift($supportedEncodings);
        }

        self::assertIsString($encoding);

        if (! MbString::isSupported($encoding, $convertEncoding)) {
            return false;
        }

        $wrapper = new MbString();
        $wrapper->setEncoding($encoding, $convertEncoding);
        return $wrapper;
    }
}
