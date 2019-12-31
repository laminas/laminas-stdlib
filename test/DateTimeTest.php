<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\DateTime;

/**
 * @category   Laminas
 * @package    Laminas_Stdlib
 * @subpackage UnitTests
 * @group      Laminas_Stdlib
 */
class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    public $dateTime;

    public function testCreatesIS08601WithoutFractionalSeconds()
    {
        $time = '2009-03-07T08:03:50Z';

        $date = DateTime::createFromISO8601($time);

        $this->assertEquals( \DateTime::createFromFormat(\DateTime::ISO8601, $time), $date);
    }

    public function testCreatesIS08601WithFractionalSeconds()
    {
        $time = '2009-03-07T08:03:50.012Z';

        $date = DateTime::createFromISO8601($time);

        $standard = \DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $time);

        $this->assertEquals( $standard, $date);
    }
}
