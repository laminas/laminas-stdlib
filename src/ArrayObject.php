<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib;

/**
 * If the version is less than 5.3.4, we'll use Laminas\Stdlib\ArrayObject\PhpLegacyCompatibility
 * which extends the native PHP ArrayObject implementation. For versions greater than or equal
 * to 5.3.4, we'll use Laminas\Stdlib\ArrayObject\PhpReferenceCompatibility, which corrects
 * issues with how PHP handles references inside ArrayObject.
 *
 * class_alias is a global construct, so we can alias either one to Laminas\Stdlib\ArrayObject,
 * and from this point forward, that alias will be used.
 */
if (version_compare(PHP_VERSION, '5.3.4', 'lt')) {
    class_alias('Laminas\Stdlib\ArrayObject\PhpLegacyCompatibility', 'Laminas\Stdlib\AbstractArrayObject');
} else {
    class_alias('Laminas\Stdlib\ArrayObject\PhpReferenceCompatibility', 'Laminas\Stdlib\AbstractArrayObject');
}

/**
 * Custom framework ArrayObject implementation
 *
 * Extends version-specific "abstract" implementation.
 */
class ArrayObject extends AbstractArrayObject
{
}
