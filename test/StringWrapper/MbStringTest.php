<?php // phpcs:disable WebimpressCodingStandard.PHP.CorrectClassNameCase.Invalid,SlevomatCodingStandard.TypeHints.DeclareStrictTypes.IncorrectWhitespaceBeforeDeclare

declare(strict_types=1);

namespace LaminasTest\Stdlib\StringWrapper;

use Laminas\Stdlib\Exception;
use Laminas\Stdlib\StringWrapper\MbString;

use function array_shift;
use function extension_loaded;

class MbStringTest extends CommonStringWrapperTest
{
    protected function setUp(): void
    {
        if (! extension_loaded('mbstring')) {
            try {
                new MbString('utf-8');
                $this->fail('Missing expected Laminas\Stdlib\Exception\ExtensionNotLoadedException');
            } catch (Exception\ExtensionNotLoadedException $e) {
                $this->markTestSkipped('Missing ext/mbstring');
            }
        }

        parent::setUp();
    }

    /**
     * @param null|string $encoding
     * @param null|string $convertEncoding
     * @return MbString|false
     */
    protected function getWrapper($encoding = null, $convertEncoding = null)
    {
        if ($encoding === null) {
            $supportedEncodings = MbString::getSupportedEncodings();
            $encoding           = array_shift($supportedEncodings);
        }

        if (! MbString::isSupported($encoding, $convertEncoding)) {
            return false;
        }

        $wrapper = new MbString();
        $wrapper->setEncoding($encoding, $convertEncoding);
        return $wrapper;
    }
}
