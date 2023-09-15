# Migration from Version 3 to 4

Version 4 of `laminas-stdlib` contains a number of backwards incompatible changes. This guide is intended to help you upgrade from the version 3 series to version 4.

## New Features

### Parameter, Property and Return Type Hints Have Been Added Throughout

All classes have been updated to make use of parameter and return types. In general usage, this should not pose too many problems, providing you have been passing the previously documented types to the methods that have changed, however, it is advisable to audit your existing usage of the library for potential type errors. A static analysis tool like Psalm or PHPStan will help you with this.

The addition of property, parameter and return types will cause fatal errors for extending classes that re-define those properties or override changed methods. If you have extended from any classes in laminas-stdlib, you should check that property types and method signatures are compatible and update them if they are not aligned.

### `PriorityQueue::toArray()` Now Returns Data in Order of Priority

In previous versions, `PriorityQueue::toArray()` returned data in insertion order. This method now returns data in priority order making it effectively the same as `iterator_to_array($queue)`, with the exception that you can pass extraction flags to `PriorityQueue::toArray()`.

## Removed Features

None.

## Signature Changes

### Breaking Changes to Return Types in Iterable Classes

A number of Queue, Stack and Heap implementations have different return types in order to align with the built-in PHP interfaces that they implement such as `Iterator` or `IteratorAggregate` etc.

#### `insert()` Signature Change for Iterable Types

PHP's built-in `\SplPriorityQueue`, `\SplHeap`, `\SplMinHeap` and other similar classes return `true` from the `insert()` method. Classes that either extend from or have similar semantics to these built-in types previously had varying return types such as `void`, `self`. From version 4, the method signature for `insert()` where implemented has been changed to `bool` _(true)_.

- `Laminas\Stdlib\SplPriorityQueue::insert()` previously returned `void`. This has been changed to `bool` to align with `\SplPriorityQueue`
- `Laminas\Stdlib\PriorityQueue::insert()` previously returned `self`. This has been changed to `bool` for consistency
- `Laminas\Stdlib\PriorityList::insert()` previously returned `void`. This has been changed to `bool` for consistency
- `Laminas\Stdlib\FastPriorityQueue::insert()` previously returned `void`. This has been changed to `bool` for consistency

#### Other Method Signature Changes

##### `Laminas\Stdlib\PriorityList`

- `next()` previously returned `false` or the node value at the next index and now returns `void` to align with the `Iterator` interface.
- `setPriority()` previously returned `self` and now returns `void` for consistency.

## Deprecations

None.
