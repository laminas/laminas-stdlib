<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\Exception\InvalidArgumentException;
use Laminas\Stdlib\Message;
use PHPUnit\Framework\TestCase;
use stdClass;

class MessageTest extends TestCase
{
    public function testMessageCanSetAndGetContent(): void
    {
        $message = new Message();
        $message->setContent('I can set content');
        self::assertEquals('I can set content', $message->getContent());
    }

    public function testMessageCanSetAndGetMetadataKeyAsString(): void
    {
        $message = new Message();
        $message->setMetadata('foo', 'bar');
        self::assertEquals('bar', $message->getMetadata('foo'));
        self::assertEquals(['foo' => 'bar'], $message->getMetadata());
    }

    public function testMessageCanSetAndGetMetadataKeyAsArray(): void
    {
        $message = new Message();
        $message->setMetadata(['foo' => 'bar']);
        self::assertEquals('bar', $message->getMetadata('foo'));
    }

    public function testMessageGetMetadataWillUseDefaultValueIfNoneExist(): void
    {
        $message = new Message();
        self::assertEquals('bar', $message->getMetadata('foo', 'bar'));
    }

    public function testMessageThrowsExceptionOnInvalidKeyForMetadataSet(): void
    {
        $message = new Message();

        $this->expectException(InvalidArgumentException::class);
        /** @psalm-suppress InvalidArgument */
        $message->setMetadata(new stdClass());
    }

    public function testMessageThrowsExceptionOnInvalidKeyForMetadataGet(): void
    {
        $message = new Message();

        $this->expectException(InvalidArgumentException::class);
        /** @psalm-suppress InvalidArgument */
        $message->getMetadata(new stdClass());
    }

    public function testMessageToStringWorks(): void
    {
        $message = new Message();
        $message->setMetadata(['Foo' => 'bar', 'One' => 'Two']);
        $message->setContent('This is my content');
        $expected = "Foo: bar\r\nOne: Two\r\n\r\nThis is my content";
        self::assertEquals($expected, $message->toString());
    }
}
