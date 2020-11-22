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
 * @psalm-type TKey=array-key
 * @psalm-type TValue=mixed
 * @template TIterator as \Iterator<array-key, mixed>
 *
 * @template-implements IteratorAggregate<array-key, mixed>
 * @template-implements ArrayAccess<array-key, mixed>
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
     * @psalm-var array<TKey, TValue>
     */
    protected $storage;

    /**
     * @var int
     * @psalm-var self::STD_PROP_LIST | self::ARRAY_AS_PROPS
     */
    protected $flag;

    /**
     * @var class-string<TIterator>
     * @psalm-suppress PropertyNotSetInConstructor
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
     * @psalm-param self::STD_PROP_LIST | self::ARRAY_AS_PROPS $flags
     * @psalm-param class-string<TIterator> $iteratorClass
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
     *
     * @psalm-param TKey $key
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
     *
     * @psalm-param TKey $key
     * @psalm-param TValue $value
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
     *
     * @psalm-param TKey $key
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
     * @psalm-return TValue|null
     */
    public function &__get($key)
    {
        $ret = null;
        if ($this->flag == self::ARRAY_AS_PROPS) {
            /** @psalm-var TValue|mixed $ret */
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
     * @psalm-return array<TKey, TValue>
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
     * @psalm-return array<TKey, TValue>
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
     * @psalm-return TIterator
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
     * @psalm-return class-string<TIterator>
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
     *
     * @psalm-param TKey $key
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
     *
     * @psalm-param TKey $key
     * @psalm-return TValue|null
     */
    public function &offsetGet($key)
    {
        $ret = null;
        if (! $this->offsetExists($key)) {
            return $ret;
        }

        /** @psalm-var TValue $ret */
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
     * @psalm-param TKey $key
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
     * @psalm-param TKey $key
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
     *
     * @psalm-param self::STD_PROP_LIST | self::ARRAY_AS_PROPS $flags
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
     *
     * @psalm-param class-string<TIterator> $class
     */
    public function setIteratorClass($class)
    {
        if (class_exists($class)) {
            $this->iteratorClass = $class;

            return ;
        }

        if (strpos($class, '\\') === 0) {
            /** @psalm-var class-string<TIterator> $class */
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
     *
     * @psalm-param callable(TValue, TValue): int $function
     * @psalm-suppress RedundantConditionGivenDocblockType
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
     *
     * @psalm-param callable(TKey, TKey): int $function
     * @psalm-suppress RedundantConditionGivenDocblockType
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
        /**
         * @psalm-type AR = array{flag: self::STD_PROP_LIST | self::ARRAY_AS_PROPS, storage: array<TKey, TValue>, iteratorClass: class-string<\Iterator<TKey, TValue>>}&array<array-key, mixed>
         * @psalm-var AR $ar
         */
        $ar                        = unserialize($data);
        $this->protectedProperties = array_keys(get_object_vars($this));

        $this->setFlags($ar['flag']);
        $this->exchangeArray($ar['storage']);
        $this->setIteratorClass($ar['iteratorClass']);

        /** @psalm-suppress MixedAssignment */
        foreach ($ar as $k => $v) {
            switch ($k) {
                case 'flag':
                    /** @psalm-var self::STD_PROP_LIST | self::ARRAY_AS_PROPS $v */
                    $this->setFlags($v);
                    break;
                case 'storage':
                    /** @psalm-var array<TKey, TValue> $v */
                    $this->exchangeArray($v);
                    break;
                case 'iteratorClass':
                    /** @psalm-var class-string<\Iterator<TKey, TValue>> $v */
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
