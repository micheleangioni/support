<?php

namespace TopGames\Support;

use DateTime;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class Helpers {

    /**
     *  Check if input value is integer: return true on success, false otherwise.
     *  int(4), string '4', float(4), 0x7FFFFFFF return true
     *  int(4.1), string '1.2', string '0x8', float(1.2) return false.
     *  min and max allowed values can be inserted
     *
     * @param  int $int
     * @param  bool|int $min = false
     * @param  bool|int $max = false
     *
     * @return bool
     */
	static function isInt($int, $min = false, $max = false)
	{
		if($min!= false){
			if(is_numeric($min) && (int)$min == $min){
				if($int < $min){
					return false;
				}
			}
			else{
				return false;
			}
		}
		
		if($max!= false){
			if(is_numeric($max) && (int)$max == $max){
				if($int > $max){
					return false;
				}
			}
			else{
				return false;
			}
		}
		
		return is_numeric($int) && (int)$int == $int;
	}


    /**
     * Return a random value out of an array
     *
     * @param array $array
     * @return mixed
     */
    static function RandInArray(array $array)
    {
        return $array[mt_rand(0, count($array) - 1)];
    }


	/**
	 *  Check date validity. Return true on success or false on failure.
	 *
	 * @param  string  $date
	 * @param  string  $format = 'Y-m-d'
	 * @return bool
	 */
	static function checkDate($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	

	/**
	 *  Check datetime 'Y-m-d H:i:s' validity. Returns true if ok or false if it fails.
	 *
	 * @param  string  $datetime
	 * @return bool
	 */
	static function checkDatetime($datetime)
	{
		$format = 'Y-m-d H:i:s';
		
		$d = DateTime::createFromFormat($format, $datetime);
		return $d && $d->format($format) == $datetime;
	}
	
	
	/**
	 *  Split two 'Y-m-d'-format dates into an array of dates. Returns false if it fails.
	 *  $first_date must be < than $second_date
	 *  Third optional parameter indicates max days difference allowed (0 = no limits).
	 *
	 * @param  $first_date
	 * @param  $second_date
	 * @param  int $max_difference = 0
     *
	 * @return array
	 */
	static function splitDates($first_date, $second_date, $max_difference = 0)
	{
		if( !self::checkDate($first_date) || !self::checkDate($second_date)){
			return false;
		}

		if (!self::isInt($max_difference, 0)){
			return false;
		}
		
		$date1 = new DateTime($first_date);
		$date2 = new DateTime($second_date);
		$interval = $date1->diff($date2, $absolute = false);
		
		if((int)$interval->format('%R%a') < 0){
			return false;
		}
		
		if($max_difference != 0){
			if((int)$interval->format('%R%a') > $max_difference){
				return false;
			}
		}
		
		list($year,$month,$day) = array_pad(explode("-",$first_date), 3, 0);
			
		$i = 0;
		$new_date = $first_date;
        $dates = array();
		
		while($new_date <= $second_date)
		{
			$dates[] = $new_date;
			$i++;
			$new_date = date("Y-m-d",mktime(0,0,0,$month,$day+$i,$year));
		}
		
		return $dates;
	}


	/**
	 * Return the number of days between the two input 'Y-m-d' or 'Y-m-d X' (X is some text) dates
	 * $date2 must be >= than $date1.
	 * Returns false on failure.
	 *
	 * @param  $date1
	 * @param  $date2
     *
	 * @return int
	 */
	static function daysBetweenDates($date1, $date2)
	{
		// If input dates have datetime 'Y-m-d X' format, take only the date part
		list($d1) = array_pad(explode(' ',$date1), 1, 0);
		list($d2) = array_pad(explode(' ',$date2), 1, 0);
		
		if( !self::checkDate($d1) || !self::checkDate($d2)){
			return false;
		}
		
		if( !($dates = self::splitDates($d1, $d2)) ) {
			return false;
		}
		
		return (count($dates) - 1);
	}


    /**
     * Compare $date with $referenceDate. Return true if $date is newer, false otherwise (included if the two dates are identical).
     *
     * @param $date
     * @param $referenceDate
     * @return bool
     */
    static function compareDates($date, $referenceDate)
    {
        $dateTimestamp = strtotime($date);
        $referenceDateTimestamp = strtotime($referenceDate);

        if ($dateTimestamp > $referenceDateTimestamp)
            return true;
        else
            return false;
    }


    /**
     * Split a Collection into groups of equal numbers. $groupsNumber must be a multiplier of 2.
     *
     * @param  Collection $collection
     * @param  int $groupsNumber = 2
     * @throws InvalidArgumentException
     *
     * @return array
     */
    static function divideCollectionIntoGroups(Collection $collection, $groupsNumber = 2)
    {
        if( !(Helpers::isInt($groupsNumber,2) && !($groupsNumber % 2)) ) {
            return false;
        }

        $elementsPerGroup = (int)ceil(count($collection) / $groupsNumber);

        $newCollection = new Collection([]);

        for($i = 0; $i <= $groupsNumber - 1; $i++) {
            $newCollection[$i] = $collection->slice($i * $elementsPerGroup, $elementsPerGroup);
        }

        return $newCollection;
    }





    // <<<--- NON STATIC METHODS METHODS --->>>


    // <<<--- DATE TIME METHODS --->>>

    /*
     * These DateTime methods are thought in order to allow Date / Time mocking in tests and other useful uses.
     */

    /**
     * Return today's day
     *
     * @return string
     */
    function getTodayDay()
    {
        $datetime = new \DateTime("now");
        return $datetime->format("D");
    }

    /**
     * Return today's day in format Y-m-d. Offset in days.
     *
     * @param  int  $offset = 0
     * @return string
     */
    function getDate($offset = 0)
    {
        return date("Y-m-d", strtotime($offset.' day'));
    }

    /**
     * Return today's time in format H:i:s. Offset in minutes.
     *
     * @param  int  $offset = 0
     * @return string
     */
    function getTime($offset = 0)
    {
        return date("H:i:s", strtotime($offset.' minutes'));
    }


    // <<<--- PSEUDO-RANDOM NUMBERS METHODS --->>>


	/**
	 * Return a random value between input $min and $max values by using the MCRYPT_DEV_URANDOM source.
     * N.B. Use only on Linux servers!
	 *
	 * @param  int  $min = 0
	 * @param  int  $max
     *
	 * @return int
	 */
	static function getRandomValueUrandom($min = 0, $max = 0x7FFFFFFF)
	{
		if( !self::isInt($min) || !self::isInt($max) || $max < $min || ($max - $min) > 0x7FFFFFFF ) {
			return false;
		}
		
		$diff = $max - $min;
		 
		$bytes = mcrypt_create_iv(4, MCRYPT_DEV_URANDOM);
		 
		if ($bytes === false || strlen($bytes) != 4) {
			return false;
		}
		 
		$ary = unpack("Nint", $bytes);
		$val = $ary['int'] & 0x7FFFFFFF;   // 32-bit safe
		$fp = (float) $val / 2147483647.0; // convert to [0,1]
		 
		return round($fp * $diff) + $min;
	}
	

	/**
	 * Return $quantity UNIQUE random value between $min and $max.
	 * Return false on failure.
	 *
	 * @param  int  $min = 0
	 * @param  int  $max
	 * @param  int  $quantity = 1
     *
	 * @return array
	 */
	function getUniqueRandomValues($min = 0, $max, $quantity = 1)
	{
		if( !self::isInt($min) || !self::isInt($max) || !self::isInt($quantity) || $quantity < 1) {
			return false;
		}
	
		$rand = array();
	
		while (count($rand) < $quantity) {
			$r = mt_rand($min,$max);
			if (!in_array($r,$rand)) $rand[] = $r;
		}
	
		return $rand;
	}
	
}