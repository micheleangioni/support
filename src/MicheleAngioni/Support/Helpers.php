<?php

namespace MicheleAngioni\Support;

use DateTime;

class Helpers
{
    /**
     *  Check if input value is integer: return true on success, false otherwise.
     *  int(4), string '4', float(4), 0x7FFFFFFF return true
     *  int(4.1), string '1.2', string '0x8', float(1.2) return false.
     *  min and max allowed values can be inserted
     *
     * @param  int $int
     * @param  int|null $min
     * @param  int|null $max
     *
     * @return bool
     */
    static public function isInt($int, int $min = null, int $max = null): bool
    {
        if (is_object($int) || is_array($int) || is_callable($int)) {
            return false;
        }

        if ($min !== null) {
            if (is_numeric($min) && (int)$min == $min) {
                if ($int < $min) {
                    return false;
                }
            } else {
                return false;
            }
        }

        if ($max !== null) {
            if (is_numeric($max) && (int)$max == $max) {
                if ($int > $max) {
                    return false;
                }
            } else {
                return false;
            }
        }

        return is_numeric($int) && (int)$int == $int;
    }

    /**
     * Return a random value out of an array
     *
     * @param  array $array
     *
     * @return mixed
     */
    static public function randInArray(array $array)
    {
        return $array[mt_rand(0, count($array) - 1)];
    }

    /**
     *  Check date validity. Return true on success or false on failure.
     *
     * @param  string $date
     * @param  string $format = 'Y-m-d'
     *
     * @return bool
     */
    static public function checkDate(string $date, string $format = 'Y-m-d'): bool
    {
        $d = DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) == $date;
    }

    /**
     *  Check datetime 'Y-m-d H:i:s' validity. Returns true if ok or false if it fails.
     *
     * @param  string $datetime
     *
     * @return bool
     */
    static public function checkDatetime(string $datetime): bool
    {
        return self::checkDate($datetime, 'Y-m-d H:i:s');
    }

    /**
     *  Split two 'Y-m-d'-format dates into an array of dates. Returns null on failure.
     *  $firstDate must be < than $secondDate
     *  Third optional parameter indicates max days difference allowed (0 = no limits).
     *
     * @param  string $firstDate
     * @param  string $secondDate
     * @param  int $maxDifference = 0
     *
     * @return array
     */
    static public function splitDates(string $firstDate, string $secondDate, int $maxDifference = 0):? array
    {
        if (!self::checkDate($firstDate) || !self::checkDate($secondDate)) {
            return null;
        }

        if (!self::isInt($maxDifference, 0)) {
            return null;
        }

        $date1 = new DateTime($firstDate);
        $date2 = new DateTime($secondDate);
        $interval = $date1->diff($date2, false);

        if ((int)$interval->format('%R%a') < 0) {
            return null;
        }

        if ($maxDifference != 0) {
            if ((int)$interval->format('%R%a') > $maxDifference) {
                return null;
            }
        }

        list($year, $month, $day) = array_pad(explode("-", $firstDate), 3, 0);

        $i = 0;
        $newDate = $firstDate;
        $dates = [];

        while ($newDate <= $secondDate) {
            $dates[] = $newDate;
            $i++;
            $newDate = date("Y-m-d", mktime(0, 0, 0, $month, $day + $i, $year));
        }

        return $dates;
    }

    /**
     * Return the number of days between the two input 'Y-m-d' or 'Y-m-d X' (X is some text) dates.
     * $date2 must be >= than $date1.
     * Returns null on failure.
     *
     * @param  string $date1
     * @param  string $date2
     *
     * @return int|null
     */
    static public function daysBetweenDates(string $date1, string $date2):? int
    {
        // If input dates have datetime 'Y-m-d X' format, take only the date part
        list($d1) = array_pad(explode(' ', $date1), 1, 0);
        list($d2) = array_pad(explode(' ', $date2), 1, 0);

        if (!self::checkDate($d1) || !self::checkDate($d2)) {
            return null;
        }

        if (!($dates = self::splitDates($d1, $d2))) {
            return null;
        }

        return (count($dates) - 1);
    }

    
    // <<<--- PSEUDO-RANDOM NUMBERS METHODS --->>>

    /**
     * Return a random value between input $min and $max values by using the MCRYPT_DEV_URANDOM source.
     * N.B. Use only on *nix servers!
     *
     * @param  int $min = 0
     * @param  int $max
     *
     * @return int
     */
    static public function getRandomValueUrandom(int $min = 0, int $max = 0x7FFFFFFF): int
    {
        if ($max < $min || ($max - $min) > 0x7FFFFFFF) {
            return false;
        }

        $diff = $max - $min;

        $bytes = mcrypt_create_iv(4, MCRYPT_DEV_URANDOM);

        if ($bytes === false || strlen($bytes) != 4) {
            return false;
        }

        $ary = unpack("Nint", $bytes);
        $val = $ary['int'] & 0x7FFFFFFF;   // 32-bit safe
        $fp = (float)$val / 2147483647.0; // convert to [0,1]

        return round($fp * $diff) + $min;
    }

    /**
     * Return $quantity UNIQUE random value between $min and $max.
     * Return null on failure.
     *
     * @param  int $min = 0
     * @param  int $max
     * @param  int $quantity = 1
     *
     * @return array|null
     */
    public function getUniqueRandomValues(int $min = 0, int $max, int $quantity = 1):? array
    {
        if ($min > $max || $quantity < 0) {
            return null;
        }

        $rand = [];

        while (count($rand) < $quantity) {
            $r = mt_rand($min, $max);
            if (!in_array($r, $rand)) {
                $rand[] = $r;
            }
        }

        return $rand;
    }
}
