<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib;

if (PHP_VERSION_ID < 50400) {
    class_alias(
        'Laminas\Stdlib\JsonSerializable\PhpLegacyCompatibility',
        'JsonSerializable'
    );
}

/**
 * Polyfill for JsonSerializable
 *
 * JsonSerializable was introduced in PHP 5.4.0.
 *
 * @see http://php.net/manual/class.jsonserializable.php
 */
interface JsonSerializable extends \JsonSerializable
{
}
