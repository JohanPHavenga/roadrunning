<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('wts')) {

    function wts($seq = '', $die = false) {
        echo "<pre>";
        print_r($seq);
        echo "</pre>";
        if ($die) {
            die();
        }
    }

    function get_url_from_edition_name($encoded_edition_name) {
        return base_url("event/" . $encoded_edition_name);
    }

    function encode_edition_name($edition_name) {
//        return urlencode(str_replace(" ","-",(str_replace("'","",str_replace("/"," ",$edition_name)))));
        return urlencode(str_replace(" ", "-", (str_replace("-", "--", ($edition_name)))));
    }

    function get_edition_name_from_url($encoded_edition_name) {
        $name = str_replace("--", "^", $encoded_edition_name);
        $name = str_replace("-", " ", urldecode($name));
        $name = str_replace("^", "-", $name);
        $name = str_replace("  ", "-", urldecode($name));
        return $name;
    }

    function get_url_from_parkrun_name($encoded_parkrun_name) {
        return base_url("parkrun/" . $encoded_parkrun_name);
    }

    function encode_parkrun_name($parkrun_name) {
        return urlencode(str_replace(" ", "-", (str_replace("'", "", str_replace("/", " ", $parkrun_name)))));
    }

    function my_encrypt($string) {
        if (is_int($string)) {
            return urlencode(base64_encode($string + 7936181));
        } else {
            return urlencode(base64_encode($string . "7936181"));
        }
    }

    function my_decrypt($decrypt) {
        $string = base64_decode(urldecode($decrypt));
        if (is_int($string)) {
            return $string - 7936181;
        } else {
            return substr($string, 0, -7);
        }
    }

    function move_to_top(&$array, $key) {
        $temp = array($key => $array[$key]);
        unset($array[$key]);
        $array = $temp + $array;
    }

    function move_to_bottom(&$array, $key) {
        $value = $array[$key];
        unset($array[$key]);
        $array[$key] = $value;
    }

    function time_is_midnight($date) {
        $time = date("H:i", strtotime($date));
        if ($time == "00:00") {
            return true;
        } else {
            return false;
        }
    }

}