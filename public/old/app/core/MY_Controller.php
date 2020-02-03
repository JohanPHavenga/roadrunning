<?php

//=======================================
// Central Controller
//=======================================
class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->session->set_flashdata(['previous_url' => current_url(),]);
        $this->data_to_view['rr_cookie']['sub_email'] = get_cookie("sub_email");
        $this->data_to_view['rr_cookie']['no_new_site'] = $this->data_to_footer['rr_cookie']['no_new_site'] = get_cookie("no_new_site");
    }

    public function get_race_name_from_status($race_name, $race_distance, $racetype_name, $race_status) {
        // set return as race_name
        $return_name = $race_name;
        // check for empty
        if (empty($return_name)) {
            switch (true) {
                case $race_distance > 42.2:
                    $return_name = "Ultra Marathon";
                    break;
                case $race_distance == 42.2:
                    $return_name = "Marathon";
                    break;
                case $race_distance == 21.1:
                    $return_name = "Half-Marathon";
                    break;
                case $race_distance < 10:
                    if (strpos($racetype_name, 'Run') !== false) {
                        $return_name = fraceDistance($race_distance) . " Fun Run";
                    } else {
                        $return_name = fraceDistance($race_distance) . " " . $racetype_name;
                    }
                    break;
                default:
                    $return_name = fraceDistance($race_distance) . " " . $racetype_name;
                    break;
            }
        }
        switch ($race_status) {
            case 2:
                $return_name = $return_name . " - DRAFT";
                break;
            case 3:
                $return_name = $return_name . " - CANCELLED";
                break;
        }
        return $return_name;
    }

    public function chronologise_data($data_arr, $date_field) {
        $return_data = [];
        foreach ($data_arr as $id => $row) {
            $year = date("Y", strtotime($row[$date_field]));
            $month = date("F", strtotime($row[$date_field]));
            $day = date("d", strtotime($row[$date_field]));

            $return_data[$year][$month][$day][$id] = $row;
        }
        return $return_data;
    }

    public function set_email_body($body, $unsub=null) {
        $year = date("Y");
        $html = <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv = "Content-Type" content = "text/html; charset=utf-8" />
<meta name = "viewport" content = "width=device-width, initial-scale=1.0"/>
<style>a, a[x-apple-data-detectors] { color: #26B8F3!important;
text-decoration: underline!important;
font-family:inherit!important;
font-size:inherit!important;
font-weight:inherit!important;
line-height:inherit!important;
}</style>
</head>
<body style = "background-color:#FFFFFF;margin:0;padding:0;">
<table cellpadding = "0" cellspacing = "0" border = "0" width = "100%" bgcolor = "#FFFFFF" style = "margin:0;"><tbody><tr><td style = "padding:0;" align = "center">
<table cellpadding = "0" cellspacing = "0" border = "0" align = "center" bgcolor = "#FFFFFF" style = "margin:0; max-width: 600px; width: 100%;"><tbody><tr><td style = "padding:0 20px;" align = "left">
<div style = "margin:0 0 25px 0;"><img alt = "RoadRunningZA" width = "72" height = "72" src = "https://www.roadrunning.co.za/img/favicon/android-icon-72x72.png" /></div>

<div style = "color:#000000;font-family:Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:16px;line-height:25.6px;text-align:left;">
$body
</div>

<div style = "border-top: 2px solid #E5E5E5;color:#111111;font-family:Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:11px;line-height:14px;margin:30px 0;">
<div style = "margin:20px 0;"><a href='https://www.roadrunning.co.za/' title='Go to RoadRunningZA'><img alt = "RoadRunningZA" width = "110" height = "22" src = "https://www.roadrunning.co.za/img/logo-vec-22.png" /></a></div>
<div style = "margin:20px 0;" > Copyright &copy;
$year RoadRunningZA. All rights reserved.<br>
$unsub
</div>
</div>
</td></tr></tbody></table>
</td></tr></tbody></table>
</body>
</html>
EOT;
        return $html;
    }

}

//=======================================
// Default Back-end Controller
//=======================================
class Admin_Controller extends MY_Controller {

    public $data_to_header = [];
    public $data_to_view = [];
    public $data_to_footer = [];
    public $view_url = "/admin/list";
    public $header_url = "/templates/admin/header";
    public $footer_url = "/templates/admin/footer";
    public $profile_url = "/admin/dashboard/profile";
    public $logout_url = "/login/logout";
    public $upload_path = "./uploads/admin/";

//    public $crumbs=[];

    function __construct() {
        parent::__construct();
        // Check login, load back end dependencies
        if (!$this->session->has_userdata('admin_logged_in')) {
            $this->session->set_flashdata([
                'alert' => "You are not logged in as an Admin. Please log in to continue.",
                'status' => "danger",
            ]);
            redirect('/login/admin', 'refresh');
            exit();
        }

        // setup auto crumbs from URI
        $segs = $this->uri->segment_array();
        $crumb_uri = substr(base_url(), 0, -1);
        $total_segments = $this->uri->total_segments();
        for ($x = 1; $x <= $total_segments; $x++) {

            if (($x == $total_segments) || ($x == 3)) {
                $crumb_uri = "";
            } else {
                $crumb_uri .= "/" . $segs[$x];
            }

            if ($segs[$x] == "admin") {
                $segs[$x] = "home";
            }
            if ($segs[$x] == "dashboard") {
                continue;
            }
            if ($segs[$x] == "delete") {
                $this->data_to_header['crumbs'] = [];
                break;
            }

            $segs[$x] = str_replace("_", " ", $segs[$x]);
            $this->data_to_header['crumbs'][ucwords($segs[$x])] = $crumb_uri;

            if ($x == 3) {
                break;
            }
        }

        $this->data_to_header['menu_array'] = $this->set_admin_menu_array();
    }

