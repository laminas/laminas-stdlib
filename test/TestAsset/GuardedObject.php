<?php // phpcs:disable WebimpressCodingStandard.PHP.CorrectClassNameCase.Invalid,SlevomatCodingStandard.TypeHints.DeclareStrictTypes.IncorrectWhitespaceBeforeDeclare

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\Guard\AllGuardsTrait;

class GuardedObject
{
    use AllGuardsTrait;

    /**
     * @param mixed $value
     */
    public function setArrayOrTraversable($value): void
    {
        $this->guardForArrayOrTraversable($value);
    }

    /**
     * @param mixed $value
     */
    public function setNotEmpty($value): void
    {
        $this->guardAgainstEmpty($value);
    }

    /**
     * @param mixed $value
     */
    public function setNotNull($value): void
    {
        $this->guardAgainstNull($value);
    }
}
