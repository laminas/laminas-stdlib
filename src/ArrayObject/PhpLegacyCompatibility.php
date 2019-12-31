<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\ArrayObject;

use ArrayObject as PhpArrayObject;

/**
 * ArrayObject
 *
 * Since we need to substitute an alternate ArrayObject implementation for
 * versions > 5.3.3, we need to provide a stub for 5.3.3. This stub
 * simply extends the PHP ArrayObject implementation, and provides default
 * behavior in the constructor.
 */
abstract class PhpLegacyCompatibility extends PhpArrayObject
{
    /**
     * Constructor
     *
     * @param array  $input
     * @param int    $flags
     * @param string $iteratorClass
     */
    public function __construct($input = array(), $flags = self::STD_PROP_LIST, $iteratorClass = 'ArrayIterator')
    {
        parent::__construct($input, $flags, $iteratorClass);
    }
}
