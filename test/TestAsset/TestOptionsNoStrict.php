<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\AbstractOptions;

/**
 * Dummy TestOptions used to test Stdlib\Options
 */
class TestOptionsNoStrict extends AbstractOptions
{
    // phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore,WebimpressCodingStandard.NamingConventions.ValidVariableName.NotCamelCapsProperty

    /** @var bool */
    protected $__strictMode__ = false;

    // phpcs:enable

    /** @var mixed */
    protected $testField;

    /** @param mixed $value */
    public function setTestField($value)
    {
        $this->testField = $value;
    }

    /** @return mixed */
    public function getTestField()
    {
        return $this->testField;
    }
}
