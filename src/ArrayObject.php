<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Serializable;

use function array_keys;
use function asort;
use function class_exists;
use function count;
use function get_object_vars;
use function in_array;
use function is_array;
use function is_callable;
use function is_object;
use function ksort;
use function natcasesort;
use function natsort;
use function serialize;
use function strpos;
use function uasort;
use function uksort;
use function unserialize;

/**
 * Custom framework ArrayObject implementation
 *
 * Extends version-specific "abstract" implementation.
 *
 * @template TKey as array-key
 * @template TValue
 *
 * @template-implements IteratorAggregate<int|TKey, TValue>
 * @template-implements ArrayAccess<int|TKey, TValue>
 */
class ArrayObject implements IteratorAggregate, ArrayAccess, Serializable, Countable
{
    /**
     * Properties of the object have their normal functionality
     * when accessed as list (var_dump, foreach, etc.).
     */
    const STD_PROP_LIST = 1;

    /**
     * Entries can be accessed as properties (read and write).
     */
    const ARRAY_AS_PROPS = 2;

    /**
     * @var array
     * @psalm-var array<int|TKey, TValue>
     */
    protected $storage;

    /**
     * @var int
     */
    protected $flag;

    /**
     * @var class-string<\Iterator>
     */
    protected $iteratorClass;

    /**
     * @var string[]
     */
    protected $protectedProperties;

    /**
     * Constructor
     *
     * @param array  $input
     * @param int    $flags
     * @param string $iteratorClass
     *
     * @psalm-param array<TKey, TValue> $input
     * @psalm-param class-string<\Iterator> $iteratorClass
     */
    public function __construct($input = [], $flags = self::STD_PROP_LIST, $iteratorClass = \ArrayIterator::class)
    {
        $this->setFlags($flags);
        $this->storage = $input;
        $this->setIteratorClass($iteratorClass);
        $this->protectedProperties = array_keys(get_object_vars($this));
    }

    /**
     * Returns whether the requested key exists
     *
     * @param  mixed $key
     * @return bool
     */
    public function __isset($key)
    {
        if ($this->flag == self::ARRAY_AS_PROPS) {
            return $this->offsetExists($key);
        }
        if (in_array($key, $this->protectedProperties)) {
            throw new Exception\InvalidArgumentException('$key is a protected property, use a different key');
        }

        return isset($this->$key);
    }

    /**
     * Sets the value at the specified key to value
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        if ($this->flag == self::ARRAY_AS_PROPS) {
            $this->offsetSet($key, $value);
            return;
        }
        if (in_array($key, $this->protectedProperties)) {
            throw new Exception\InvalidArgumentException('$key is a protected property, use a different key');
        }
        $this->$key = $value;
    }

    /**
     * Unsets the value at the specified key
     *
     * @param  mixed $key
     * @return void
     */
    public function __unset($key)
    {
        if ($this->flag == self::ARRAY_AS_PROPS) {
            $this->offsetUnset($key);
            return;
        }
        if (in_array($key, $this->protectedProperties)) {
            throw new Exception\InvalidArgumentException('$key is a protected property, use a different key');
        }
        unset($this->$key);
    }

    /**
     * Returns the value at the specified key by reference
     *
     * @param  mixed $key
     * @return mixed
     *
     * @psalm-param TKey $key
     * @psalm-return TValue
     */
    public function &__get($key)
    {
        $ret = null;
        if ($this->flag == self::ARRAY_AS_PROPS) {
            $ret =& $this->offsetGet($key);

            return $ret;
        }
        if (in_array($key, $this->protectedProperties)) {
            throw new Exception\InvalidArgumentException('$key is a protected property, use a different key');
        }

        return $this->$key;
    }

    /**
     * Appends the value
     *
     * @param  mixed $value
     * @return void
     *
     * @psalm-param TValue $value
     */
    public function append($value)
    {
        $this->storage[] = $value;
    }

    /**
     * Sort the entries by value
     *
     * @return void
     */
    public function asort()
    {
        asort($this->storage);
    }

    /**
     * Get the number of public properties in the ArrayObject
     *
     * @return int
     */
    public function count()
    {
        return count($this->storage);
    }

    /**
     * Exchange the array for another one.
     *
     * @param  array|\ArrayObject $data
     * @return array
     *
     * @psalm-param array<TKey, TValue>|\ArrayObject<TKey, TValue> $data
     * @psalm-return array<int|TKey, TValue>
     */
    public function exchangeArray($data)
    {
        /** @psalm-var array<TKey, TValue>|mixed $data */
        if (! is_array($data) && ! is_object($data)) {
            throw new Exception\InvalidArgumentException(
                'Passed variable is not an array or object, using empty array instead'
            );
        }

        if (is_object($data) && ($data instanceof self || $data instanceof \ArrayObject)) {
            /** @psalm-var array<TKey, TValue> $data */
            $data = $data->getArrayCopy();
        }
        if (! is_array($data)) {
            /** @psalm-var array<TKey, TValue> $data */
            $data = (array) $data;
        }

        $storage = $this->storage;

        $this->storage = $data;

        return $storage;
    }