    function url_disect() {
        $url_info = [];
        $url_info["base_url"] = base_url();
        $url_info["url_string"] = uri_string();
        $url_info["url_string_arr"] = explode("/", uri_string());

        return $url_info;
    }

    function csv_handler($file_path) {
        $csv = array_map('str_getcsv', file($file_path));
        array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);
        return $csv;
    }

    function csv_flat_table_import($file_data) {
        foreach ($file_data as $entity) {
            //reset($entity);

            $id = array_shift($entity);
            foreach ($entity as $key => $value) {
                if (!empty($value)) {
                    $user_data[$key] = $value;
                }
            }
            // get ID - set action
            if ($id > 0) {
                $action = "edit";
            } else {
                $action = "add";
                $id = 0;
                if (isset($sum_data[$action])) {
                    $id = max(array_keys($sum_data[$action])) + 1;
                }
            }

            $sum_data[$action][$id] = $user_data;
            unset($user_data);
        }

        return $sum_data;
    }

    //CHECK AND CREATE UPLOAD FOLDER
    public function check_upload_folder($linked_to, $id) {
        $upload_path = "./uploads/" . $linked_to . "/" . $id;
        if (!file_exists($upload_path)) {
            if (!mkdir($upload_path, 0777, true)) {
                return false;
            }
        }
        return $upload_path;
    }

    public function set_results_flag($linked_to, $id) {
        $this->load->model('url_model');
        $this->load->model('file_model');
        $this->load->model('race_model');

        // chcek if there is a results URL
        $has_results_url = $this->url_model->check_urltype_exists($linked_to, $id, 4);
        $has_results_file = $this->file_model->check_filetype_exists($linked_to, $id, 4);

        //this method is in MY_MODEL
        if ($has_results_url || $has_results_file) {
            $flag = true;
        } else {
            $flag = false;
        }
        // get edition info if the results is on race level
        if ($linked_to == "race") {
            $id = $this->race_model->get_edition_id($id);
            $linked_to = "edition";
        }
        // set the flag
        $set = $this->url_model->set_results_flag($linked_to, $id, $flag);
    }

    function set_admin_menu_array() {
        return [
            // Dashboard
            [
                "text" => "Dashboard",
                "url" => 'admin',
                "icon" => "home",
                "seg0" => ['dashboard'],
                "submenu" => [
                    [
                        "text" => "Dashboard",
                        "url" => 'admin/dashboard',
                        "icon" => "bar-chart",
                    ],
                    [
                        "text" => "Audit",
                        "url" => 'admin/dashboard/audit',
                        "icon" => "bulb",
                    ],
                    [
                        "text" => "Search",
                        "url" => 'admin/dashboard/search',
                        "icon" => "magnifier",
                    ],
                    [
                        "text" => "Export Events",
                        "url" => 'admin/event/export',
                        "icon" => "arrow-down",
                    ]
                ],
            ],
            // Events
            [
                "text" => "Events Info",
                "url" => 'admin/event',
                "icon" => "calendar",
                "seg0" => ['event', 'edition', 'race'],
                "submenu" => [
                    [
                        "text" => "Events",
                        "url" => 'admin/event',
                        "icon" => "rocket",
                    ],
                    [
                        "text" => "Editions",
                        "url" => 'admin/edition',
                        "icon" => "calendar",
                    ],
                    [
                        "text" => "Races",
                        "url" => 'admin/race',
                        "icon" => "speedometer",
                    ],
                ],
            ],
            // Other info
            [
                "text" => "Other Info",
                "url" => 'admin/town/search',
                "icon" => "settings",
                "seg0" => ['town', 'file', 'url', 'quote'],
                "submenu" => [
                    [
                        "text" => "Towns",
                        "url" => 'admin/town',
                        "icon" => "home",
                    ],
                    [
                        "text" => "Files",
                        "url" => 'admin/file',
                        "icon" => "folder-alt",
                    ],
                    [
                        "text" => "URLs",
                        "url" => 'admin/url',
                        "icon" => "link",
                    ],
                    [
                        "text" => "Venues",
                        "url" => 'admin/venue',
                        "icon" => "pin",
                    ],
                    [
                        "text" => "Tags",
                        "url" => 'admin/tag',
                        "icon" => "tag",
                    ],
                    [
                        "text" => "Dates",
                        "url" => 'admin/date',
                        "icon" => "calendar",
                    ],
                    [
                        "text" => "Quotes",
                        "url" => 'admin/quote',
                        "icon" => "speech",
                    ],
                ],
            ],
            // Mail Queue
            [
                "text" => "Email Module",
                "url" => 'admin/emailque/view/4',
                "icon" => "envelope",
                "seg0" => ['emailque', 'emailmerge', 'emailtemplate'],
                "submenu" => [
                    [
                        "text" => "Email Merges",
                        "url" => 'admin/emailmerge',
                        "icon" => "envelope-open",
                    ],
                    [
                        "text" => "Email Templates",
                        "url" => 'admin/emailtemplate',
                        "icon" => "envelope-letter",
                    ],
                    [
                        "text" => "Drafts",
                        "url" => 'admin/emailque/view/4',
                        "icon" => "pencil",
                    ],
                    [
                        "text" => "Pending",
                        "url" => 'admin/emailque/view/5',
                        "icon" => "login",
                    ],
                    [
                        "text" => "Sent",
                        "url" => 'admin/emailque/view/6',
                        "icon" => "like",
                    ],
                    [
                        "text" => "Failed",
                        "url" => 'admin/emailque/view/7',
                        "icon" => "dislike",
                    ],
                ],
            ],
            // STATIS INFO
            [
                "text" => "Static",
                "url" => '',
                "icon" => "puzzle",
                "seg0" => ['asamember', 'role', 'racetype', 'filetype', 'urltype', 'area', 'province'],
                "submenu" => [
                    [
                        "text" => "ASA Members",
                        "url" => 'admin/asamember',
                        "icon" => "umbrella",
                    ],
                    [
                        "text" => "ASA Regulations",
                        "url" => 'admin/asareg',
                        "icon" => "notebook",
                    ],
                    [
                        "text" => "ASA Licence Fees",
                        "url" => 'admin/asafee',
                        "icon" => "credit-card",
                    ],
                    [
                        "text" => "Roles",
                        "url" => 'admin/role',
                        "icon" => "user",
                    ],
                    [
                        "text" => "Entry Types",
                        "url" => 'admin/entrytype',
                        "icon" => "flag",
                    ],
                    [
                        "text" => "Registration Types",
                        "url" => 'admin/regtype',
                        "icon" => "bell",
                    ],
                    [
                        "text" => "Date Types",
                        "url" => 'admin/datetype',
                        "icon" => "calendar",
                    ],
                    [
                        "text" => "Race Types",
                        "url" => 'admin/racetype',
                        "icon" => "compass",
                    ],
                    [
                        "text" => "File Types",
                        "url" => 'admin/filetype',
                        "icon" => "folder",
                    ],
                    [
                        "text" => "URL Types",
                        "url" => 'admin/urltype',
                        "icon" => "link",
                    ],
                    [
                        "text" => "Tag Types",
                        "url" => 'admin/tagtype',
                        "icon" => "tag",
                    ],
                    [
                        "text" => "Areas",
                        "url" => 'admin/area',
                        "icon" => "map",
                    ],
                    [
                        "text" => "Regions",
                        "url" => 'admin/region',
                        "icon" => "map",
                    ],
                    [
                        "text" => "Provinces",
                        "url" => 'admin/province',
                        "icon" => "globe-alt",
                    ],
                ],
            ],
            // Users
            [
                "text" => "Users",
                "url" => 'admin/user',
                "icon" => "users",
                "seg0" => ['user', 'usersubscription'],
                "submenu" => [
                    [
                        "text" => "List users",
                        "url" => 'admin/user',
                        "icon" => "users",
                    ],
                    [
                        "text" => "User Subscriptions",
                        "url" => 'admin/usersubscription',
                        "icon" => "present",
                    ],
                ],
            ],
            // Results
            [
                "text" => "Results",
                "url" => 'admin/result',
                "icon" => "trophy",
                "seg0" => ['result'],
                "submenu" => [
                    [
                        "text" => "List Results",
                        "url" => 'admin/result/view',
                        "icon" => "list",
                    ],
                    [
                        "text" => "Import Result Set",
                        "url" => 'admin/result/import',
                        "icon" => "login",
                    ],
                ],
            ],
            // Clubs
            [
                "text" => "Clubs",
                "url" => 'admin/club',
                "icon" => "badge",
                "seg0" => ['club'],
            ],
            // Sponsors
            [
                "text" => "Sponsors",
                "url" => 'admin/sponsor',
                "icon" => "wallet",
                "seg0" => ['sponsor'],
            ],
            // Parkruns
            [
                "text" => "Parkruns",
                "url" => 'admin/parkrun',
                "icon" => "direction",
                "seg0" => ['parkrun'],
            ],
        ];
    }

    function get_event_field_list() {
        return ['event_id', 'event_name', 'town_id'];
    }

    function get_edition_field_list() {
        return ['edition_id', 'edition_name', 'edition_date', 'latitude_num', 'longitude_num', 'edition_url', 'edition_address'];
    }

    function get_race_field_list() {
        return ['race_id', 'race_name', 'race_distance', 'race_time_start', 'racetype_id'];
    }

    function get_contact_field_list() {
        return ['user_id', 'user_name', 'user_surname', 'user_email'];
    }

    function get_asa_member_field_list() {
        return ['asa_member_id'];
    }

    public function race_fill_blanks($race_data, $edition_info) {
        $this->load->model('asareg_model');
        $this->load->model('asafee_model');
        $this->load->model('racetype_model');

        // check for emtpy race_name 
        if (empty($race_data['race_name'])) {
            if (!isset($race_data['racetype_name'])) {
                $racetype_data = $this->racetype_model->get_racetype_detail($race_data['racetype_id']);
                $racetype_name = $racetype_data['racetype_name'];
            } else {
                $racetype_name = $race_data['racetype_name'];
            }
            $race_data['race_name'] = $this->get_race_name_from_status($race_data['race_name'], $race_data['race_distance'], $racetype_name, $race_data['race_status']);
        }
        // check for empty minimum age
        if (empty($race_data['race_minimum_age'])) {
            // get asa_reg_id
            $asareg_id = $this->asareg_model->get_asareg_id_from_distance($race_data['race_distance']);
            // get asa_reg list
            $asareg_list = $this->asareg_model->get_asareg_list();
            $race_data['race_minimum_age'] = $asareg_list[$asareg_id]['asa_reg_minimum_age'];
        }

        // check senior fees
        if (($race_data['race_fee_senior_licenced'] > 0) && ($race_data['race_fee_senior_unlicenced'] == 0)) {

            $licence_fee = $this->asafee_model->get_asafee_from_distance($edition_info['edition_asa_member'], fdateYear($edition_info['edition_date']), $race_data['race_distance']);
            if ($licence_fee > 0) {
                $race_data['race_fee_senior_unlicenced'] = $race_data['race_fee_senior_licenced'] + $licence_fee;
            }
        }

        // check junior fees
        if (($race_data['race_fee_junior_licenced'] > 0) && ($race_data['race_fee_junior_unlicenced'] == 0)) {
            $licence_fee = $this->asafee_model->get_asafee_from_distance($edition_info['edition_asa_member'], fdateYear($edition_info['edition_date']), $race_data['race_distance'], "asa_fee_jnr");
            if ($licence_fee > 0) {
                $race_data['race_fee_junior_unlicenced'] = $race_data['race_fee_junior_licenced'] + $licence_fee;
            }
        }

        return $race_data;
    }

}

