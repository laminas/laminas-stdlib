<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\TestAsset;

/**
 * Test asset to verify that a composition of a class-methods and an array-serializable
 * hydrator produces the expected output
 */
class AggregateObject
{
    /**
     * @var array
     */
    public $arrayData  = array('president' => 'Zaphod');

    /**
     * @var string
     */
    public $maintainer = 'Marvin';

    /**
     * @return string
     */
    public function getMaintainer()
    {
        return $this->maintainer;
    }

    /**
     * @param string $maintainer
     */
    public function setMaintainer($maintainer)
    {
        $this->maintainer = $maintainer;
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->arrayData;
    }

    /**
     * @param array $data
     */
    public function exchangeArray(array $data)
    {
        $this->arrayData = $data;
    }
}