    /**
     * Creates a copy of the ArrayObject.
     *
     * @return array
     * @psalm-return array<int|TKey, TValue>
     */
    public function getArrayCopy()
    {
        return $this->storage;
    }

    /**
     * Gets the behavior flags.
     *
     * @return int
     */
    public function getFlags()
    {
        return $this->flag;
    }

    /**
     * Create a new iterator from an ArrayObject instance
     *
     * @return \Iterator
     * @psalm-return \Iterator<TKey, TValue>
     */
    public function getIterator()
    {
        $class = $this->iteratorClass;

        return new $class($this->storage);
    }

    /**
     * Gets the iterator classname for the ArrayObject.
     *
     * @return string
     *
     * @psalm-return class-string<\Iterator>
     */
    public function getIteratorClass()
    {
        return $this->iteratorClass;
    }

    /**
     * Sort the entries by key
     *
     * @return void
     */
    public function ksort()
    {
        ksort($this->storage);
    }

    /**
     * Sort an array using a case insensitive "natural order" algorithm
     *
     * @return void
     */
    public function natcasesort()
    {
        natcasesort($this->storage);
    }

    /**
     * Sort entries using a "natural order" algorithm
     *
     * @return void
     */
    public function natsort()
    {
        natsort($this->storage);
    }

    /**
     * Returns whether the requested key exists
     *
     * @param  mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return isset($this->storage[$key]);
    }

    /**
     * Returns the value at the specified key
     *
     * @param  mixed $key
     * @return mixed
     */
    public function &offsetGet($key)
    {
        $ret = null;
        if (! $this->offsetExists($key)) {
            return $ret;
        }
        $ret =& $this->storage[$key];

        return $ret;
    }

    /**
     * Sets the value at the specified key to value
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     *
     * @psalm-param int|TKey $key
     * @psalm-param TValue $value
     */
    public function offsetSet($key, $value)
    {
        $this->storage[$key] = $value;
    }

    /**
     * Unsets the value at the specified key
     *
     * @param  mixed $key
     * @return void
     *
     * @psalm-param int|TKey $key
     */
    public function offsetUnset($key)
    {
        if ($this->offsetExists($key)) {
            unset($this->storage[$key]);
        }
    }

    /**
     * Serialize an ArrayObject
     *
     * @return string
     */
    public function serialize()
    {
        return serialize(get_object_vars($this));
    }

    /**
     * Sets the behavior flags
     *
     * @param  int  $flags
     * @return void
     */
    public function setFlags($flags)
    {
        $this->flag = $flags;
    }

    /**
     * Sets the iterator classname for the ArrayObject
     *
     * @param  string $class
     * @return void
     */
    public function setIteratorClass($class)
    {
        if (class_exists($class)) {
            $this->iteratorClass = $class;

            return ;
        }

        if (strpos($class, '\\') === 0) {
            $class = '\\' . $class;
            if (class_exists($class)) {
                $this->iteratorClass = $class;

                return ;
            }
        }

        throw new Exception\InvalidArgumentException('The iterator class does not exist');
    }

    /**
     * Sort the entries with a user-defined comparison function and maintain key association
     *
     * @param  callable $function
     * @return void
     */
    public function uasort($function)
    {
        if (is_callable($function)) {
            uasort($this->storage, $function);
        }
    }

    /**
     * Sort the entries by keys using a user-defined comparison function
     *
     * @param  callable $function
     * @return void
     */
    public function uksort($function)
    {
        if (is_callable($function)) {
            uksort($this->storage, $function);
        }
    }

    /**
     * Unserialize an ArrayObject
     *
     * @param  string $data
     * @return void
     */
    public function unserialize($data)
    {
        $ar                        = unserialize($data);
        $this->protectedProperties = array_keys(get_object_vars($this));

        $this->setFlags($ar['flag']);
        $this->exchangeArray($ar['storage']);
        $this->setIteratorClass($ar['iteratorClass']);

        foreach ($ar as $k => $v) {
            switch ($k) {
                case 'flag':
                    $this->setFlags($v);
                    break;
                case 'storage':
                    $this->exchangeArray($v);
                    break;
                case 'iteratorClass':
                    $this->setIteratorClass($v);
                    break;
                case 'protectedProperties':
                    break;
                default:
                    $this->__set($k, $v);
            }
        }
    }
}
