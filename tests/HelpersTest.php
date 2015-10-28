<?php

use MicheleAngioni\Support\Helpers as H;

class HelpersTest extends PHPUnit_Framework_TestCase {

	public function testIsInt()
    {
        $this->assertTrue(H::isInt(3));
        $this->assertTrue(H::isInt('3'));
        $this->assertFalse(H::isInt(3.2));
        $this->assertFalse(H::isInt('3.2'));
        $this->assertFalse(H::isInt('a'));
        $this->assertFalse(H::isInt('3a'));
        $this->assertFalse(H::isInt('__'));
    }

    public function testRandInArray()
    {
        $array = ['1', 1, 2, 3, '5'];

        $value = H::randInArray($array);

        $this->assertTrue(in_array($value, $array));
    }

    public function testCheckDate()
    {
        date_default_timezone_set('UTC');
        $this->assertTrue(H::checkDate('2014-12-24'));
        $this->assertTrue(H::checkDate('2014-24-12', 'Y-d-m'));
        $this->assertFalse(H::checkDate('2014-12-32'));
        $this->assertFalse(H::checkDate('2014-24-12'));
        $this->assertFalse(H::checkDate('aa'));
        $this->assertFalse(H::checkDate('2014--12-24'));
    }

    public function testCheckDateTime()
    {
        date_default_timezone_set('UTC');
        $this->assertTrue(H::checkDateTime('2014-12-24 18:24:02'));
        $this->assertFalse(H::checkDateTime('2014-12-24 18:24:61'));
        $this->assertFalse(H::checkDateTime('2014-12-24'));
        $this->assertFalse(H::checkDateTime('2014-12-32'));
        $this->assertFalse(H::checkDateTime('2014-24-12'));
        $this->assertFalse(H::checkDateTime('aa'));
        $this->assertFalse(H::checkDateTime('2014--12-24'));
    }

    public function testCheckSplitDates()
    {
        date_default_timezone_set('UTC');
        $dates = H::splitDates('2014-12-24', '2014-12-26');
        $this->assertEquals(3, count($dates));

        $dates = H::splitDates('2014-12-24', '2014-12-24');
        $this->assertEquals(1, count($dates));

        $this->assertFalse(H::splitDates('2014-12-26', '2014-12-24', 1));
        $this->assertFalse(H::splitDates('2014-12-24', '2014-12-26', 1));
        $this->assertFalse(H::splitDates('2014-12-24', '2014-12-26x'));
    }

    public function testDaysBetweenDates()
    {
        date_default_timezone_set('UTC');
        $this->assertEquals(2, H::daysBetweenDates('2014-12-24', '2014-12-26'));
        $this->assertEquals(0, H::daysBetweenDates('2014-12-24', '2014-12-24'));
        $this->assertFalse(H::daysBetweenDates('2014-12-24', '2014-12-23'));
        $this->assertFalse(H::daysBetweenDates('2014-12-23', '2014-12-24x'));
    }

    public function testCompareDates()
    {
        date_default_timezone_set('UTC');
        $this->assertTrue(H::compareDates('2014-12-24', '2014-12-22'));
        $this->assertFalse(H::compareDates('2014-12-22', '2014-12-24'));
        $this->assertFalse(H::compareDates('2014-12-24', '2014-12-24'));
    }

    public function testDivideCollectionIntoGroups()
    {
        $collection = new \Illuminate\Support\Collection;
        $collection->push(1);
        $collection->push(2);
        $collection->push(3);
        $collection->push(4);

        $newCollection = H::divideCollectionIntoGroups($collection);
        $this->assertEquals(2, $newCollection->count());

        $this->assertFalse(H::divideCollectionIntoGroups($collection, 3));
    }

    public function testGetUniqueRandomValues()
    {
        $helpers = new H;

        $numbers = $helpers->getUniqueRandomValues(1, 100, 4);
        $this->assertEquals(4, count($numbers));

        $this->assertNotEquals($numbers[0], $numbers[1]);
        $this->assertNotEquals($numbers[0], $numbers[2]);
        $this->assertNotEquals($numbers[0], $numbers[3]);
        $this->assertNotEquals($numbers[1], $numbers[2]);
        $this->assertNotEquals($numbers[1], $numbers[3]);
        $this->assertNotEquals($numbers[2], $numbers[3]);
    }

    public function tearDown()
    {
        Mockery::close();
    }

}
