<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\Exception\InvalidArgumentException;
use Laminas\Stdlib\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testMessageCanSetAndGetContent(): void
    {
        $message = new Message();
        $ret = $message->setContent('I can set content');
        self::assertInstanceOf('Laminas\Stdlib\Message', $ret);
        self::assertEquals('I can set content', $message->getContent());
    }

    public function testMessageCanSetAndGetMetadataKeyAsString(): void
    {
        $message = new Message();
        $ret = $message->setMetadata('foo', 'bar');
        self::assertInstanceOf('Laminas\Stdlib\Message', $ret);
        self::assertEquals('bar', $message->getMetadata('foo'));
        self::assertEquals(['foo' => 'bar'], $message->getMetadata());
    }

    public function testMessageCanSetAndGetMetadataKeyAsArray(): void
    {
        $message = new Message();
        $ret = $message->setMetadata(['foo' => 'bar']);
        self::assertInstanceOf('Laminas\Stdlib\Message', $ret);
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
        $message->setMetadata(new \stdClass());
    }

    public function testMessageThrowsExceptionOnInvalidKeyForMetadataGet(): void
    {
        $message = new Message();

        $this->expectException(InvalidArgumentException::class);
        $message->getMetadata(new \stdClass());
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
