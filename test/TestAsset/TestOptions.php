<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\AbstractOptions;

/**
 * Dummy TestOptions used to test Stdlib\Options
 *
 * @extends AbstractOptions<mixed>
 */
class TestOptions extends AbstractOptions
{
    protected mixed $testField;

    private mixed $parentPrivate;

    protected mixed $parentProtected;

    protected mixed $parentPublic;

    public function setTestField(mixed $value): void
    {
        $this->testField = $value;
    }

    public function getTestField(): mixed
    {
        return $this->testField;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    private function setParentPrivate(mixed $parentPrivate): void
    {
        $this->parentPrivate = $parentPrivate;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    private function getParentPrivate(): mixed
    {
        return $this->parentPrivate;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    protected function setParentProtected(mixed $parentProtected): void
    {
        $this->parentProtected = $parentProtected;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    protected function getParentProtected(): mixed
    {
        return $this->parentProtected;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    public function setParentPublic(mixed $parentPublic): void
    {
        $this->parentPublic = $parentPublic;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    public function getParentPublic(): mixed
    {
        return $this->parentPublic;
    }
}