//=======================================
// Default Front-end Controller
//=======================================

class Frontend_Controller extends MY_Controller {

    public $data_to_header = ["section" => ""];
    public $data_to_view = [];
    public $data_to_footer = ["admin_login" => "/login/admin"];
    public $header_url = 'templates/header';
    public $footer_url = 'templates/footer';
    public $crumbs_arr = [];
    public $ini_array = [];

    function __construct() {
        parent::__construct();

        if (!isset($_SESSION['area_list'])) {
            $_SESSION['area_list'] = $this->get_area_list();
        }

        // Load shared resources here or in autoload.php
        $this->crumbs_arr = $this->set_crumbs();
        $this->data_to_header["title_bar"] = $this->render_topbar_html(["crumbs" => $this->crumbs_arr]);
        $this->data_to_header["menu_array"] = $this->set_top_menu_array();
        $this->data_to_footer["area_list"] = $_SESSION['area_list'];
        $this->data_to_footer["date_list"] = $this->get_date_list();
        $this->data_to_footer["uri_string"] = uri_string();

//        wts($this->data_to_footer["uri_string"],1);

        $this->ini_array = parse_ini_file("server_config.ini", true);
        // history
        $this->check_history();
    }

    public function show_my_404($msg, $status) {
        //Using 'location' not work well on some windows systems
        $this->session->set_flashdata([
            'alert' => $msg,
            'status' => $status,
        ]);
        redirect('404');
    }

