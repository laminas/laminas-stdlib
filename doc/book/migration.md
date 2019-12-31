# Migration Guide

## From v2 to v3

The changes made going from v2 to v3 were:

- Removal of the Hydrator subcomponent.
- Removal of the `CallbackHandler` class.
- Removal of `Laminas\Stdlib\Guard\GuardUtils`.

### Hydrators

The biggest single change from version 2 to version 3 is that the hydrator
subcomponent, which was deprecated in v2.7.0, is now removed. This means that if
you were using laminas-stdlib principally for the hydrators, you need to convert
your code to use [laminas-hydrator](https://github.com/laminas/laminas-hydrator).

This will also mean a multi-step migration. laminas-stdlib v3 pre-dates
laminas-hydrator v2.1, which will be the first version that supports laminas-stdlib v3
and laminas-servicemanager v3. If you are using Composer, the migration should be
seamless:

- Remove your laminas-stdlib dependency:

  ```bash
  $ composer remove laminas/laminas-stdlib
  ```

- Update to use laminas-hydrator:

  ```bash
  $ composer require laminas/laminas-hydrator
  ```

When laminas-hydrator updates to newer versions of laminas-stdlib and
laminas-servicemanager, you will either automatically get those versions, or you
can tell composer to use those specific versions:

```bash
$ composer require "laminas/laminas-stdlib:^3.0"
```

### CallbackHandler

`Laminas\Stdlib\CallbackHandler` primarily existed for legacy purposes; it was
created before the `callable` typehint existed, so that we could typehint PHP
callables. It also provided some minimal features around lazy-loading callables
from instantiable classes, but these features were rarely used, and better
approaches already exist for handling such functinality in laminas-servicemanager
and mezzio.

As such, the class was marked deprecated in v2.7.0, and removed for v3.0.0.

### GuardUtils

Version 3 removes `Laminas\Stdlib\Guard\GuardUtils`. This abstract class existed to
provide the functionality of the various traits also present in that
subcomponent, for consumers on versions of PHP earlier than 5.4. Since the
minimum required version is now PHP 5.5, the class is unnecessary. If you were
using it previously, compose the related traits instead.
