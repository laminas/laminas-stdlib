<?php

declare(strict_types=1);

namespace Laminas\Stdlib\ArrayObject;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Laminas\Stdlib\Exception\InvalidArgumentException;
use Serializable;
use Traversable;
use UnexpectedValueException;

use function array_keys;
use function asort;
use function class_exists;
use function count;
use function get_class;
use function get_object_vars;
use function gettype;
use function in_array;
use function is_array;
use function is_callable;
use function is_object;
use function is_string;
use function ksort;
use function natcasesort;
use function natsort;
use function serialize;
use function sprintf;
use function strpos;
use function uasort;
use function uksort;
use function unserialize;

/**
 * Custom framework ArrayObject implementation
 *
 * Extends version-specific "abstract" implementation.
 */
class PHP81Implementation implements IteratorAggregate, ArrayAccess, Serializable, Countable
{
    /**
     * Properties of the object have their normal functionality
     * when accessed as list (var_dump, foreach, etc.).
     */
    public const STD_PROP_LIST = 1;

    /**
     * Entries can be accessed as properties (read and write).
     */
    public const ARRAY_AS_PROPS = 2;

    /** @var array */
    protected $storage;

    /** @var int */
    protected $flag;

    /** @var string */
    protected $iteratorClass;

    /** @var array */
    protected $protectedProperties;

    /**
     * Constructor
     *
     * @param array|object $input Object values must act like ArrayAccess
     * @param int          $flags
     * @param string       $iteratorClass
     */
    public function __construct($input = [], $flags = self::STD_PROP_LIST, $iteratorClass = 'ArrayIterator')
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
        if ($this->flag === self::ARRAY_AS_PROPS) {
            return $this->offsetExists($key);
        }

        if (in_array($key, $this->protectedProperties)) {
            throw new InvalidArgumentException('$key is a protected property, use a different key');
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
        if ($this->flag === self::ARRAY_AS_PROPS) {
            $this->offsetSet($key, $value);
            return;
        }

        if (in_array($key, $this->protectedProperties)) {
            throw new InvalidArgumentException('$key is a protected property, use a different key');
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
        if ($this->flag === self::ARRAY_AS_PROPS) {
            $this->offsetUnset($key);
            return;
        }

        if (in_array($key, $this->protectedProperties)) {
            throw new InvalidArgumentException('$key is a protected property, use a different key');
        }

        unset($this->$key);
    }

    /**
     * Returns the value at the specified key by reference
     *
     * @param  mixed $key
     * @return mixed
     */
    public function &__get($key)
    {
        if ($this->flag === self::ARRAY_AS_PROPS) {
            $ret = &$this->offsetGet($key);

            return $ret;
        }

        if (in_array($key, $this->protectedProperties, true)) {
            throw new InvalidArgumentException('$key is a protected property, use a different key');
        }

        return $this->$key;
    }

    /**
     * Appends the value
     *
     * @param  mixed $value
     * @return void
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
     */
    public function count(): int
    {
        return count($this->storage);
    }

    /**
     * Exchange the array for another one.
     *
     * @param  array|ArrayObject|ArrayIterator|object $data
     * @return array
     */
    public function exchangeArray($data)
    {
        if (! is_array($data) && ! is_object($data)) {
            throw new InvalidArgumentException(
                'Passed variable is not an array or object, using empty array instead'
            );
        }

        if (is_object($data) && ($data instanceof self || $data instanceof \ArrayObject)) {
            $data = $data->getArrayCopy();
        }
        if (! is_array($data)) {
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
     */
    public function getIterator(): Traversable
    {
        $class = $this->iteratorClass;

        return new $class($this->storage);
    }

    /**
     * Gets the iterator classname for the ArrayObject.
     *
     * @return string
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
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->storage[$offset]);
    }

    /**
     * Returns the value at the specified key
     */
    public function &offsetGet(mixed $offset): mixed
    {
        $ret = null;
        if (! $this->offsetExists($offset)) {
            return $ret;
        }
        $ret = &$this->storage[$offset];

        return $ret;
    }

    /**
     * Sets the value at the specified key to value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->storage[$offset] = $value;
    }

    /**
     * Unsets the value at the specified key
     */
    public function offsetUnset(mixed $offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->storage[$offset]);
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
     * Magic method used for serializing of an instance.
     *
     * @return array
     */
    public function __serialize()
    {
        return get_object_vars($this);
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

            return;
        }

        if (strpos($class, '\\') === 0) {
            $class = '\\' . $class;
            if (class_exists($class)) {
                $this->iteratorClass = $class;

                return;
            }
        }

        throw new InvalidArgumentException('The iterator class does not exist');
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

    /**
     * Magic method used to rebuild an instance.
     *
     * @param array $data Data array.
     * @return void
     */
    public function __unserialize($data)
    {
        $this->protectedProperties = array_keys(get_object_vars($this));

        foreach ($data as $k => $v) {
            switch ($k) {
                case 'flag':
                    $this->setFlags((int) $v);
                    break;

                case 'storage':
                    if (! is_array($v) && ! is_object($v)) {
                        throw new UnexpectedValueException(sprintf(
                            'Cannot unserialize to %s; expected "storage" value of array or object, received %s',
                            self::class,
                            gettype($v)
                        ));
                    }
                    $this->exchangeArray($v);
                    break;

                case 'iteratorClass':
                    if (! is_string($v)) {
                        throw new UnexpectedValueException(sprintf(
                            'Cannot unserialize to %s; expected "iteratorClass" value as string, received %s',
                            self::class,
                            is_object($v) ? get_class($v) : gettype($v)
                        ));
                    }

                    $this->setIteratorClass($v);
                    break;

                case 'protectedProperties':
                    break;

                default:
                    $this->__set($k, $v);
                    break;
            }
        }
    }
}
