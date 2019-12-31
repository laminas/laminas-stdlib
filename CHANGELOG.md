# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 2.7.4 - 2015-10-15

### Added

- Nothing.

### Deprecated

- [zendframework/zend-stdlib#35](https://github.com/zendframework/zend-stdlib/pull/35) deprecates
  `Laminas\Stdlib\CallbackHandler`, as the one component that used it,
  laminas-eventmanager, will no longer depend on it starting in v3.

### Removed

- Nothing.

### Fixed

- Nothing.

## 2.7.3 - 2015-09-24

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-stdlib#27](https://github.com/zendframework/zend-stdlib/pull/27) fixes a race
  condition in the `FastPriorityQueue::remove()` logic that occurs when removing
  items iteratively from the same priority of a queue.

## 2.7.2 - 2015-09-23

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-stdlib#26](https://github.com/zendframework/zend-stdlib/pull/26) fixes a subtle
  inheritance issue with deprecation in the hydrators, and updates the
  `HydratorInterface` to also extend the laminas-hydrator `HydratorInterface` to
  ensure LSP is preserved.

## 2.7.1 - 2015-09-22

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-stdlib#24](https://github.com/zendframework/zend-stdlib/pull/24) fixes an import in
  `FastPriorityQueue` to alias `SplPriorityQueue` in order to disambiguate with
  the local override present in the component.

## 2.7.0 - 2015-09-22

### Added

- [zendframework/zend-stdlib#19](https://github.com/zendframework/zend-stdlib/pull/19) adds a new
  `FastPriorityQueue` implementation. It follows the same signature as
  `SplPriorityQueue`, but uses a performance-optimized algorithm:

  - inserts are 2x faster than `SplPriorityQueue` and 3x faster than the
    `Laminas\Stdlib\PriorityQueue` implementation.
  - extracts are 4x faster than `SplPriorityQueue` and 4-5x faster than the
    `Laminas\Stdlib\PriorityQueue` implementation.

  The intention is to use this as a drop-in replacement in the
  `laminas-eventmanager` component to provide performance benefits.

### Deprecated

- [zendframework/zend-stdlib#20](https://github.com/zendframework/zend-stdlib/pull/20) deprecates *all
  hydrator* classes, in favor of the new [laminas-hydrator](https://github.com/laminas/laminas-hydrator)
  component. All classes were updated to extend their laminas-hydrator equivalents,
  and marked as `@deprecated`, indicating the equivalent class from the other
  repository.

  Users *should* immediately start changing their code to use the laminas-hydrator
  equivalents; in most cases, this can be as easy as removing the `Stdlib`
  namespace from import statements or hydrator configuration. Hydrators will be
  removed entirely from laminas-stdlib in v3.0, and all future updates to hydrators
  will occur in the laminas-hydrator library.

  Changes with backwards compatibility implications:

  - Users implementing `Laminas\Stdlib\Hydrator\HydratorAwareInterface` will need to
    update their `setHydrator()` implementation to typehint on
    `Laminas\Hydrator\HydratorInterface`. This can be done by changing the import
    statement for that interface as follows:

    ```php
    // Replace this:
    use Laminas\Stdlib\Hydrator\HydratorInterface;
    // with this:
    use Laminas\Hydrator\HydratorInterface;
    ```

    If you are not using imports, change the typehint within the signature itself:

    ```php
    // Replace this:
    public function setHydrator(\Laminas\Stdlib\Hydrator\HydratorInterface $hydrator)
    // with this:
    public function setHydrator(\Laminas\Hydrator\HydratorInterface $hydrator)
    ```

    If you are using `Laminas\Stdlib\Hydrator\HydratorAwareTrait`, no changes are
    necessary, unless you override that method.

  - If you were catching hydrator-generated exceptions, these were previously in
    the `Laminas\Stdlib\Exception` namespace. You will need to update your code to
    catch exceptions in the `Laminas\Hydrator\Exception` namespace.

  - Users who *do* migrate to laminas-hydrator may end up in a situation where
    their code will not work with existing libraries that are still type-hinting
    on the laminas-stdlib interfaces. We will be attempting to address that ASAP,
    but the deprecation within laminas-stdlib is necessary as a first step.

    In the meantime, you can write hydrators targeting laminas-stdlib still in
    order to guarantee compatibility.

### Removed

- Nothing.

### Fixed

- Nothing.

## 2.6.0 - 2015-07-21

### Added

- [zendframework/zend-stdlib#13](https://github.com/zendframework/zend-stdlib/pull/13) adds
  `Laminas\Stdlib\Hydrator\Iterator`, which provides mechanisms for hydrating
  objects when iterating a traversable. This allows creating generic collection
  resultsets; the original idea was pulled from
  [PhlyMongo](https://github.com/phly/PhlyMongo), where it was used to hydrate
  collections retrieved from MongoDB.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 2.5.2 - 2015-07-21

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-stdlib#9](https://github.com/zendframework/zend-stdlib/pull/9) fixes an issue with
  count incrementation during insert in PriorityList, ensuring that incrementation only
  occurs when the item inserted was not previously present in the list.

## 2.4.4 - 2015-07-21

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zendframework/zend-stdlib#9](https://github.com/zendframework/zend-stdlib/pull/9) fixes an issue with
  count incrementation during insert in PriorityList, ensuring that incrementation only
  occurs when the item inserted was not previously present in the list.