    public function get_date_list() {
        $dates_to_fetch = [
//            "2 months ago",
            "1 month ago",
            "today",
            "+1 month",
            "+2 month",
            "+3 month",
            "+4 month",
            "+5 month",
//            "+6 month",
        ];
        foreach ($dates_to_fetch as $strtotime) {
            $date_list[date("Y", strtotime($strtotime))][date("m", strtotime($strtotime))] = date("F Y", strtotime($strtotime));
        }
        return $date_list;
    }

    public function set_top_menu_array() {
        return [
            // Dashboard
            [
                "text" => "Home",
                "url" => base_url(),
                "section" => 'home',
            ],
            // Events
            [
                "text" => "Events",
                "url" => base_url('/calendar'),
                "section" => 'events',
            ],
            // Results
            [
                "text" => "Results",
                "url" => base_url('/calendar/results'),
                "section" => 'results',
            ],
            // Parkruns
            [
                "text" => "Parkrun",
                "url" => base_url('/parkrun/calendar'),
                "section" => 'parkrun',
            ],
            // FAQ
            [
                "text" => "FAQ",
                "url" => base_url('/faq'),
                "section" => 'faq',
            ],
            // Newletter
            [
                "text" => "Newsletter",
                "url" => base_url('/newsletter'),
                "section" => 'newsletter',
            ],
            // Contact
            [
                "text" => "Contact Us",
                "url" => base_url('/contact'),
                "section" => 'contact',
            ],
        ];
    }

    public function set_crumbs() {
        // setup auto crumbs from URI
        $segs = $this->uri->segment_array();
        $crumb_uri = substr(base_url(), 0, -1);
        $total_segments = $this->uri->total_segments();
        $crumbs['Home'] = base_url();
        for ($x = 1; $x <= $total_segments; $x++) {

            if (($x == $total_segments) || ($x == 3)) {
                $crumb_uri = "";
            } else {
                $crumb_uri .= "/" . $segs[$x];
            }

            // make controller prural for event and overwrite URI
            if (($x == 1) && ($segs[$x] == "event")) {
                $segs[$x] = $segs[$x] . "s";
                $crumb_uri = "/calendar";
            }

            $segs[$x] = str_replace("_", " ", $segs[$x]);
            $crumbs[ucwords($segs[$x])] = $crumb_uri;

            if ($x == 3) {
                break;
            }
        }

        return array_reverse($crumbs);
    }

    public function render_crumbs($crumb_arr) {
        // crumbs
        $return_html = '<ul class="c-page-breadcrumbs c-theme-nav c-pull-right c-fonts-regular">';
        foreach ($crumb_arr as $display => $url) {
            $return_html .= '<li><a href="' . $url . '">' . urldecode($display) . '</a></li>';
            if ($display != "Home") {
                $return_html .= "<li>/</li>";
            }
        }
        $return_html .= '</ul>';

        return $return_html;
    }

    public function render_topbar_html($params) {
        if (isset($params['sub_title'])) {
            $return_html = '<div class="c-layout-breadcrumbs-1 c-subtitle c-fonts-uppercase c-fonts-bold">';
        } else {
            $return_html = '<div class="c-layout-breadcrumbs-1 c-fonts-uppercase c-fonts-bold">';
        }
        $return_html .= '<div class="container">';

        // heading
        $return_html .= '<div class="c-page-title c-pull-left">';
        if (isset($params['title'])) {
            $return_html .= '<h3 class="c-font-uppercase c-font-sbold">' . $params['title'] . '</h3>';
        }
        if (isset($params['sub_title'])) {
            $return_html .= '<h4 class="">' . $params['sub_title'] . '</h4>';
        }
        $return_html .= '</div>';

        $return_html .= $this->render_crumbs($params['crumbs']);

        $return_html .= '</div>';
        $return_html .= '</div>';

        return $return_html;
    }

