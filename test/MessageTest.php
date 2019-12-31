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
    public function testMessageCanSetAndGetContent()
    {
        $message = new Message();
        $ret = $message->setContent('I can set content');
        $this->assertInstanceOf('Laminas\Stdlib\Message', $ret);
        $this->assertEquals('I can set content', $message->getContent());
    }

    public function testMessageCanSetAndGetMetadataKeyAsString()
    {
        $message = new Message();
        $ret = $message->setMetadata('foo', 'bar');
        $this->assertInstanceOf('Laminas\Stdlib\Message', $ret);
        $this->assertEquals('bar', $message->getMetadata('foo'));
        $this->assertEquals(['foo' => 'bar'], $message->getMetadata());
    }

    public function testMessageCanSetAndGetMetadataKeyAsArray()
    {
        $message = new Message();
        $ret = $message->setMetadata(['foo' => 'bar']);
        $this->assertInstanceOf('Laminas\Stdlib\Message', $ret);
        $this->assertEquals('bar', $message->getMetadata('foo'));
    }

    public function testMessageGetMetadataWillUseDefaultValueIfNoneExist()
    {
        $message = new Message();
        $this->assertEquals('bar', $message->getMetadata('foo', 'bar'));
    }

    public function testMessageThrowsExceptionOnInvalidKeyForMetadataSet()
    {
        $message = new Message();

        $this->expectException(InvalidArgumentException::class);
        $message->setMetadata(new \stdClass());
    }

    public function testMessageThrowsExceptionOnInvalidKeyForMetadataGet()
    {
        $message = new Message();

        $this->expectException(InvalidArgumentException::class);
        $message->getMetadata(new \stdClass());
    }

    public function testMessageToStringWorks()
    {
        $message = new Message();
        $message->setMetadata(['Foo' => 'bar', 'One' => 'Two']);
        $message->setContent('This is my content');
        $expected = "Foo: bar\r\nOne: Two\r\n\r\nThis is my content";
        $this->assertEquals($expected, $message->toString());
    }
}
