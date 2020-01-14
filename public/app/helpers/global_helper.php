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
        return base64_encode($string + 7936181);
    } else {
        return base64_encode($string . "7936181");
    }
}

function my_decrypt($decrypt) {
    $string = base64_decode($decrypt);
    if (is_int($string)) {
        return $string - 7936181;
    } else {
        return substr($string, 0, -7);
    }
}

function array_keys_exists(array $keys, array $arr) {
    return !array_diff_key(array_flip($keys), $arr);
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