    public function get_bullet_color($params) {

        $return = [];

        switch ($params['edition_status']) {
            case 3:
                $return['color'] = "c-font-grey-2";
                $return['text'] = "Event cancelled";
                break;
            case 9:
                $return['color'] = "c-font-yellow-2";
                $return['text'] = "Event Postponed";
                break;
        }
        if (!empty($return)) {
            return $return;
        }

        switch ($params['info_status']) {
            case 14:
                $return['color'] = "c-font-red-1";
                $return['text'] = "Basic information confirmed. Waiting for more info to be released";
                break;
            case 15:
                $return['color'] = "c-font-green";
                $return['text'] = "Information loaded. Awaiting final verification";
                break;
            case 16:
                $return['color'] = "c-font-green-3";
                $return['text'] = "Information verified as correct";
                break;
            case 10:
                $return['color'] = "c-font-red-1";
                $return['text'] = "Pending loading of results";
                break;
            case 11:
                $return['color'] = "c-font-green-3";
                $return['text'] = "Results Loaded";
                break;
            case 12:
                $return['color'] = "c-font-grey-2";
                $return['text'] = "No results expected";
                break;
            default:
                $return['color'] = "c-font-yellow";
                $return['text'] = "Unconfirmed dates and race times";
                break;
        }
        return $return;
    }

    public function getL2Keys($array) {
        $result = array();
        foreach ($array as $sub) {
            $result = array_merge($result, $sub);
        }
        return array_keys($result);
    }

    public function get_edition_name_from_status($edition_name, $edition_status, $edition_date = null) {
        // set edition names
        $e_names['edition_name'] = $edition_name;
        $e_names['edition_name_clean'] = $edition_name;
        $e_names['edition_name_no_date'] = substr($edition_name, 0, -5);
        switch ($edition_status) {
            case 2:
                $e_names['edition_name'] = $e_names['edition_name_no_date'] = $edition_name . " - DRAFT";
                break;
            case 3:
                $e_names['edition_name'] = $e_names['edition_name_no_date'] = $edition_name . " - CANCELLED";
                break;
            case 9:
                $e_names['edition_name'] = $e_names['edition_name_no_date'] = $edition_name . " - POSTPONED";
                break;
            default:
                if ($edition_date) {
                    $e_names['edition_name'] = $e_names['edition_name'] . " - " . fdateTitle($edition_date, true);
                } else {
                    $e_names['edition_name'] = $edition_name;
                }
                break;
        }
        return $e_names;
    }

