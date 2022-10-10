<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\AbstractOptions;

/**
 * Dummy TestOptions used to test Stdlib\Options
 */
class TestOptions extends AbstractOptions
{
    /** @var mixed */
    protected $testField;

    /** @var mixed */
    private $parentPrivate;

    /** @var mixed */
    protected $parentProtected;

    /** @var mixed */
    protected $parentPublic;

    public function setTestField(mixed $value): void
    {
        $this->testField = $value;
    }

    /** @return mixed */
    public function getTestField()
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
     *
     * @return mixed
     */
    private function getParentPrivate()
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
     *
     * @return mixed
     */
    protected function getParentProtected()
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
     *
     * @return mixed
     */
    public function getParentPublic()
    {
        return $this->parentPublic;
    }
}
