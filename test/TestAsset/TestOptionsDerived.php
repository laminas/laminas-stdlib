<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\TestAsset;

/**
 * Dummy derived TestOptions used to test Stdlib\Options
 */
class TestOptionsDerived extends TestOptions
{
    private $derivedPrivate;

    protected $derivedProtected;

    protected $derivedPublic;

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    private function setDerivedPrivate($derivedPrivate)
    {
        $this->derivedPrivate = $derivedPrivate;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    private function getDerivedPrivate()
    {
        return $this->derivedPrivate;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    protected function setDerivedProtected($derivedProtected)
    {
        $this->derivedProtected = $derivedProtected;
    }


    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    protected function getDerivedProtected()
    {
        return $this->derivedProtected;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    public function setDerivedPublic($derivedPublic)
    {
        $this->derivedPublic = $derivedPublic;
    }


    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    public function getDerivedPublic()
    {
        return $this->derivedPublic;
    }
}