    public function render_races_accordian_html($race_summary, $filter_title = "All") {
        $this->load->model('race_model');

        $return_html_arr = [];
        if ($race_summary) {

            $rand = rand(1, 500);

            $return_html_arr[] = '<div class="row c-page-faq-2">';

            $return_html_arr[] = '<div class="col-sm-3 hidden-xs">';
            $return_html_arr[] = '<ul class="nav nav-tabs c-faq-tabs" data-tabs="tabs">';
            $return_html_arr[] = '<li class="active"><a href="#all" data-toggle="tab">' . $filter_title . '</a></li>';

            foreach ($race_summary as $year => $year_list) {
                foreach ($year_list as $month => $month_list) {
                    $acc_month_list[$month . $year] = $month;
                }
            }

//            wts($acc_month_list);
//            wts($race_summary);
//            die();

            foreach ($acc_month_list as $month_key => $month) {
                $return_html_arr[] = '<li><a href="#' . $month_key . '" data-toggle="tab">' . $month . '</a></li>';
                //$return_html_arr[]='<div data-filter=".'.$month.'" class="cbp-filter-item"> '.$month.' </div>';
            }
            $return_html_arr[] = '</ul>';
            $return_html_arr[] = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>';
            $return_html_arr[] = '<ins class="adsbygoogle"';
            $return_html_arr[] = 'style="display:block"';
            $return_html_arr[] = 'data-ad-format="autorelaxed"';
            $return_html_arr[] = 'data-ad-client="ca-pub-8912238222537097"';
            $return_html_arr[] = 'data-ad-slot="9750166355"></ins>';
            $return_html_arr[] = '<script>';
            $return_html_arr[] = '(adsbygoogle = window.adsbygoogle || []).push({});';
            $return_html_arr[] = '</script></div>';


            $return_html_arr[] = '<div class="col-sm-9">';
            $return_html_arr[] = '<div class="tab-content">';


            $return_html_arr[] = '<div class="tab-pane active" id="all">';
            if ($filter_title == "Next 3 Months") {
                $return_html_arr[] = '<div class="c-content-title-1"><h3 class="c-font-uppercase c-font-bold">Events over the next 3 months</h3><div class="c-line-left"></div></div>';
            }
            $return_html_arr[] = '<div class="c-content-accordion-1"><div class="panel-group" id="accordion_' . $rand . '" role="tablist">';

            $n = 0;

            foreach ($race_summary as $year => $year_list) {
                foreach ($year_list as $month => $month_list) {
                    foreach ($month_list as $day => $edition_list) {
                        foreach ($edition_list as $edition_id => $edition) {

                            $e_names = $this->get_edition_name_from_status($edition['edition_name'], $edition['edition_status']);
                            $e_url = base_url("event/" . $edition['edition_slug']);

                            // set bullet color
                            $bullet_info = $this->get_bullet_color([
//                                "confirmed" => $edition['edition_info_isconfirmed'],
//                                "results" => $edition['edition_results_isloaded'],
//                                "results_status" => $edition['edition_results_status'],
                                "edition_status" => $edition['edition_status'],
                                "info_status" => $edition['edition_info_status'],
                            ]);

                            // set distance circles
                            $badge = "";
                            foreach ($edition['distance_arr'] as $distance) {
                                $color = $this->race_model->get_race_color($distance);
                                $badge .= "<span class='badge c-bg-$color'>" . $distance . "</span> ";
                            }

                            $date_name = date("M j", strtotime($edition['edition_date'])) . ' - ' . $e_names['edition_name'];

                            $return_html_arr[] = '<div class="panel">';
                            $return_html_arr[] = '<div class="panel-heading" role="tab" id="heading' . $edition_id . '">';
                            $return_html_arr[] = '<h4 class="panel-title">';
                            $return_html_arr[] = '<a class="" data-toggle="collapse" data-parent="#accordion_' . $rand . '" href="#collapse' . $edition_id . '" aria-expanded="true" aria-controls="collapse' . $edition_id . '">';
                            $return_html_arr[] = '<table class="accordian" style="width: 100%"><tr>';
                            $return_html_arr[] = '<td style="width: 10px;"><i class="' . $bullet_info['color'] . ' fa fa-check-square" title="' . $bullet_info['text'] . '"></i> </td>';
                            if ($edition['edition_isfeatured']) {
                                $return_html_arr[] = '<td><b>' . $date_name . '</b></td>';
                            } else {
                                $return_html_arr[] = '<td>' . $date_name . '</td>';
                            }
                            $return_html_arr[] = '<td class="badges hidden-xs">' . $badge . '</td>';
                            $return_html_arr[] = '</tr></table>';
                            $return_html_arr[] = '</a>';
                            $return_html_arr[] = '</h4>';
                            $return_html_arr[] = '</div>';
                            if ($n == 0) {
                                $act = "in";
                            } else {
                                $act = "";
                            }
                            $return_html_arr[] = '<div id="collapse' . $edition_id . '" class="panel-collapse collapse ' . $act . '" role="tabpanel" aria-labelledby="heading' . $edition_id . '">';
                            $return_html_arr[] = '<div class="panel-body">';
                            $return_html_arr[] = '<p style="margin:0 0 5px;"><span class="visible-xs">' . $badge . '</span></p>';
                            $return_html_arr[] = '<p><b>When: </b>' . $edition['edition_date'] . "<br>";
                            $return_html_arr[] = '<b>Where: </b>' . $edition['town_name'] . "<br>";
                            $return_html_arr[] = '<b>Distances: </b>' . $edition['race_distance'] . "<br>";
//                                            $return_html_arr[]='<b>Time of day: </b>'.$edition['race_time_start']."<br>";
                            $return_html_arr[] = '<b>Start Times: </b>' . $edition['start_time'] . "<br>";
                            $return_html_arr[] = '<b>Info Status: </b>' . $bullet_info['text'] . "</p>";
                            $return_html_arr[] = '<p><a href="' . $e_url . '"  class="btn c-theme-btn c-btn-border-2x c-btn-square">DETAIL</a></p>';
                            $return_html_arr[] = '</div>';
                            $return_html_arr[] = '</div>';
                            $return_html_arr[] = '</div>';
                            $n++;
                        }
                    }
                }
            }


            $return_html_arr[] = '</div></div>'; //c-content-accordion-1 + panel-group
            $return_html_arr[] = '</div>'; // tab-pane

            $m = 0;

            // maande
            foreach ($race_summary as $year => $year_list) {
                foreach ($year_list as $month => $month_list) {
                    $month_key = $month . $year;
                    $m++;

                    $return_html_arr[] = '<div class="tab-pane" id="' . $month_key . '">';
                    $return_html_arr[] = '<div class="c-content-title-1"><h3 class="c-font-uppercase c-font-bold">Events in ' . $month . '</h3><div class="c-line-left"></div></div>';
                    $return_html_arr[] = '<div class="c-content-accordion-1"><div class="panel-group" id="accordion' . $month_key . '" role="tablist">';

                    $n = 0;
                    foreach ($month_list as $day => $edition_list) {
                        foreach ($edition_list as $edition_id => $edition) {

                            $e_names = $this->get_edition_name_from_status($edition['edition_name'], $edition['edition_status']);
                            $e_url = base_url("event/" . $edition['edition_slug']);

                            // set bullet color
//                            $bullet_info = $this->get_bullet_color([
//                                "confirmed" => $edition['edition_info_isconfirmed'],
//                                "results" => $edition['edition_results_isloaded'],
//                                "status" => $edition['edition_status'],
//                            ]);
                            $bullet_info = $this->get_bullet_color([
//                                "confirmed" => $edition['edition_info_isconfirmed'],
//                                "results" => $edition['edition_results_isloaded'],
//                                "results_status" => $edition['edition_results_status'],
                                "edition_status" => $edition['edition_status'],
                                "info_status" => $edition['edition_info_status'],
                            ]);

                            // set distance circles
                            $badge = "";
                            foreach ($edition['distance_arr'] as $distance) {
                                $color = $this->race_model->get_race_color($distance);
                                $badge .= "<span class='badge c-bg-$color'>" . intval($distance) . "</span> ";
                            }

                            $uid = $n . $edition_id;

                            $return_html_arr[] = '<div class="panel">';
                            $return_html_arr[] = '<div class="panel-heading" role="tab" id="heading' . $uid . '">';
                            $return_html_arr[] = '<h4 class="panel-title">';
                            $return_html_arr[] = '<a class="" data-toggle="collapse" data-parent="#accordion' . $month_key . '" href="#collapse' . $uid . '" aria-expanded="true" aria-controls="collapse' . $uid . '">';
                            $return_html_arr[] = '<table class="accordian" style="width: 100%"><tr>';
                            $return_html_arr[] = '<td style="width: 10px;"><i class="' . $bullet_info['color'] . ' fa fa-check-square" title="' . $bullet_info['text'] . '"></i> </td>';
                            $return_html_arr[] = '<td>' . date("M j", strtotime($edition['edition_date'])) . '</b> - ' . $e_names['edition_name'] . '</td>';
                            $return_html_arr[] = '<td class="badges hidden-xs">' . $badge . '</td>';
                            $return_html_arr[] = '</tr></table>';
                            $return_html_arr[] = '</a>';
                            $return_html_arr[] = '</h4>';
                            $return_html_arr[] = '</div>';
                            if ($n == 0) {
                                $act = "in";
                            } else {
                                $act = "";
                            }
                            $return_html_arr[] = '<div id="collapse' . $uid . '" class="panel-collapse collapse ' . $act . '" role="tabpanel" aria-labelledby="heading' . $uid . '">';
                            $return_html_arr[] = '<div class="panel-body">';
                            $return_html_arr[] = '<p style="margin:0 0 5px;"><span class="visible-xs">' . $badge . '</span></p>';
                            $return_html_arr[] = '<p><b>When: </b>' . $edition['edition_date'] . "<br>";
                            $return_html_arr[] = '<b>Where: </b>' . $edition['town_name'] . "<br>";
                            $return_html_arr[] = '<b>Distances: </b>' . $edition['race_distance'] . "<br>";
//                                                $return_html_arr[]='<b>Time of day: </b>'.$edition['race_time_start']."<br>";
                            $return_html_arr[] = '<b>Start Times: </b>' . $edition['start_time'] . "<br>";
                            $return_html_arr[] = '<b>Info Status: </b>' . $bullet_info['text'] . "</p>";
                            $return_html_arr[] = '<p><a href="' . $e_url . '" class="btn c-theme-btn c-btn-border-2x c-btn-square">DETAIL</a></p>';
                            $return_html_arr[] = '</div>';
                            $return_html_arr[] = '</div>';
                            $return_html_arr[] = '</div>';

                            $n++;
                        }
                    }

                    $return_html_arr[] = '</div></div>'; //c-content-accordion-1 + panel-group
                    $return_html_arr[] = '</div>'; // tab-pane
                }
            }


            $return_html_arr[] = '</div>'; // tab-content
            $return_html_arr[] = '</div>'; // close col-sm-9

            $return_html_arr[] = '</div>'; // close row c-page-faq-2
        } else {
            $return_html_arr[] = '<p>There is no event data to display.</p><p>&nbsp;</p>';
        }

        return implode("", $return_html_arr);
    }

