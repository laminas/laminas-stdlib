<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\ArraySerializableInterface;
use PHPUnit\Framework\Attributes\Group;

#[Group('Laminas_Stdlib')]
class ArraySerializable implements ArraySerializableInterface
{
    /** @var array */
    protected $data = [];

    public function __construct()
    {
        $this->data = [
            "foo"   => "bar",
            "bar"   => "foo",
            "blubb" => "baz",
            "quo"   => "blubb",
        ];
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
