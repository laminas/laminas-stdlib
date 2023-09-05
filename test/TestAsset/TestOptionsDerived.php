<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

/**
 * Dummy derived TestOptions used to test Stdlib\Options
 */
class TestOptionsDerived extends TestOptions
{
    private mixed $derivedPrivate;

    protected mixed $derivedProtected;

    protected mixed $derivedPublic;

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    private function setDerivedPrivate(mixed $derivedPrivate): void
    {
        $this->derivedPrivate = $derivedPrivate;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    private function getDerivedPrivate(): mixed
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
     */
    protected function getDerivedProtected(): mixed
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
     */
    public function getDerivedPublic(): mixed
    {
        return $this->derivedPublic;
    }
}
