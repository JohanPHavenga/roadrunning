<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// ================================================================
// Formulate display for yes no
// ================================================================
if (!function_exists('fyesNo')) {

    function fyesNo($bool) {
        if ($bool) {
            return "Yes";
        } else {
            return "No";
        }
    }

}
// ================================================================
// Formulate display of race distance
// ================================================================
if (!function_exists('fraceDistance')) {

    function fraceDistance($distance) {
        if ($distance) {
            return floatval($distance) . "km";
        } else {
            return false;
        }
    }

}
// ================================================================
// Formulate Currency
// ================================================================
if (!function_exists('fdisplayCurrency')) {

    function fdisplayCurrency($amount, $des = 0) {
        if ($amount) {
            return "R" . number_format($amount, $des, '.', '');
        } else {
            return false;
        }
    }

}
// ================================================================
// Formulate Dates / TIME
// ================================================================
if (!function_exists('fdateDay')) {

    function fdateDay($date) {
        if ($date > 0) {
            return date("d", strtotime($date));
        } else {
            return false;
        }
    }

}

if (!function_exists('fdateShort')) {

    function fdateShort($date) {
        if ($date > 0) {
            return date("Y-m-d", strtotime($date));
        } else {
            return false;
        }
    }

}

if (!function_exists('fdateHuman')) {

    function fdateHuman($date) {
        if ($date > 0) {
            return date("D j M", strtotime($date));
        } else {
            return false;
        }
    }

}

if (!function_exists('fdateHumanFull')) {

    function fdateHumanFull($date, $show_dotw = FALSE) {
        if ($date > 0) {
            if ($show_dotw) {
                return date("l, j F Y", strtotime($date));
            } else {
                return date("j F Y", strtotime($date));
            }
        } else {
            return false;
        }
    }

}

if (!function_exists('fdateLong')) {

    function fdateLong($date, $show_sec = TRUE) {
        if ($date) {
            if ($show_sec) {
                return date("Y-m-d H:i:s", strtotime($date));
            } else {
                return date("Y-m-d H:i", strtotime($date));
            }
        } else {
            return false;
        }
    }

}

if (!function_exists('fdateYear')) {

    function fdateYear($date) {
        if ($date) {
            return date("Y", strtotime($date));
        } else {
            return false;
        }
    }

}

if (!function_exists('ftimeSort')) {

    function ftimeSort($time) {
        if ($time) {
            return date("H:i", strtotime($time));
        } else {
            return 0;
        }
    }

}

if (!function_exists('fdateToCal')) {

    function fdateToCal($timestamp) {
        if ($timestamp) {
            return date('Ymd\THis', $timestamp);
        } else {
            return false;
        }
    }

}

if (!function_exists('fdateStructured')) {

    function fdateStructured($timestamp) {
        if ($timestamp) {
            return date('Y-m-d\TH:i:s' . '+02:00', strtotime($timestamp));
        } else {
            return false;
        }
    }

}

if (!function_exists('fdateTitle')) {

    function fdateTitle($date) {
        if ($date > 0) {
            return date("D, d M", strtotime($date));
        } else {
            return false;
        }
    }

}