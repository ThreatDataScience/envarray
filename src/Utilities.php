<?php


namespace ThreatDataScience\EnvArray;


class Utilities
{

    /**
     * https://stackoverflow.com/a/173479
     *
     * @param array $arr
     * @return bool
     */
    public static function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * https://stackoverflow.com/a/834355
     *
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public static function stringStartsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * https://stackoverflow.com/a/834355
     *
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public static function stringEndsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    public static function stringContains($haystack, $needle)
    {
        return (strpos($haystack, $needle) !== false);
    }

}