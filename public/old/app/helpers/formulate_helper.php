<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
// Formulate Number with 2 dessimals
// ================================================================
if (!function_exists('fnumber')) {

    function fnumber($num, $des = 0) {
        return rtrim(rtrim(number_format($num, $des, ".", ""), '0'), '.');
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

    function fdateHumanFull($date, $show_dotw = false, $inc_time = false) {
        if ($date > 0) {
            if ($show_dotw) {
                if ($inc_time) {
                    return date("l, j F Y H\hi", strtotime($date));
                } else {
                    return date("l, j F Y", strtotime($date));
                }
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
        if (!is_int($date)) {
            $date = strtotime($date);
        }
        if ($date) {
            if ($show_sec) {
                return date("Y-m-d H:i:s", $date);
            } else {
                return date("Y-m-d H:i", $date);
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

if (!function_exists('fdateMonth')) {

    function fdateMonth($date) {
        if ($date) {
            return date("F", strtotime($date));
        } else {
            return false;
        }
    }

}

if (!function_exists('ftimeSort')) {

    function ftimeSort($time, $show_sec = false) {
        if ($time) {            
            if ($show_sec) {
                return date("H:i:s", strtotime($time));
            } else {
                return date("H:i", strtotime($time));
            }
        } else {
            return 0;
        }
    }

}

if (!function_exists('ftimeMil')) {

    function ftimeMil($time) {
        if ($time) {
            return date("H\hi", strtotime($time));
        } else {
            return 0;
        }
    }

}

if (!function_exists('fdateToCal')) {

    function fdateToCal($timestamp) {
        if ($timestamp) {
            return date('Ymd\THis', $timestamp);
//            return date('Ymd\THis\Z', $timestamp-(2*60*60)); // google does not like the Z
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


//                    $sEvent[]='"startDate": "'.$race_start_date.'T'.$race['race_time_start'].'+02:00",';
// ================================================================
// Formulate Lables
// ================================================================
if (!function_exists('flable')) {

    function flable($text = "", $status = "default", $size = NULL) {
        if ($size) {
            $l_size = "label-" . $size;
        } else {
            $l_size = NULL;
        }
        return "<span class='label $l_size label-$status'> $text </span>";
    }

}


if (!function_exists('flableStatus')) {

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

}



// ================================================================
// Formulate Buttons
// ================================================================
if (!function_exists('fbutton')) {

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

}

if (!function_exists('fbuttonSave')) {

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

}

if (!function_exists('fbuttonLink')) {

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

}


if (!function_exists('fbuttonLinkGroup')) {

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

}


if (!function_exists('fbuttonActionGroup')) {

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

}

if (!function_exists('fLink')) {

    function fLink($url, $text) {
        return "<a href='$url'>$text</a>";
    }

}

// ====================================s============================
// Formulate Tables
// ================================================================

if (!function_exists('ftable')) {

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

}


if (!function_exists('ftableHeading')) {

    function ftableHeading($heading_list, $blank_columns = 0) {
        foreach ($heading_list as $heading):
            $fheading_list[] = str_replace("Id", "ID", str_replace("_", "&nbsp;", ucwords($heading, "_")));
        endforeach;
        $return['heading'] = $fheading_list;

        for ($n = 0; $n < $blank_columns; $n++) {
            $return['heading'][] = "";
        }
        return $return['heading'];
    }

}


// ================================================================
// Formulate Pagenation
// ================================================================
if (!function_exists('fpagination')) {

    function fpagination($p) {
        return $p;
    }

}

if (!function_exists('fpaginationConfig')) {

    function fpaginationConfig($url, $per_page, $total, $uri_segment) {
        $config["base_url"] = $url;
        $config["total_rows"] = $total;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = $uri_segment;

        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';

        $config['next_link'] = "Next &rsaquo;";
        $config['prev_link'] = "&lsaquo; Previous";

        $open_tag_list = ['first_tag_open', 'next_tag_open', 'prev_tag_open', 'last_tag_open', 'num_tag_open'];
        foreach ($open_tag_list as $tag):
            $config[$tag] = '<li>';
        endforeach;

        $open_tag_list = ['first_tag_close', 'next_tag_close', 'prev_tag_close', 'last_tag_close', 'num_tag_close'];
        foreach ($open_tag_list as $tag):
            $config[$tag] = '</li>';
        endforeach;

        $config['cur_tag_open'] = "<li><a><b>";
        $config['cur_tag_close'] = "</b></a></li>";
        return $config;
    }

}