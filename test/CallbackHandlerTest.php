<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\CallbackHandler;

/**
 * @group      Laminas_Stdlib
 */
class CallbackHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (isset($this->args)) {
            unset($this->args);
        }
    }

    public function testCallbackShouldStoreMetadata()
    {
        $handler = new CallbackHandler('rand', array('event' => 'foo'));
        $this->assertEquals('foo', $handler->getMetadatum('event'));
        $this->assertEquals(array('event' => 'foo'), $handler->getMetadata());
    }

    public function testCallbackShouldBeStringIfNoHandlerPassedToConstructor()
    {
        $handler = new CallbackHandler('rand');
        $this->assertSame('rand', $handler->getCallback());
    }

    public function testCallbackShouldBeArrayIfHandlerPassedToConstructor()
    {
        $handler = new CallbackHandler(array('LaminasTest\\Stdlib\\SignalHandlers\\ObjectCallback', 'test'));
        $this->assertSame(array('LaminasTest\\Stdlib\\SignalHandlers\\ObjectCallback', 'test'), $handler->getCallback());
    }

    public function testCallShouldInvokeCallbackWithSuppliedArguments()
    {
        $handler = new CallbackHandler(array( $this, 'handleCall' ));
        $args   = array('foo', 'bar', 'baz');
        $handler->call($args);
        $this->assertSame($args, $this->args);
    }

    public function testPassingInvalidCallbackShouldRaiseInvalidCallbackExceptionDuringInstantiation()
    {
        $this->setExpectedException('Laminas\Stdlib\Exception\InvalidCallbackException');
        $handler = new CallbackHandler('boguscallback');
    }

    public function testCallShouldReturnTheReturnValueOfTheCallback()
    {
        $handler = new CallbackHandler(array('LaminasTest\\Stdlib\\SignalHandlers\\ObjectCallback', 'test'));
        if (!is_callable(array('LaminasTest\\Stdlib\\SignalHandlers\\ObjectCallback', 'test'))) {
            echo "\nClass exists? " . var_export(class_exists('LaminasTest\\Stdlib\\SignalHandlers\\ObjectCallback'), 1) . "\n";
            echo "Include path: " . get_include_path() . "\n";
        }
        $this->assertEquals('bar', $handler->call(array()));
    }

    public function testStringCallbackResolvingToClassDefiningInvokeNameShouldRaiseException()
    {
        $this->setExpectedException('Laminas\Stdlib\Exception\InvalidCallbackException');
        $handler = new CallbackHandler('LaminasTest\\Stdlib\\SignalHandlers\\Invokable');
    }

    public function testStringCallbackReferringToClassWithoutDefinedInvokeShouldRaiseException()
    {
        $this->setExpectedException('Laminas\Stdlib\Exception\InvalidCallbackException');
        $class   = new SignalHandlers\InstanceMethod();
        $handler = new CallbackHandler($class);
    }

    public function testCallbackConsistingOfStringContextWithNonStaticMethodShouldNotRaiseExceptionButWillRaiseEStrict()
    {
        $handler = new CallbackHandler(array('LaminasTest\\Stdlib\\SignalHandlers\\InstanceMethod', 'handler'));
        $error   = false;
        set_error_handler(function ($errno, $errstr) use (&$error) {
            $error = true;
        }, E_STRICT|E_DEPRECATED);
        $handler->call();
        restore_error_handler();
        $this->assertTrue($error);
    }

    public function testStringCallbackConsistingOfNonStaticMethodShouldRaiseException()
    {
        $handler = new CallbackHandler('LaminasTest\\Stdlib\\SignalHandlers\\InstanceMethod::handler');

        if (version_compare(PHP_VERSION, '5.4.0rc1', '>=')) {
            $this->setExpectedException('Laminas\Stdlib\Exception\InvalidCallbackException');
            $handler->call();
        } else {
            $error   = false;
            set_error_handler(function ($errno, $errstr) use (&$error) {
                $error = true;
            }, E_STRICT);
            $handler->call();
            restore_error_handler();
            $this->assertTrue($error);
        }
    }

    public function testStringStaticCallbackForPhp54()
    {
        if (version_compare(PHP_VERSION, '5.4.0rc1', '<=')) {
            $this->markTestSkipped('Requires PHP 5.4');
        }

        $handler = new CallbackHandler('LaminasTest\\Stdlib\\SignalHandlers\\InstanceMethod::staticHandler');
        $error   = false;
        set_error_handler(function ($errno, $errstr) use (&$error) {
            $error = true;
        }, E_STRICT);
        $result = $handler->call();
        restore_error_handler();
        $this->assertFalse($error);
        $this->assertSame('staticHandler', $result);
    }

    public function testStringStaticCallbackForPhp54WithMoreThan3Args()
    {
        if (version_compare(PHP_VERSION, '5.4.0rc1', '<=')) {
            $this->markTestSkipped('Requires PHP 5.4');
        }

        $handler = new CallbackHandler('LaminasTest\\Stdlib\\SignalHandlers\\InstanceMethod::staticHandler');
        $error   = false;
        set_error_handler(function ($errno, $errstr) use (&$error) {
            $error = true;
        }, E_STRICT);
        $result = $handler->call(array(1, 2, 3, 4));
        restore_error_handler();
        $this->assertFalse($error);
        $this->assertSame('staticHandler', $result);
    }

    public function testCallbackToClassImplementingOverloadingButNotInvocableShouldRaiseException()
    {
        $this->setExpectedException('Laminas\Stdlib\Exception\InvalidCallbackException');
        $handler = new CallbackHandler('foo', array( 'LaminasTest\\Stdlib\\SignalHandlers\\Overloadable', 'foo' ));
    }

    public function testClosureCallbackShouldBeInvokedByCall()
    {
        $handler = new CallbackHandler(function () {
            return 'foo';
        });
        $this->assertEquals('foo', $handler->call());
    }

    public function testHandlerShouldBeInvocable()
    {
        $handler = new CallbackHandler(array($this, 'handleCall'));
        $handler('foo', 'bar');
        $this->assertEquals(array('foo', 'bar'), $this->args);
    }

    public function handleCall()
    {
        $this->args = func_get_args();
    }
}
