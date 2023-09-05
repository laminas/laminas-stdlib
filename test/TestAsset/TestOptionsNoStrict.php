<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\AbstractOptions;

/**
 * Dummy TestOptions used to test Stdlib\Options
 *
 * @extends AbstractOptions<mixed>
 */
class TestOptionsNoStrict extends AbstractOptions
{
    // phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore,WebimpressCodingStandard.NamingConventions.ValidVariableName.NotCamelCapsProperty

    /** @var bool */
    protected $__strictMode__ = false;

    // phpcs:enable

    protected mixed $testField;

    public function setTestField(mixed $value): void
    {
        $this->testField = $value;
    }

    public function getTestField(): mixed
    {
        return $this->testField;
    }
}
