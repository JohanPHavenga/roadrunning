<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function wts($seq = '', $die = false) {
    echo "<pre>";
    print_r($seq);
    echo "</pre>";
    if ($die) {
        die();
    }
}

function encode_edition_name($edition_name) {
    return urlencode(str_replace(" ", "-", (str_replace("-", "--", ($edition_name)))));
}

function get_edition_name_from_url($encoded_edition_name) {
    return str_replace("  ", "-", str_replace("^", "-", str_replace("-", " ", str_replace("--", "^", urldecode($encoded_edition_name)))));
}

function get_url_from_parkrun_name($encoded_parkrun_name) {
    return base_url("parkrun/" . $encoded_parkrun_name);
}

function encode_parkrun_name($parkrun_name) {
    return urlencode(str_replace(" ", "-", (str_replace("'", "", str_replace("/", " ", $parkrun_name)))));
}

function hash_pass($password) {
    if ($password) {
        $options = [
            'cost' => 13,
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
//        return sha1($password . "37");
    } else {
        return NULL;
    }
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

function array_keys_exists(array $keys, array $arr) {
    return !array_diff_key(array_flip($keys), $arr);
}

function replace_key($arr, $oldkey, $newkey) {
    if (array_key_exists($oldkey, $arr)) {
        $keys = array_keys($arr);
        $keys[array_search($oldkey, $keys)] = $newkey;
        return array_combine($keys, $arr);
    }
    return $arr;
}

function time_to_sec($time) {
    if ($time) {
        $sec = 0;
        foreach (array_reverse(explode(':', $time)) as $k => $v) {
            $sec += pow(60, $k) * $v;
        }
        return $sec;
    } else {
        return false;
    }
}

function time_is_midnight($date) {
    $time = date("H:i", strtotime($date));
    if ($time == "00:00") {
        return true;
    } else {
        return false;
    }
}

function time_is_almost_midnight($date) {
    $time = date("H:i", strtotime($date));
    if (($time == "23:55") || ($time == "23:59")) {
        return true;
    } else {
        return false;
    }
}

function convert_seconds($seconds) {
    $dt1 = new DateTime("@0");
    $dt2 = new DateTime("@$seconds");
//    return $dt1->diff($dt2)->format('%a days, %h hours, %i minutes and %s seconds');
    return $dt1->diff($dt2)->format('%a');
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
