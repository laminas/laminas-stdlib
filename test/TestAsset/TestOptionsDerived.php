<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

/**
 * Dummy derived TestOptions used to test Stdlib\Options
 */
class TestOptionsDerived extends TestOptions
{
    /** @var mixed */
    private $derivedPrivate;

    /** @var mixed */
    protected $derivedProtected;

    /** @var mixed */
    protected $derivedPublic;

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    private function setDerivedPrivate(mixed $derivedPrivate): void
    {
        $this->derivedPrivate = $derivedPrivate;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     *
     * @return mixed
     */
    private function getDerivedPrivate()
    {
        return $this->derivedPrivate;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    protected function setDerivedProtected(mixed $derivedProtected): void
    {
        $this->derivedProtected = $derivedProtected;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     *
     * @return mixed
     */
    protected function getDerivedProtected()
    {
        return $this->derivedProtected;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    public function setDerivedPublic(mixed $derivedPublic): void
    {
        $this->derivedPublic = $derivedPublic;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     *
     * @return mixed
     */
    public function getDerivedPublic()
    {
        return $this->derivedPublic;
    }
}
