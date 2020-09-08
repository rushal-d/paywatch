<?php

namespace App\Helpers;

use App\FiscalYearModel;
use Carbon\Carbon;

class BSDateHelper
{

    // Data for nepali date
    static $_bs = array(
        0 => array(2000, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        1 => array(2001, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        2 => array(2002, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        3 => array(2003, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        4 => array(2004, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        5 => array(2005, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        6 => array(2006, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        7 => array(2007, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        8 => array(2008, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31),
        9 => array(2009, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        10 => array(2010, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        11 => array(2011, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        12 => array(2012, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
        13 => array(2013, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        14 => array(2014, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        15 => array(2015, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        16 => array(2016, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
        17 => array(2017, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        18 => array(2018, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        19 => array(2019, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        20 => array(2020, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
        21 => array(2021, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        22 => array(2022, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
        23 => array(2023, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        24 => array(2024, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
        25 => array(2025, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        26 => array(2026, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        27 => array(2027, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        28 => array(2028, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        29 => array(2029, 31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30),
        30 => array(2030, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        31 => array(2031, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        32 => array(2032, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        33 => array(2033, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        34 => array(2034, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        35 => array(2035, 30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31),
        36 => array(2036, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        37 => array(2037, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        38 => array(2038, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        39 => array(2039, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
        40 => array(2040, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        41 => array(2041, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        42 => array(2042, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        43 => array(2043, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
        44 => array(2044, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        45 => array(2045, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        46 => array(2046, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        47 => array(2047, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
        48 => array(2048, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        49 => array(2049, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
        50 => array(2050, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        51 => array(2051, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
        52 => array(2052, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        53 => array(2053, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
        54 => array(2054, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        55 => array(2055, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        56 => array(2056, 31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30),
        57 => array(2057, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        58 => array(2058, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        59 => array(2059, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        60 => array(2060, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        61 => array(2061, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        62 => array(2062, 30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31),
        63 => array(2063, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        64 => array(2064, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        65 => array(2065, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        66 => array(2066, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31),
        67 => array(2067, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        68 => array(2068, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        69 => array(2069, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        70 => array(2070, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
        71 => array(2071, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        72 => array(2072, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30),
        73 => array(2073, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
        74 => array(2074, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
        75 => array(2075, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        76 => array(2076, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
        77 => array(2077, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
        78 => array(2078, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
        79 => array(2079, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
        80 => array(2080, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
        81 => array(2081, 31, 31, 32, 32, 31, 30, 30, 30, 29, 30, 30, 30),
        82 => array(2082, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30),
        83 => array(2083, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30),
        84 => array(2084, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30),
        85 => array(2085, 31, 32, 31, 32, 30, 31, 30, 30, 29, 30, 30, 30),
        86 => array(2086, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30),
        87 => array(2087, 31, 31, 32, 31, 31, 31, 30, 30, 29, 30, 30, 30),
        88 => array(2088, 30, 31, 32, 32, 30, 31, 30, 30, 29, 30, 30, 30),
        89 => array(2089, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30),
        90 => array(2090, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30)
    );

    private $_nep_date = array('year' => '', 'month' => '', 'date' => '', 'day' => '', 'nmonth' => '', 'num_day' => '');
    private $_eng_date = array('year' => '', 'month' => '', 'date' => '', 'day' => '', 'emonth' => '', 'num_day' => '');
    public $debug_info = "";

    /**
     * Return day
     *
     * @param int $day
     * @return string
     */
    private static function _get_day_of_week($day)
    {
        switch ($day) {
            case 1:
                $day = "आईतवार";
                break;
            case 2:
                $day = "सोमबार";
                break;
            case 3:
                $day = "मंगलवार";
                break;
            case 4:
                $day = "बुधबार";
                break;
            case 5:
                $day = "बिहीबार";
                break;
            case 6:
                $day = "शुक्रबार";
                break;
            case 7:
                $day = "शनिबार";
                break;
        }
        return $day;
    }

    /**
     * Return english month name
     *
     * @param int $m
     * @return string
     */
    private static function _get_english_month($m)
    {
        $eMonth = FALSE;
        switch ($m) {
            case 1:
                $eMonth = "January";
                break;
            case 2:
                $eMonth = "February";
                break;
            case 3:
                $eMonth = "March";
                break;
            case 4:
                $eMonth = "April";
                break;
            case 5:
                $eMonth = "May";
                break;
            case 6:
                $eMonth = "June";
                break;
            case 7:
                $eMonth = "July";
                break;
            case 8:
                $eMonth = "August";
                break;
            case 9:
                $eMonth = "September";
                break;
            case 10:
                $eMonth = "October";
                break;
            case 11:
                $eMonth = "November";
                break;
            case 12:
                $eMonth = "December";
        }
        return $eMonth;
    }

    /**
     * Return nepali month name
     *
     * @param int $m
     * @return string
     */
    public static function _get_nepali_month($m)
    {
        $n_month = FALSE;
        switch ($m) {
            case 1:
                $n_month = "बैशाख";
                break;
            case 2:
                $n_month = "जेष्ठ";
                break;
            case 3:
                $n_month = "असार";
                break;
            case 4:
                $n_month = "श्रावण";
                break;
            case 5:
                $n_month = "भाद्र";
                break;
            case 6:
                $n_month = "आश्विन";
                break;
            case 7:
                $n_month = "कार्तिक";
                break;
            case 8:
                $n_month = "मंसिर";
                break;
            case 9:
                $n_month = "पुष";
                break;
            case 10:
                $n_month = "माघ";
                break;
            case 11:
                $n_month = "फाल्गुन";
                break;
            case 12:
                $n_month = "चैत्र";
                break;
        }
        return $n_month;
    }

    /**
     * Check if date range is in english
     *
     * @param int $yy
     * @param int $mm
     * @param int $dd
     * @return bool
     */
    private static function _is_in_range_eng($yy, $mm, $dd)
    {
        if ($yy < 1944 || $yy > 2033) {
            return 'Supported only between 1944-2033';
        }
        if ($mm < 1 || $mm > 12) {
            return 'Error! month value can be between 1-12 only';
        }
        if ($dd < 1 || $dd > 31) {
            return 'Error! day value can be between 1-31 only';
        }
        return TRUE;
    }

    /**
     * Check if date is with in nepali data range
     *
     * @param int $yy
     * @param int $mm
     * @param int $dd
     * @return bool
     */
    private static function _is_in_range_nep($yy, $mm, $dd)
    {
        if ($yy < 2000 || $yy > 2089) {
            return 'Supported only between 2000-2089';
        }
        if ($mm < 1 || $mm > 12) {
            return 'Error! month value can be between 1-12 only';
        }
        if ($dd < 1 || $dd > 32) {
            return 'Error! day value can be between 1-31 only';
        }
        return TRUE;
    }

    /**
     * Calculates wheather english year is leap year or not
     *
     * @param int $year
     * @return bool
     */
    public static function is_leap_year($year)
    {
        $a = $year;
        if ($a % 100 == 0) {
            if ($a % 400 == 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            if ($a % 4 == 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    /**
     * currently can only calculate the date between AD 1944-2033...
     *
     * @param int $yy
     * @param int $mm
     * @param int $dd
     * @return array
     */
    public static function eng_to_nep($yy, $mm, $dd)
    {
        // Check for date range
        $chk = self::_is_in_range_eng($yy, $mm, $dd);
        if ($chk !== TRUE) {
            return false;
        } else {
            // Month data.
            $month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

            // Month for leap year
            $lmonth = array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
            $def_eyy = 1944;    // initial english date.
            $def_nyy = 2000;
            $def_nmm = 9;
            $def_ndd = 17 - 1;    // inital nepali date.
            $total_eDays = 0;
            $total_nDays = 0;
            $a = 0;
            $day = 7 - 1;
            $m = 0;
            $y = 0;
            $i = 0;
            $j = 0;
            $numDay = 0;
            // Count total no. of days in-terms year
            for ($i = 0; $i < ($yy - $def_eyy); $i++) //total days for month calculation...(english)
            {
                if (self::is_leap_year($def_eyy + $i) === TRUE) {
                    for ($j = 0; $j < 12; $j++) {
                        $total_eDays += $lmonth[$j];
                    }
                } else {
                    for ($j = 0; $j < 12; $j++) {
                        $total_eDays += $month[$j];
                    }
                }
            }
            // Count total no. of days in-terms of month
            for ($i = 0; $i < ($mm - 1); $i++) {
                if (self::is_leap_year($yy) === TRUE) {
                    $total_eDays += $lmonth[$i];
                } else {
                    $total_eDays += $month[$i];
                }
            }
            // Count total no. of days in-terms of date
            $total_eDays += $dd;
            $i = 0;
            $j = $def_nmm;
            $total_nDays = $def_ndd;
            $m = $def_nmm;
            $y = $def_nyy;
            // Count nepali date from array
            while ($total_eDays != 0) {
                $a = self::$_bs[$i][$j];

                $total_nDays++;        //count the days
                $day++;                //count the days interms of 7 days
                if ($total_nDays > $a) {
                    $m++;
                    $total_nDays = 1;
                    $j++;
                }

                if ($day > 7) {
                    $day = 1;
                }

                if ($m > 12) {
                    $y++;
                    $m = 1;
                }

                if ($j > 12) {
                    $j = 1;
                    $i++;
                }

                $total_eDays--;
            }
            $numDay = $day;
            /*$_nep_date['year'] = self::convert_to_nepali_number($y);
            $_nep_date['month'] = self::convert_to_nepali_number($m);
            $_nep_date['date'] = self::convert_to_nepali_number($total_nDays);
            $_nep_date['num_day'] = self::convert_to_nepali_number($numDay);*/
            $_nep_date['year'] = $y;
            $_nep_date['month'] = $m;
            $_nep_date['date'] = $total_nDays;
            $_nep_date['day'] = self::_get_day_of_week($day);
            $_nep_date['nmonth'] = $m;

            return $_nep_date;
        }
    }

    /**
     * Currently can only calculate the date between BS 2000-2089
     *
     * @param int $yy
     * @param int $mm
     * @param int $dd
     * @return array
     */
    public static function nep_to_eng($yy, $mm, $dd)
    {
        $def_eyy = 1943;
        $def_emm = 4;
        $def_edd = 14 - 1;    // initial english date.
        $def_nyy = 2000;
        $def_nmm = 1;
        $def_ndd = 1;        // iniital equivalent nepali date.
        $total_eDays = 0;
        $total_nDays = 0;
        $a = 0;
        $day = 4 - 1;
        $m = 0;
        $y = 0;
        $i = 0;
        $k = 0;
        $numDay = 0;
        $month = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $lmonth = array(0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        // Check for date range
        $chk = self::_is_in_range_nep($yy, $mm, $dd);
        if ($chk !== TRUE) {
            return false;
        } else {
            // Count total days in-terms of year
            for ($i = 0; $i < ($yy - $def_nyy); $i++) {
                for ($j = 1; $j <= 12; $j++) {
                    $total_nDays += self::$_bs[$k][$j];
                }
                $k++;
            }
            // Count total days in-terms of month
            for ($j = 1; $j < $mm; $j++) {
                $total_nDays += self::$_bs[$k][$j];
            }
            // Count total days in-terms of dat
            $total_nDays += $dd;
            // Calculation of equivalent english date...
            $total_eDays = $def_edd;
            $m = $def_emm;
            $y = $def_eyy;
            while ($total_nDays != 0) {
                if (self::is_leap_year($y)) {
                    $a = $lmonth[$m];
                } else {
                    $a = $month[$m];
                }
                $total_eDays++;
                $day++;
                if ($total_eDays > $a) {
                    $m++;
                    $total_eDays = 1;
                    if ($m > 12) {
                        $y++;
                        $m = 1;
                    }
                }
                if ($day > 7) {
                    $day = 1;
                }
                $total_nDays--;
            }

            $numDay = $day;
            $_eng_date['year'] = $y;
            $_eng_date['month'] = $m;
            $_eng_date['date'] = $total_eDays;
            $_eng_date['day'] = self::_get_day_of_week($day);
            $_eng_date['nmonth'] = self::_get_english_month($m);
            $_eng_date['num_day'] = $numDay;
            return $_eng_date;
        }
    }

    public static function convert_to_nepali_number($str)
    {
        $str = strval($str);
        $array = array(0 => '&#2406;',
            1 => '&#2407;',
            2 => '&#2408;',
            3 => '&#2409;',
            4 => '&#2410;',
            5 => '&#2411;',
            6 => '&#2412;',
            7 => '&#2413;',
            8 => '&#2414;',
            9 => '&#2415;',
            /*'.'=>'&#2404;'*/
        );
        $utf = "";
        $cnt = strlen($str);
        for ($i = 0; $i < $cnt; $i++) {
            if (!isset($array[$str[$i]])) {
                $utf .= $str[$i];
            } else
                $utf .= $array[$str[$i]];
        }
        return $utf;
    }

    public static function BsToAd($delimiter, $date)
    {
        $date_np = "";
        if (!empty($date)) {
            $np_date = explode($delimiter, $date);
            $date_np = self::nep_to_eng($np_date[0], $np_date[1], $np_date[2]);
            if ($date_np != false) {
                return $date_np['year'] . '-' . str_pad($date_np['month'], 2, "0", STR_PAD_LEFT) .
                    '-' . str_pad($date_np['date'], 2, "0", STR_PAD_LEFT);
            }
        }
        return $date_np;
    }

    public static function AdToBs($delimiter, $date)
    {
        $date_en = "";
        if (!empty($date)) {
            $np_date = explode($delimiter, $date);
            $date_np = self::eng_to_nep($np_date[0], $np_date[1], $np_date[2]);
            if ($date_np != false) {
                return $date_np['year'] . '-' . str_pad($date_np['month'], 2, "0", STR_PAD_LEFT) .
                    '-' . str_pad($date_np['date'], 2, "0", STR_PAD_LEFT);
            }
        }
        return $date_en;
    }

    public static function AdToBsEN($delimiter, $date)
    {
        $date_en = "";
        if (!empty($date)) {
            $np_date = explode($delimiter, $date);
            $date_np = self::eng_to_nep($np_date[0], $np_date[1], $np_date[2]);
            if ($date_np != false) {
                return $date_np['year_en'] . '-' . $date_np['month_en'] . '-' . $date_np['date_en'];
            }
        }
        return $date_en;
    }

    /** Return current fiscal year with id and year
     * @return bool|\Illuminate\Database\Eloquent\Model|null|static
     */
    public static function getCurrentFiscalYear($date_np)
    {
        if (empty($date_np)) {
            $current_nepali_date = self::AdToBsEN('-', date('Y-m-d'));
        } else {
            $current_nepali_date = $date_np;
        }
        $c_fy = explode('-', $current_nepali_date);
        $c_year = $c_fy[0];
        $c_month = $c_fy[1];
        $fiscal_year = '';
        if ($c_month >= 4 && $c_month <= 12) {
            $start_year = $c_year;
            $end_year = substr(((int)($c_year) + 1), 2);
            $fiscal_year = $start_year . '/' . $end_year;
        } else { //baisakh to asar
            $start_year = (int)$c_year - 1;
            $end_year = substr($c_year, 2);
            $fiscal_year = $start_year . '/' . $end_year;
        }
        $current_fiscal_year = \App\FiscalYear::where('fiscal_year', $fiscal_year)->first();
        if (!empty($current_fiscal_year)) {
            return $current_fiscal_year;
        }
        return false;
    }

    /** Return current fiscal year with id and year
     * @return bool|\Illuminate\Database\Eloquent\Model|null|static
     */
    public static function getYear($date_np)
    {
        if (empty($date_np)) {
            $current_nepali_date = self::AdToBsEN('-', date('Y-m-d'));
        } else {
            $current_nepali_date = $date_np;
        }
        $c_fy = explode('-', $current_nepali_date);
        $c_year = $c_fy[0];
        $c_month = $c_fy[1];
        $fiscal_year = '';
        if ($c_month >= 4 && $c_month <= 12) {
            $start_year = $c_year;
        } else { //baisakh to asar
            $start_year = (int)$c_year - 1;
        }
        if (!empty($start_year)) {
            return $start_year;
        }
        return false;
    }

    public static function getLastDayByYearMonth($year, $month)
    {
        $bs = self::$_bs;
        foreach ($bs as $date) {
            if ($date[0] == $year) {
                return $date[$month];
            }
        }
    }

    public static function getFirtDayOfMonthNPByDate($delimiter, $date)
    {
        $date_en = "";
        if (!empty($date)) {
            $np_date = explode($delimiter, $date);
            $date_np = self::eng_to_nep($np_date[0], $np_date[1], $np_date[2]);
            return $date_np['year'] . '-' . str_pad($date_np['month'], 2, "0", STR_PAD_LEFT) .
                '-' . str_pad(1, 2, "0", STR_PAD_LEFT);
        }
        return $date_en;
    }

    public static function getBSYearMonthDayArrayFromEnDate($date)
    {
        $enDate = Carbon::parse($date);
        return static::eng_to_nep($enDate->year, $enDate->month, $enDate->day);
    }

    public static function getMonthRange($from_month, $to_month)
    {
        if ($from_month > 12 || $from_month < 1 || $to_month > 12 || $to_month < 1) {
            return [];
        }
        // if the leave goes from Chaitra to Baisakh
        if ($from_month > $to_month) {
            $months_first = range($from_month, 12);
            $months_last = range(1, $to_month);
            $months = array_merge($months_first, $months_last);
        } else {
            $months = range($from_month, $to_month);
        }
        return $months;
    }

    public static function getMonthRangeFromDates($from_date_np, $to_date_np)
    {
        $from_month = explode('-', $from_date_np)[1];
        $to_month = explode('-', $to_date_np)[1];
        return static::getMonthRange($from_month, $to_month);
    }

    public static function getDaysInMonthOfDateRange($from_date_np, $to_date_np)
    {

        $from_month = explode('-', $from_date_np)[1];
        $from_year = explode('-', $from_date_np)[0];
        $to_month = explode('-', $to_date_np)[1];
        $to_year = explode('-', $to_date_np)[0];
        $from_date = self::BsToAd('-', $from_date_np);
        $to_date = self::BsToAd('-', $to_date_np);
        $months = self::getMonthRangeFromDates($from_date_np, $to_date_np);

        $month_days = [];
        $year = $from_year;
        foreach ($months as $month) {
            if (($from_month > $to_month) && $month == 1) {
                $year = $to_year;
            }
            if ($from_month == $to_month) {
                $days_in_month = self::daysDifferenceEn($from_date, $to_date);
                $calc_from = $from_date;
                $calc_to = $to_date;
                $calc_from_np = $from_date_np;
                $calc_to_np = $to_date_np;
            } elseif ($month == $from_month) {
                $last_day_in_month_np = $year . '-' . $month . '-' . self::getLastDayByYearMonth($year, $month);
                $last_day_in_month = self::BsToAd('-', $last_day_in_month_np);
                $days_in_month = self::daysDifferenceEn($from_date, $last_day_in_month);
                $calc_from = $from_date;
                $calc_to = $last_day_in_month;
                $calc_from_np = $from_date_np;
                $calc_to_np = $last_day_in_month_np;
            } elseif ($month == $to_month) {
                $first_day_in_month_np = $year . '-' . $month . '-01';
                $first_day_in_month = self::BsToAd('-', $first_day_in_month_np);
                $days_in_month = self::daysDifferenceEn($first_day_in_month, $to_date);

                $calc_from = $first_day_in_month;
                $calc_to = $to_date;
                $calc_from_np = $first_day_in_month_np;
                $calc_to_np = $to_date_np;

            } else {
                $days_in_month = self::getLastDayByYearMonth($year, $month);
                $calc_from_np = $year . '-' . $month . '-1';
                $calc_to_np = $year . '-' . $month . '-' . $days_in_month;
                $calc_from = self::BsToAd('-', $calc_from_np);
                $calc_to = self::BsToAd('-', $calc_to_np);

            }
            $month_days[$month]['days'] = $days_in_month;
            $month_days[$month]['from'] = $calc_from;
            $month_days[$month]['to'] = $calc_to;
            $month_days[$month]['from_np'] = $calc_from_np;
            $month_days[$month]['to_np'] = $calc_to_np;
        }
        return $month_days;
    }

    public static function daysDifferenceEn($date_from, $date_to)
    {
        $dateFrom = date_create($date_from);
        $dateTo = date_create($date_to);
        $holidayDays = date_diff($dateFrom, $dateTo)->format('%a') + 1;
        return $holidayDays;
    }

    public static function getStartDateFromFiscalYearAndMonth($fiscalYearId, $monthId)
    {
        $fiscalYear = FiscalYearModel::where('id', $fiscalYearId)->first();

        if (empty($fiscalYear)) {
            return null;
        }

        $fiscalYearSplit = explode('-', $fiscalYear->fiscal_start_date_np);
        if ($monthId >= 4 && $monthId <= 12) {
            $selectedYear = $fiscalYearSplit[0];
        } else { //baisakh to asar
            $selectedYear = (int)$fiscalYearSplit[0] + 1;
        }

        if (empty($selectedYear)) {
            return null;
        }

        return $selectedYear . '-'. $monthId . '-1';
    }

    public static function getEndDateFromFiscalYearAndMonth($fiscalYearId, $monthId)
    {
        $fiscalYear = FiscalYearModel::where('id', $fiscalYearId)->first();

        if (empty($fiscalYear)) {
            return null;
        }

        $fiscalYearSplit = explode('-', $fiscalYear->fiscal_start_date_np);
        if ($monthId >= 4 && $monthId <= 12) {
            $selectedYear = $fiscalYearSplit[0];
        } else { //baisakh to asar
            $selectedYear = (int)$fiscalYearSplit[0] + 1;
        }

        if (empty($selectedYear)) {
            return null;
        }

        $end_date_np = $selectedYear . '-' . $monthId . '-' . BSDateHelper::getLastDayByYearMonth($selectedYear, $monthId);

        return $end_date_np;
    }
}
