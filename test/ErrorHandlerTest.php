<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use ErrorException;
use Laminas\Stdlib\ErrorHandler;
use PHPUnit\Framework\TestCase;

use function trigger_error;

use const E_USER_WARNING;

class ErrorHandlerTest extends TestCase
{
    protected function tearDown(): void
    {
        if (ErrorHandler::getNestedLevel()) {
            ErrorHandler::clean();
        }
    }

    public function testNestedLevel(): void
    {
        self::assertSame(0, ErrorHandler::getNestedLevel());

        ErrorHandler::start();
        self::assertSame(1, ErrorHandler::getNestedLevel());

        ErrorHandler::start();
        self::assertSame(2, ErrorHandler::getNestedLevel());

        ErrorHandler::stop();
        self::assertSame(1, ErrorHandler::getNestedLevel());

        ErrorHandler::stop();
        self::assertSame(0, ErrorHandler::getNestedLevel());
    }

    public function testClean(): void
    {
        ErrorHandler::start();
        self::assertSame(1, ErrorHandler::getNestedLevel());

        ErrorHandler::start();
        self::assertSame(2, ErrorHandler::getNestedLevel());

        ErrorHandler::clean();
        self::assertSame(0, ErrorHandler::getNestedLevel());
    }

    public function testStarted(): void
    {
        self::assertFalse(ErrorHandler::started());

        ErrorHandler::start();
        self::assertTrue(ErrorHandler::started());

        ErrorHandler::stop();
        self::assertFalse(ErrorHandler::started());
    }

    public function testReturnCatchedError(): void
    {
        ErrorHandler::start(E_USER_WARNING);
        trigger_error('consider this a warning', E_USER_WARNING);
        $err = ErrorHandler::stop();

        self::assertInstanceOf('ErrorException', $err);
    }

    public function testThrowCatchedError(): void
    {
        ErrorHandler::start(E_USER_WARNING);
        trigger_error('consider this a warning', E_USER_WARNING);

        $this->expectException(ErrorException::class);
        ErrorHandler::stop(true);
    }

    public function testAddError(): void
    {
        ErrorHandler::start();
        ErrorHandler::addError(1, 'test-msg1', 'test-file1', 100);
        ErrorHandler::addError(2, 'test-msg2', 'test-file2', 200);
        $err = ErrorHandler::stop();

        self::assertInstanceOf('ErrorException', $err);
        self::assertEquals('test-file2', $err->getFile());
        self::assertEquals('test-msg2', $err->getMessage());
        self::assertEquals(200, $err->getLine());
        self::assertEquals(0, $err->getCode());
        self::assertEquals(2, $err->getSeverity());

        $previous = $err->getPrevious();
        self::assertInstanceOf('ErrorException', $previous);
        self::assertEquals('test-file1', $previous->getFile());
        self::assertEquals('test-msg1', $previous->getMessage());
        self::assertEquals(100, $previous->getLine());
        self::assertEquals(0, $previous->getCode());
        self::assertEquals(1, $previous->getSeverity());
    }
}
