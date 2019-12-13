<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function fyesNo($bool) {
    if ($bool) {
        return "Yes";
    } else {
        return "No";
    }
}

function fraceDistance($distance) {
    return floatval($distance) . "km";
}

function fdisplayCurrency($amount, $des = 0) {
    return "R" . number_format($amount, $des, '.', '');
}

function int_phone($phone) {
    $phone = trim($phone);
    $phone = str_replace(" ", "", $phone);
    $phone = str_replace("-", "", $phone);
    return preg_replace('/^(?:\+?27|0)?/', '+27', $phone);
}

function fphone($phone) {
    $phone = int_phone($phone);

    $int_code = substr($phone, 0, 3);
    $code = substr($phone, 3, 2);
    $first_3 = substr($phone, 5, 3);
    $last_4 = substr($phone, 8, 4);

    $new_phone = $int_code . " " . $code . " " . $first_3 . " " . $last_4;

    return $new_phone;
}

// =================================
// DATES
// =================================

function fdateDay($date) {
    return date("d", strtotime($date));
}

function fdateShort($date) {
    return date("Y-m-d", strtotime($date));
}

function fdateHuman($date) {

    return date("D j M", strtotime($date));
}

function fdateHumanFull($date, $show_dotw = FALSE) {
    if ($show_dotw) {
        return date("l, j F Y", strtotime($date));
    } else {
        return date("j F Y", strtotime($date));
    }
}

function fdateLong($date, $show_sec = TRUE) {
    if ($show_sec) {
        return date("Y-m-d H:i:s", strtotime($date));
    } else {
        return date("Y-m-d H:i", strtotime($date));
    }
}

function fdateYear($date) {
    return date("Y", strtotime($date));
}

function ftimeSort($time) {
    return date("H:i", strtotime($time));
}

function ftimeMil($time) {
    return date("H\hi", strtotime($time));
}

function fdateToCal($timestamp) {
    return date('Ymd\THis', $timestamp);
}

function fdateStructured($timestamp) {
    return date('Y-m-d\TH:i:s' . '+02:00', strtotime($timestamp));
}

function fdateTitle($date) {
    return date("D, d M", strtotime($date));
}