    public function render_races_table_html($race_summary, $page = "other") {
        $return_html_arr = [];

        if ($race_summary) {
            $n = 0;
            foreach ($race_summary as $month => $edition_list) {

                if ($page == "home") {
                    $return_html_arr[] = '<div class="c-content-title-1">';
                    $return_html_arr[] = '<h3 class="c-font-34 c-center c-font-bold c-font-uppercase">Races in ' . $month . '</h3>';
                    $return_html_arr[] = '<div class="c-line-center c-theme-bg"></div>';
                    $return_html_arr[] = '</div>';
                }


                $return_html_arr[] = '<div class="table-responsive">';
                $return_html_arr[] = '<table class="table table-bordered" style="margin-bottom: 40px;">';

                $return_html_arr[] = "<thead>";
                if ($page != "home") {
                    $return_html_arr[] = "<tr><th colspan='6' class='table-head-blue'>Races in $month</th></tr>";
                }
                $return_html_arr[] = "<tr><th>Date</th><th>Event</th><th>Place</th><th>Race Distances</th><th>Time of Day</th><th></th></tr>";
                $return_html_arr[] = "</thead>";

                $return_html_arr[] = '<tbody>';

                foreach ($edition_list as $edition_id => $edition) {
                    $return_html_arr[] = "<tr>";
                    $return_html_arr[] = "<th scope='row'><a href='" . $edition['edition_url'] . "'>" . $edition['edition_date'] . "</a></th>";
                    $return_html_arr[] = "<td>" . $edition['edition_name'] . "</td>";
                    $return_html_arr[] = "<td>" . $edition['town_name'] . "</td>";
                    $return_html_arr[] = "<td>" . $edition['race_distance'] . "</td>";
                    $return_html_arr[] = "<td>" . $edition['race_time_start'] . "</td>";
                    $return_html_arr[] = "<td style='padding: 2px; text-align: center;'><a href='" . $edition['edition_url'] . "' class='btn c-theme-btn c-btn-border-2x c-btn-square'>DETAIL</a></td>";
                    $return_html_arr[] = "</tr>";
                }

                $return_html_arr[] = '</tbody>';

                $return_html_arr[] = '</table>';
                $return_html_arr[] = '</div>';
            }
        } else {
            $return_html_arr[] = '<p>There is currently no event data to display. Please chack back again shortly.</p><p>&nbsp;</p>';
        }

        return implode("", $return_html_arr);
    }

    public function get_area_list() {
        $this->load->model('area_model');
        $area_list = $this->area_model->get_area_list();
        return $area_list;
    }

    private function set_subscribe_confirmation_email($usersub_data) {
        $this->load->model('user_model');
        $this->load->model('emailque_model');
        // get user data
        $user_data = $this->user_model->get_user_detail($usersub_data['user_id']);
        // get edition_data
        if ($usersub_data['linked_to'] == "edition") {
            $this->load->model('edition_model');
            $edition_data = $this->edition_model->get_edition_url_from_id($usersub_data['linked_id']);
        }
        // set body
        switch ($usersub_data['linked_to']) {
            case "newsletter":
                $switch = " our <strong>monthly newletter</strong>.";
                break;
            case "edition":
                $switch = " updates regarding the <strong>" . $edition_data['edition_name'] . "</strong> event.";
                break;
        }
        $body_arr[] = "Hi " . $user_data['user_name'] . "<br>";
        $body_arr[] = "This is a courtesy email to confirm you have been subscribed to receive " . $switch;
        $body_arr[] = "If <u>this was not you</u> subscribing yourself to this awesome service, please reply to this email to be removed.<br>";
        $body_arr[] = "Kind Regards";
        $body_arr[] = "Johan from RoadRunning.co.za";
        $body = implode("<br>", $body_arr);

        $data = array(
            'emailque_subject' => ucfirst($usersub_data['linked_to']) . " subscription successful",
            'emailque_to_address' => $user_data['user_email'],
            'emailque_to_name' => $user_data['user_name'] . " " . $user_data['user_surname'],
            'emailque_body' => $body,
            'emailque_status' => 5,
            'emailque_from_address' => $this->ini_array['email']['from_address_server'],
            'emailque_from_name' => $this->ini_array['email']['from_name_server'],
//            'emailque_bcc_address' => $this->ini_array['email']['bcc_address'],
        );
        $set = $this->emailque_model->set_emailque("add", 0, $data);
    }

