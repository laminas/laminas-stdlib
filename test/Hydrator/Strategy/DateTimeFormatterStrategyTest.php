<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator\Strategy;

use Laminas\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy;

/**
 * Tests for {@see \Laminas\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy}
 *
 * @covers \Laminas\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy
 */
class DateTimeFormatterStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrate()
    {
        $strategy = new DateTimeFormatterStrategy('Y-m-d');
        $this->assertEquals('2014-04-26', $strategy->hydrate('2014-04-26')->format('Y-m-d'));

        $strategy = new DateTimeFormatterStrategy('Y-m-d', new \DateTimeZone('Asia/Kathmandu'));

        $date = $strategy->hydrate('2014-04-26');
        $this->assertEquals('Asia/Kathmandu', $date->getTimezone()->getName());
    }

    public function testExtract()
    {
        $strategy = new DateTimeFormatterStrategy('d/m/Y');
        $this->assertEquals('26/04/2014', $strategy->extract(new \DateTime('2014-04-26')));
    }

    public function testGetNullWithInvalidDateOnHydration()
    {
        $strategy = new DateTimeFormatterStrategy('Y-m-d');
        $this->assertEquals(null, $strategy->hydrate(null));
        $this->assertEquals(null, $strategy->hydrate(''));
    }

    public function testCanExtractIfNotDateTime()
    {
        $strategy = new DateTimeFormatterStrategy();
        $date = $strategy->extract(new \stdClass);

        $this->assertInstanceOf('stdClass', $date);
    }

    public function testCanHydrateWithInvalidDateTime()
    {
        $strategy = new DateTimeFormatterStrategy('d/m/Y');
        $this->assertSame('foo bar baz', $strategy->hydrate('foo bar baz'));
    }

    public function testAcceptsStringCastableDateTimeFormat()
    {
        $format = $this->getMock('stdClass', ['__toString']);

        $format->expects($this->once())->method('__toString')->will($this->returnValue('d/m/Y'));

        $strategy = new DateTimeFormatterStrategy($format);

        $this->assertEquals('26/04/2014', $strategy->extract(new \DateTime('2014-04-26')));
        $this->assertEquals('26/04/2015', $strategy->extract(new \DateTime('2015-04-26')));
    }
}
