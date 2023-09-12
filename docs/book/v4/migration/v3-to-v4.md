# Migration from Version 3 to 4

Version 4 of `laminas-stdlib` contains a number of backwards incompatible changes. This guide is intended to help you upgrade from the version 3 series to version 4.

### Parameter and return type hints have been added throughout

All classes have been updated to make use of parameter and return types. In general usage, this should not pose too many problems, providing you have been passing the previously documented types to the methods that have changed, however, it is advisable to audit your existing usage of the library for potential type errors. A static analysis tool like Psalm or PHPStan will help you with this.

### Breaking changes to return types in iterable classes

A number of Queue, Stack and Heap implementations have different return types in order to align with the built-in PHP interfaces that they implement such as `Iterator` or `IteratorAggregate` etc.

#### Laminas\Stdlib\SplPriorityQueue

This class implements PHP's built-in `\SplPriorityQueue`

- The `insert()` method previously returned `void`. This has been changed to `bool` to align with `\SplPriorityQueue`
- 
