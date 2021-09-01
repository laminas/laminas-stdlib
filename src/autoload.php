<?php // phpcs:disable WebimpressCodingStandard.PHP.CorrectClassNameCase.Invalid,SlevomatCodingStandard.TypeHints.DeclareStrictTypes.IncorrectWhitespaceBeforeDeclare

declare(strict_types=1);

namespace Laminas\Stdlib;

use function class_alias;

use const PHP_VERSION_ID;

// PHP < 8.1
if (PHP_VERSION_ID < 80100) {
    // Laminas\Stdlib\ArrayObject
    class_alias(ArrayObject\LegacyImplementation::class, ArrayObject::class);

    // Laminas\Stdlib\FastPriorityQueue
    class_alias(FastPriorityQueue\LegacyImplementation::class, FastPriorityQueue::class);

    // Laminas\Stdlib\Parameters
    class_alias(Parameters\LegacyImplementation::class, Parameters::class);

    // Laminas\Stdlib\PriorityList
    class_alias(PriorityList\LegacyImplementation::class, PriorityList::class);

    // Laminas\Stdlib\PriorityQueue
    class_alias(PriorityQueue\LegacyImplementation::class, PriorityQueue::class);

    // Laminas\Stdlib\SplQueue
    class_alias(SplQueue\LegacyImplementation::class, SplQueue::class);

    // Laminas\Stdlib\SplStack
    class_alias(SplStack\LegacyImplementation::class, SplStack::class);
}

// PHP 8.1+
if (PHP_VERSION_ID >= 80100) {
    // Laminas\Stdlib\ArrayObject
    class_alias(ArrayObject\PHP81Implementation::class, ArrayObject::class);

    // Laminas\Stdlib\FastPriorityQueue
    class_alias(FastPriorityQueue\PHP81Implementation::class, FastPriorityQueue::class);

    // Laminas\Stdlib\Parameters
    class_alias(Parameters\PHP81Implementation::class, Parameters::class);

    // Laminas\Stdlib\PriorityList
    class_alias(PriorityList\PHP81Implementation::class, PriorityList::class);

    // Laminas\Stdlib\PriorityQueue
    class_alias(PriorityQueue\PHP81Implementation::class, PriorityQueue::class);

    // Laminas\Stdlib\SplQueue
    class_alias(SplQueue\PHP81Implementation::class, SplQueue::class);

    // Laminas\Stdlib\SplStack
    class_alias(SplStack\PHP81Implementation::class, SplStack::class);
}
