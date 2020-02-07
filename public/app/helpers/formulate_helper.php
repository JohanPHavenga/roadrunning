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

function fdateMonth($date) {
    if ($date) {
        return date("F", strtotime($date));
    } else {
        return false;
    }
}

function fdateDay($date) {
    return date("d", strtotime($date));
}

function fdateShort($date) {
    return date("Y-m-d", strtotime($date));
}

function fdateHuman($date) {

    return date("D j M", strtotime($date));
}

function fdateHumanFull($date, $show_dotw = false, $show_time = false) {
    $date_str = "j F Y";
    if ($show_dotw) {
        $date_str = "l, " . $date_str;
    }
    if ($show_time) {
        if (!time_is_midnight($date)) {
            $date_str .= " H:i";
        }
    }
    return date($date_str, strtotime($date));
}

function fdateEntries($date) {
    $post_text = '';
    $date_str = "l, j F";
    if (time_is_almost_midnight($date)) {
        $post_text = " @ midnight";
    } elseif (!time_is_midnight($date)) {
        $date_str .= " @ H\hi";
    }
    return date($date_str, strtotime($date)) . $post_text;
}

function fdateLong($date, $show_sec = true) {
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

// =======================================
// ADDED FOR ADMIN
// =======================================

function ftable($id = "", $is_newsletter = false) {
    $template = array('table_open' => '<table class="table table-striped table-bordered table-hover order-column" id="' . $id . '">');
    if ($is_newsletter) {
        $template = array(
            'table_open' => '<table border="0" cellpadding="4" cellspacing="0" style="border: 1px solid #e7ecf1;width: 100%;">',
            'cell_start' => '<td style="font-size: 15px;  line-height: 20px; border: 1px solid #e7ecf1;background-color: #fbfcfd;font-family: arial, sans-serif; font-size: 14px;">',
            'cell_alt_start' => '<td style="font-size: 15px;  line-height: 20px; border: 1px solid #e7ecf1; font-family: arial, sans-serif; font-size: 14px;" >',
        );
    }
    return $template;
}

function flable($text = "", $status = "default", $size = NULL) {
    if ($size) {
        $l_size = "label-" . $size;
    } else {
        $l_size = NULL;
    }
    return "<span class='label $l_size label-$status'> $text </span>";
}

function flableStatus($status_num) {
    switch ($status_num) {
        case 1:
            $text = "Active";
            $status = "success";
            break;
        case 2:
            $text = "Not Active";
            $status = "danger";
            break;
        case 3:
            $text = "Cancelled";
            $status = "warning";
            break;
        case 4:
            $text = "Draft";
            $status = "warning";
            break;
        case 8:
            $text = "Completed";
            $status = "success";
            break;
        case 9:
            $text = "Postponed";
            $status = "warning";
            break;
        default:
            $text = "No Status";
            $status = "warning";
            break;
    }

    return flable($text, $status, "sm");
}

function fLink($url, $text) {
    return "<a href='$url'>$text</a>";
}

function fbutton($text = "Submit", $type = "submit", $status = "default", $size = NULL, $name = "", $value = "") {
    // status: default|primary|success|warning|danger|link
    // size: lg|sm|xs

    if ($size) {
        $btn_size = "btn-" . $size;
    } else {
        $btn_size = NULL;
    }
    return "<button type='$type' name='$name' class='btn btn-$status $btn_size' value='$value'>$text</button>";
}

function fbuttonSave($params) {
    // status: default|primary|success|warning|danger|link
    // size: lg|sm|xs

    $btn_size = NULL;
    if (isset($params['size'])) {
        $btn_size = "btn-" . $params['size'];
    }
    $status = "default";
    if (isset($params['status'])) {
        $status = $params['status'];
    }
    $value = "";
    if (isset($params['value'])) {
        $value = $params['value'];
    }
    $type = "submit";
    if (isset($params['type'])) {
        $type = $params['type'];
    }
    $dt = "";
    if (isset($params['data-toggle'])) {
        $dt = "data-toggle='" . $params['data-toggle'] . "' data-target='" . $params['data-target'] . "'";
    }

    return "<button type='$type' name='save-btn' class='btn btn-$status $btn_size' value='$value' $dt>" . @$params['text'] . "</button>";
}

function fbuttonLinkGroup($action_array) {
    $html = "<div class='btn-group'>";
    foreach ($action_array as $text => $link) {
        if ($text == "Delete") {
            $btn_color = "danger";
        } else {
            $btn_color = "default";
        }
        $html .= "<a href='$link' class='btn btn-$btn_color btn-xs' role='button'>$text</a>";
    }
    $html .= "</div>";

    return $html;
}

function fbuttonLink($url, $text, $status = "default", $size = NULL, $extra = NULL) {
    // status: default|primary|success|warning|danger|link
    // size: lg|sm|xs
    if ($size) {
        $btn_size = "btn-" . $size;
    } else {
        $btn_size = NULL;
    }
    return "<a href='$url' class='btn btn-$status $btn_size' role='button' $extra>$text</a>";
}

function fbuttonActionGroup2($action_array) {
    $html = "<div class='btn-group'>";
    foreach ($action_array as $action_item) {
        // confirmation
        if (isset($action_item['confirmation_text'])) {
            $confirm = "data-toggle='confirmation' data-original-title='" . $action_item['confirmation_text'] . "'";
        } else {
            $confirm = "";
        }
        if ($action_item['text'] == "Delete") {
            $btn_color = "danger";
        } else {
            $btn_color = "default";
        }
        $html .= "<a href='" . $action_item['url'] . "' class='btn btn-$btn_color btn-xs' role='button' $confirm>" . $action_item['text'] . " </a>";
    }
    $html .= "</div>";

    return $html;
}

function fbuttonActionGroup($action_array) {
    $html = "<div class='btn-group'>";
    $html .= "<button class='btn btn-xs default dropdown-toggle' type='button' data-toggle='dropdown' aria-expanded='false'> Actions <i class='fa fa-angle-down'></i></button>";
    $html .= "<ul class='dropdown-menu pull-right' role='menu'>";
    foreach ($action_array as $action_item) {
        // confirmation
        if (isset($action_item['confirmation_text'])) {
            $confirm = "data-toggle='confirmation' data-original-title='" . $action_item['confirmation_text'] . "'";
        } else {
            $confirm = "";
        }
        $html .= "<li><a href='" . $action_item['url'] . "' $confirm><i class='" . $action_item['icon'] . "'></i> " . $action_item['text'] . " </a>";
    }
    $html .= "</ul></div>";

    return $html;
}