    public function subscribe_user($user_data, $linked_to, $linked_id) {
        // this function will add a user to a subscription        
        $this->load->model('user_model');
        $this->load->model('role_model');
        $this->load->model('usersubscription_model');

        // get user id
        $user_id = $this->user_model->get_user_id($user_data['user_email']);
        // new user
        if (!$user_id) {
            $user_data['role_arr'] = [2]; // role 2 = user
            $user_id = $this->user_model->set_user("add", 0, $user_data, true);
        } else {
            // check if role 2 exist
            $role_list = $this->role_model->get_role_list_per_user($user_id);
            if (!in_array(2, $role_list)) {
                $this->role_model->set_user_role($user_id, 2);
            }
        }

        // check if subscription exists
        $sub_exists = $this->usersubscription_model->exists($user_id, $linked_to, $linked_id);
        if ($sub_exists) {
            $alert = "<b>Note</b>: We found a subsciption already existed for the email address entered.  If you believe this to be an error please contact the site administrator.";
            $status = "warning";
        } else {
            $usersubscription_data = array(
                'user_id' => $user_id,
                'linked_to' => $linked_to,
                'linked_id' => $linked_id,
            );

            $add = $this->usersubscription_model->set_usersubscription("add", $usersubscription_data);
            if ($add) {
                $email = $this->set_subscribe_confirmation_email($usersubscription_data);
                $alert = "<b>Success!</b> Thank you. You have been added to the subscription";
                $status = "success";
            } else {
                $alert = "<b>Note</b>:Failed to add subsciprtion. Please contact the site administrator";
                $status = "danger";
            }
        }
        // set session flash data
        $this->session->set_flashdata(['alert' => $alert, 'status' => $status,]);
    }

    // central MAILER function
    public function send_mail($data = "") {
        $this->load->library('email');

        $config['mailtype'] = 'html';
        $config['smtp_host'] = $this->ini_array['email']['smtp_server'];
        $config['smtp_port'] = $this->ini_array['email']['smtp_port'];
        $config['smtp_crypto'] = $this->ini_array['email']['smtp_crypto'];
        $config['charset'] = $this->ini_array['email']['email_charset'];
        $config['useragent'] = $this->ini_array['email']['useragent'];
        $this->email->initialize($config);

        $this->email->subject($data['emailque_subject']);
        $this->email->from($data['emailque_from_address'], $data['emailque_from_name']);
        $this->email->to($data['emailque_to_address'], $data['emailque_to_name']);
        if ($data['emailque_cc_address']) {
            $this->email->bcc($data['emailque_cc_address']);
        }
        if ($data['emailque_bcc_address']) {
            $this->email->bcc($data['emailque_bcc_address']);
        }
        // add default BCC address to ALL outgoing email
        $this->email->bcc($this->ini_array['email']['bcc_address']);
        $this->email->message($data['emailque_body']);

//        wts($data);
//        wts($this->email);
//        exit();

        $send = $this->email->send();

        return $send;
    }

    public function recaptcha($str = "") {
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = '6LcxdoYUAAAAAFphXeYMlOL2w5ysa9ovdOdCLJyP';
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $google_url . "?secret=" . $secret . "&response=" . $str . "&remoteip=" . $ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        //reCaptcha success check
        if ($res['success']) {
            return TRUE;
        } else {
            $this->form_validation->set_message('recaptcha', 'The <b>reCAPTCHA</b> field is telling me that you are a robot. Shall we give it another try?');
            return FALSE;
        }
    }

    // ==============================================================================================
    // HISTORY
    // ============================================================================================== 
    private function check_history() {
        // check current session history
        if (!isset($_SESSION['history'])) {
            $_SESSION['history'] = [];
        } else {
            $this->history_purge();
        }
        // check if cookie exists
        if (is_null(get_cookie('session_token'))) {
            set_cookie("session_token", session_id(), 604800);
            $session_token = session_id();
        } else {
            $session_token = get_cookie('session_token');
        }

        // check if the url not already in session
        if (!in_array(current_url(), $_SESSION['history'])) {
            // set session variable
            $_SESSION['history'][time()] = current_url();

            // chcek if uri not in exclusion list
            if (!$this->segment_exclusion_list(uri_string())) {
                // check if url has already been counted today for this session. If not add to DB
                $this->load->model('history_model');
                $history_exists = $this->history_model->check_history($session_token, current_url());
                if (!$history_exists) {
                    $history_data = [
                        "history_session_id" => $session_token,
                        "history_url" => current_url(),
                    ];
                    if (isset($_SESSION['user']['user_id'])) {
                        $history_data['user_id'] = $_SESSION['user']['user_id'];
                    }
                    // set DB
                    $this->history_model->set_history($history_data);
                }
            }
        }
        return $_SESSION['history'];
    }

    private function segment_exclusion_list($uri_string) {
        $seg = explode("/", $uri_string);
        if ((in_array($uri_string, $this->ini_array['history']['exclusion'])) || (in_array($seg[0] . "/*", $this->ini_array['history']['exclusion']))) {
            return true;
        } else {
            return false;
        }
    }

    private function history_purge() {
        foreach ($_SESSION['history'] as $timestamp => $url) {
            if ($timestamp < strtotime($this->ini_array['history']['purge_period'])) {
                unset($_SESSION['history'][$timestamp]);
            }
        }
    }

}
