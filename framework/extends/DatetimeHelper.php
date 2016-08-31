<?php

/**
 * Class DatetimeHelper
 */
class DatetimeHelper {

    /**
     * Get Y-m-d H:i:s datetime string after now.
     *
     * @param $days int The days after.
     * @return string Datetime string.
     */
    static function afterNow($days) {
        $timestamp = strtotime(date('Y-m-d H:i:s'));
        return date('Y-m-d H:i:s', $timestamp + (24 * 60 * 60 * $days) );
    }

    /**
     * Get Y-m-d H:i:s datetime string beforeNow now.
     *
     * @param $days int The days before.
     * @return string Datetime string.
     */
    static function beforeNow($days) {
        $timestamp = strtotime(date('Y-m-d H:i:s'));
        return date('Y-m-d H:i:s', $timestamp - (24 * 60 * 60 * $days) );
    }
}

?>