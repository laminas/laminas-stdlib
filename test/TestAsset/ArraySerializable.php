<?php

namespace LaminasTest\Stdlib\TestAsset;

/**
 * @group      Laminas_Stdlib
 */
class ArraySerializable implements \Laminas\Stdlib\ArraySerializableInterface
{
    protected $data = array();

    public function __construct()
    {
        $this->data = array(
            "foo" => "bar",
            "bar" => "foo",
            "blubb" => "baz",
            "quo" => "blubb"
        );
    }

    /**
     * Exchange internal values from provided array
     *
     * @param  array $array
     * @return void
     */
    public function exchangeArray(array $array)
    {
        $this->data = $array;
    }

    /**
     * Return an array representation of the object
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->data;
    }
}
