<?php

namespace LaminasTest\Stdlib\TestAsset;

class ClassMethodsCamelCase
{
    protected $fooBar = '1';

    protected $fooBarBaz = '2';

    protected $isFoo = true;

    protected $isBar = true;

    protected $hasFoo = true;

    protected $hasBar = true;

    public function getFooBar()
    {
        return $this->fooBar;
    }

    public function setFooBar($value)
    {
        $this->fooBar = $value;
        return $this;
    }

    public function getFooBarBaz()
    {
        return $this->fooBarBaz;
    }

    public function setFooBarBaz($value)
    {
        $this->fooBarBaz = $value;
        return $this;
    }

    public function getIsFoo()
    {
        return $this->isFoo;
    }

    public function setIsFoo($value)
    {
        $this->isFoo = $value;
        return $this;
    }

    public function isBar()
    {
        return $this->isBar;
    }

    public function setIsBar($value)
    {
        $this->isBar = $value;
        return $this;
    }

    public function getHasFoo()
    {
        return $this->hasFoo;
    }

    public function setHasFoo($value)
    {
        $this->hasFoo = $value;
        return $this;
    }

    public function hasBar()
    {
        return $this->hasBar;
    }

    public function setHasBar($value)
    {
        $this->hasBar = $value;
        return $this;
    }
}
