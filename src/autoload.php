<?php // phpcs:disable WebimpressCodingStandard.PHP.CorrectClassNameCase.Invalid,SlevomatCodingStandard.TypeHints.DeclareStrictTypes.IncorrectWhitespaceBeforeDeclare

declare(strict_types=1);

namespace Laminas\Stdlib;

use function class_alias;

use const PHP_VERSION_ID;

// Laminas\Stdlib\ArrayObject
class_alias(
    PHP_VERSION_ID >= 80100
    ? ArrayObject\PHP81Implementation::class
    : ArrayObject\LegacyImplementation::class,
    ArrayObject::class
);

// Laminas\Stdlib\FastPriorityQueue
class_alias(
    PHP_VERSION_ID >= 80100
    ? FastPriorityQueue\PHP81Implementation::class
    : FastPriorityQueue\LegacyImplementation::class,
    FastPriorityQueue::class
);

// Laminas\Stdlib\Parameters
class_alias(
    PHP_VERSION_ID >= 80100
    ? Parameters\PHP81Implementation::class
    : Parameters\LegacyImplementation::class,
    Parameters::class
);

// Laminas\Stdlib\PriorityList
class_alias(
    PHP_VERSION_ID >= 80100
    ? PriorityList\PHP81Implementation::class
    : PriorityList\LegacyImplementation::class,
    PriorityList::class
);

// Laminas\Stdlib\PriorityQueue
class_alias(
    PHP_VERSION_ID >= 80100
    ? PriorityQueue\PHP81Implementation::class
    : PriorityQueue\LegacyImplementation::class,
    PriorityQueue::class
);

// Laminas\Stdlib\SplQueue
class_alias(
    PHP_VERSION_ID >= 80100
    ? SplQueue\PHP81Implementation::class
    : SplQueue\LegacyImplementation::class,
    SplQueue::class
);

// Laminas\Stdlib\SplStack
class_alias(
    PHP_VERSION_ID >= 80100
    ? SplStack\PHP81Implementation::class
    : SplStack\LegacyImplementation::class,
    SplStack::class
);
