<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\JsonSerializable;

/**
 * Interface compatible with the built-in JsonSerializable interface
 *
 * JsonSerializable was introduced in PHP 5.4.0.
 *
 * @see http://php.net/manual/class.jsonserializable.php
 */
interface PhpLegacyCompatibility
{
    /**
     * Returns data which can be serialized by json_encode().
     *
     * @return mixed
     * @see    http://php.net/manual/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize();
}
