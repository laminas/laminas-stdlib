<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\Exception\InvalidArgumentException;
use Laminas\Stdlib\Message;
use PHPUnit\Framework\TestCase;
use stdClass;

class MessageTest extends TestCase
{
    public function testMessageCanSetAndGetContent()
    {
        $message = new Message();
        $ret     = $message->setContent('I can set content');
        self::assertInstanceOf(Message::class, $ret);
        self::assertEquals('I can set content', $message->getContent());
    }

    public function testMessageCanSetAndGetMetadataKeyAsString()
    {
        $message = new Message();
        $ret     = $message->setMetadata('foo', 'bar');
        self::assertInstanceOf(Message::class, $ret);
        self::assertEquals('bar', $message->getMetadata('foo'));
        self::assertEquals(['foo' => 'bar'], $message->getMetadata());
    }

    public function testMessageCanSetAndGetMetadataKeyAsArray()
    {
        $message = new Message();
        $ret     = $message->setMetadata(['foo' => 'bar']);
        self::assertInstanceOf(Message::class, $ret);
        self::assertEquals('bar', $message->getMetadata('foo'));
    }

    public function testMessageGetMetadataWillUseDefaultValueIfNoneExist()
    {
        $message = new Message();
        self::assertEquals('bar', $message->getMetadata('foo', 'bar'));
    }

    public function testMessageThrowsExceptionOnInvalidKeyForMetadataSet()
    {
        $message = new Message();

        $this->expectException(InvalidArgumentException::class);
        $message->setMetadata(new stdClass());
    }

    public function testMessageThrowsExceptionOnInvalidKeyForMetadataGet()
    {
        $message = new Message();

        $this->expectException(InvalidArgumentException::class);
        $message->getMetadata(new stdClass());
    }

    public function testMessageToStringWorks()
    {
        $message = new Message();
        $message->setMetadata(['Foo' => 'bar', 'One' => 'Two']);
        $message->setContent('This is my content');
        $expected = "Foo: bar\r\nOne: Two\r\n\r\nThis is my content";
        self::assertEquals($expected, $message->toString());
    }
}
