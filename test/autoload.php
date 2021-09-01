<?php // phpcs:disable WebimpressCodingStandard.PHP.CorrectClassNameCase.Invalid,SlevomatCodingStandard.TypeHints.DeclareStrictTypes.IncorrectWhitespaceBeforeDeclare

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use function class_alias;

use const PHP_VERSION_ID;

// PHP < 8.1
if (PHP_VERSION_ID < 80100) {
    // LaminasTest\Stdlib\TestAsset\ArrayObjectIterator
    class_alias(TestAsset\ArrayObjectIterator\LegacyImplementation::class, TestAsset\ArrayObjectIterator::class);

    // LaminasTest\Stdlib\TestAsset\ArrayObjectObjectCount
    class_alias(TestAsset\ArrayObjectObjectCount\LegacyImplementation::class, TestAsset\ArrayObjectObjectCount::class);
}

// PHP 8.1+
if (PHP_VERSION_ID >= 80100) {
    // LaminasTest\Stdlib\TestAsset\ArrayObjectIterator
    class_alias(TestAsset\ArrayObjectIterator\PHP81Implementation::class, TestAsset\ArrayObjectIterator::class);

    // LaminasTest\Stdlib\TestAsset\ArrayObjectObjectCount
    class_alias(TestAsset\ArrayObjectObjectCount\PHP81Implementation::class, TestAsset\ArrayObjectObjectCount::class);
}
