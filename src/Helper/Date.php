<?php

namespace Theme\Helper;

class Date
{
    /**
     * Returns an array with either the abbreviated or full days of the week.
     *
     * @param  boolean $long Whether to return abbreviated or full day names
     *
     * @return array<string> Array containing days of the week
     */
    public static function days_of_the_week($long = true)
    {
        if ($long) {
            return ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        } else {
            return ['Sun', 'Mon', 'Tues', 'Wed', 'Thur', 'Fri', 'Sat'];
        }
    }

    /**
     * Get a specific day of the week based on a 0-based index (e.g. 0 = Sunday)
     *
     * @param  integer $day  0-Based number for day of the week
     * @param  boolean $long Whether to learn the abbreviated or full day name
     *
     * @return string         Day of the week
     */
    public static function day_of_the_week($day, $long = true)
    {
        $days = self::days_of_the_week($long);

        return $days[$day];
    }

    /**
     * Creates an array of numbers within a given range with optional 0 padding. When using $match,
     * the index may match the value. So if the starting number is 10 then $match = true would return
     * 10 => 10, while $match = false would return 0 => 10.
     *
     * @param  integer $start   Number to start from
     * @param  integer $end     Number to end on
     * @param  boolean $match   Whether to match the index to the value
     * @param  boolean $padding Whether or not to 0-pad the values to have equal value characters
     *
     * @return array<integer>   An array with the enumerated values
     */
    private static function enumerate_array($start, $end, $match, $padding)
    {
        $result   = [];
        $pad_size = $padding ? strlen($end) : null;
        for ($count = $start; $count <= $end; $count++) {
            $value          = $padding ? sprintf('%0' . $pad_size . 'd', $count) : $count;
            $index          = $match ? $value : $count;
            $result[$index] = $value;
        }

        return $result;
    }

    /**
     * Returns an array with the hours in a day
     *
     * @param  boolean $twenty_four_hour Whether to return a 12 or 24-hour array
     * @param  boolean $match            Whether the index should match the value
     * @param  boolean $padding          Whether 0-padding should be applied (e.g. 01 vs 1)
     *
     * @return array<integer>            An numeric array with the hours
     */
    public static function hours($twenty_four_hour = false, $match = true, $padding = false)
    {
        return self::enumerate_array(1, $twenty_four_hour ? 24 : 12, $match, $padding);
    }

    /**
     * Returns an array with the minutes in a day
     *
     * @param  boolean $match   Whether the index should match the value
     * @param  boolean $padding Whether 0-padding should be applied (e.g. 01 vs 1)
     *
     * @return array<integer>            An numeric array with the minutes
     */
    public static function minutes($match = false, $padding = true)
    {
        return self::enumerate_array(0, 60, $match, $padding);
    }
}
