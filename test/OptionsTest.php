<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use ArrayObject;
use Laminas\Stdlib\Exception\InvalidArgumentException;
use LaminasTest\Stdlib\TestAsset\TestOptions;
use LaminasTest\Stdlib\TestAsset\TestOptionsNoStrict;

class OptionsTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructionWithArray()
    {
        $options = new TestOptions(array('test_field' => 1));

        $this->assertEquals(1, $options->test_field);
    }

    public function testConstructionWithTraversable()
    {
        $config = new ArrayObject(array('test_field' => 1));
        $options = new TestOptions($config);

        $this->assertEquals(1, $options->test_field);
    }

    public function testConstructionWithOptions()
    {
        $options = new TestOptions(new TestOptions(array('test_field' => 1)));

        $this->assertEquals(1, $options->test_field);
    }

    public function testInvalidFieldThrowsException()
    {
        $this->setExpectedException('BadMethodCallException');
        $options = new TestOptions(array('foo' => 'bar'));
    }

    public function testNonStrictOptionsDoesNotThrowException()
    {
        try {
            $options = new TestOptionsNoStrict(array('foo' => 'bar'));
        } catch (\Exception $e) {
            $this->fail('Nonstrict options should not throw an exception');
        }
    }

    public function testConstructionWithNull()
    {
        try {
            $options = new TestOptions(null);
        } catch (InvalidArgumentException $e) {
            $this->fail("Unexpected InvalidArgumentException raised");
        }
    }

    public function testUnsetting()
    {
        $options = new TestOptions(array('test_field' => 1));

        $this->assertEquals(true, isset($options->test_field));
        unset($options->testField);
        $this->assertEquals(false, isset($options->test_field));
    }

    public function testUnsetThrowsInvalidArgumentException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $options = new TestOptions;
        unset($options->foobarField);
    }

    public function testGetThrowsBadMethodCallException()
    {
        $this->setExpectedException('BadMethodCallException');
        $options = new TestOptions();
        $options->fieldFoobar;
    }

    public function testSetFromArrayAcceptsArray()
    {
        $array = array('test_field' => 3);
        $options = new TestOptions();

        $this->assertSame($options, $options->setFromArray($array));
        $this->assertEquals(3, $options->test_field);
    }

    public function testSetFromArrayThrowsInvalidArgumentException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $options = new TestOptions;
        $options->setFromArray('asd');
    }

    public function testExceptionMessageContainsActualUsedSetter()
    {
        $this->setExpectedException(
            'BadMethodCallException',
            'The option "foo bar" does not have a matching "setFooBar" ("setfoo bar") setter method which must be defined'
        );

        new TestOptions(array(
            'foo bar' => 'baz',
        ));
    }
}
