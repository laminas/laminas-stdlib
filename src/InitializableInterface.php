<?php

namespace Laminas\Stdlib;

/**
 * Interface to allow objects to have initialization logic
 */
interface InitializableInterface
{
    /**
     * Init an object
     *
     * @return void
     */
    public function init();
}
