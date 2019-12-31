<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\AbstractOptions;

/**
 * Dummy TestOptions used to test Stdlib\Options
 */
class TestOptions extends AbstractOptions
{
    protected $testField;

    private $parentPrivate;

    protected $parentProtected;

    protected $parentPublic;

    public function setTestField($value)
    {
        $this->testField = $value;
    }

    public function getTestField()
    {
        return $this->testField;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    private function setParentPrivate($parentPrivate)
    {
        $this->parentPrivate = $parentPrivate;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    private function getParentPrivate()
    {
        return $this->parentPrivate;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    protected function setParentProtected($parentProtected)
    {
        $this->parentProtected = $parentProtected;
    }


    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    protected function getParentProtected()
    {
        return $this->parentProtected;
    }

    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    public function setParentPublic($parentPublic)
    {
        $this->parentPublic = $parentPublic;
    }


    /**
     * Needed to test accessibility of getters / setters within deriving classes
     */
    public function getParentPublic()
    {
        return $this->parentPublic;
    }
}
