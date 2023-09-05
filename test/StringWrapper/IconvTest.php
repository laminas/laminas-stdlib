<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception;
use Laminas\Stdlib\StringWrapper\Iconv;

use function array_shift;
use function extension_loaded;
use function file_exists;
use function file_get_contents;
use function is_readable;
use function stripos;

class IconvTest extends CommonStringWrapperTestCase
{
    protected function setUp(): void
    {
        if (! extension_loaded('iconv')) {
            try {
                new Iconv();
                $this->fail('Missing expected Laminas\Stdlib\Exception\ExtensionNotLoadedException');
            } catch (Exception\ExtensionNotLoadedException) {
                $this->markTestSkipped('Missing ext/iconv');
            }
        }

        /**
         * ext-iconv is not properly supported on Alpine Linux, hence, we skip the tests for now
         *
         * @see https://github.com/nunomaduro/phpinsights/issues/43
         * @see https://github.com/docker-library/php/issues/240#issuecomment-353678474
         */
        if (file_exists('/etc/os-release') && is_readable('/etc/os-release')) {
            $osRelease = file_get_contents('/etc/os-release');
            self::assertIsString($osRelease);
            if (stripos($osRelease, 'Alpine Linux') !== false) {
                $this->markTestSkipped('iconv not properly supported on Alpine Linux');
            }
        }

        parent::setUp();
    }

    protected function getWrapper(
        string|null $encoding = null,
        string|null $convertEncoding = null,
    ): Iconv|false {
        if ($encoding === null) {
            $supportedEncodings = Iconv::getSupportedEncodings();
            $encoding           = array_shift($supportedEncodings);
        }

        self::assertIsString($encoding);

        if (! Iconv::isSupported($encoding, $convertEncoding)) {
            return false;
        }

        $wrapper = new Iconv();
        $wrapper->setEncoding($encoding, $convertEncoding);
        return $wrapper;
    }
}
