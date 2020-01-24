<?php

class MY_Controller extends CI_Controller {

    public $data_to_views = [];
    public $header_url = "/templates/header";
    public $banner_url = "/templates/banner";
    public $notice_url = "/templates/notice";
    public $footer_url = "/templates/footer";
    public $logged_in_user = [];
    public $crumb_arr = [];

    function __construct() {
        parent::__construct();
        // make ini file content available 
        $this->ini_array = parse_ini_file("server_config.ini", true);
        // doen checks en set session vars
        $this->data_to_views['logged_in_user'] = $this->logged_in_user = $this->check_if_user_is_logged_in();
        $this->data_to_views['history'] = $this->check_history();
        $this->data_to_views['crumbs_arr'] = $this->set_crumbs();

        // check of weer moet laai
        if ($this->check_value_refresh()) {
            $this->session->set_userdata("static_pages", $this->get_static_pages());
            $this->session->set_userdata("province_pages", $this->get_province_pages());
            $this->session->set_userdata("region_pages", $this->get_region_pages());
            $this->session->set_userdata("most_viewed_pages", $this->get_most_viewed_pages());
            $this->session->set_userdata("most_searched", $this->get_most_searched());
            $this->session->set_userdata("calendar_date_list", $this->get_date_list());
        }

        // set email cookie
        $this->data_to_views['rr_cookie']['sub_email'] = get_cookie("sub_email");
    }

    public function show_my_404($msg, $status) {
        //Using 'location' not work well on some windows systems
        $this->session->set_flashdata([
            'alert' => $msg,
            'status' => $status,
        ]);
        redirect('404');
    }

    // ==============================================================================================
    // SESSION CHECKS
    // ==============================================================================================  
    private function check_if_user_is_logged_in() {
        // check of user ingelog is. set view variable to user
        if (isset($_SESSION['user']['logged_in'])) {
            return $_SESSION['user'];
        } else {
            return $user['user']['logged_in'] = false;
        }
    }

    private function check_value_refresh() {
        // check if data was last retrieved a day ago or more, then unsets data to be retrieved again
        if ((!$this->session->has_userdata('session_value_refresh')) || ($this->session->session_value_refresh < strtotime($this->ini_array['session']['static_values_expiry']))) {
            $this->session->set_userdata("session_value_refresh", time());
            return true;
        } else {
            return false;
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

    private function get_most_viewed_pages() {
        $this->load->model('history_model');
        $query_params = [
            "order_by" => ["historysum_countweek" => "DESC"],
            "limit" => 5,
        ];
        return $this->history_model->get_history_summary($query_params);
    }

    private function get_most_searched() {
        $this->load->model('history_model');
        $query_params = [
            "where" => ["updated_date >" => date('Y-m-d', strtotime("1 month ago"))],
            "order_by" => ["search_count" => "DESC"],
            "limit" => 5,
        ];
        return $this->history_model->get_most_searched($query_params);
    }

    // ==============================================================================================
    // CENTRAL MAIL FUNCTIONS
    // ==============================================================================================
    public function set_email($data) {
        // THIS FUNCTION ONLY TAKES EMAIL FIELDS AND ADD THEM TO THE EMAIL QUE TABLE
        // load emailque_model
        $this->load->model('emailque_model');
        $required_fields = ['to', 'subject', 'body'];
        if (array_keys_exists($required_fields, $data)) {
            if (isset($data['from'])) {
                $from = $data['from'];
            } else {
                $from = $this->ini_array['email']['from_address'];
            }
            if (isset($data['from_name'])) {
                $from_name = $data['from_name'];
            } else {
                $from_name = $this->ini_array['email']['from_name'];
            }
            $emailque_data = array(
                'emailque_subject' => $data['subject'],
                'emailque_to_address' => $data['to'],
                'emailque_body' => $this->set_email_body($data['body']),
                'emailque_status' => 5,
                'emailque_from_address' => $from,
                'emailque_from_name' => $from_name,
                'emailque_bcc_address' => $this->ini_array['email']['bcc_address'],
            );
            if (isset($data['to_name'])) {
                $emailque_data['emailque_to_name'] = $data['to_name'];
            }
            $params = [
                "action" => "add",
                "data" => $emailque_data,
                "id" => false,
            ];
//            echo $emailque_data['emailque_body'];
//            die();
            return $this->emailque_model->set_emailque($params);
        } else {
            die("Missing required fields to send email: MY_Controller->send_mail");
        }
    }

    private function set_email_body($body) {
        $year = date("Y");
        $html = <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv = "Content-Type" content = "text/html; charset=utf-8" />
<meta name = "viewport" content = "width=device-width, initial-scale=1.0"/>
<style>a, a[x-apple-data-detectors] { color:inherit!important;
font-family:inherit!important;
font-size:inherit!important;
font-weight:inherit!important;
line-height:inherit!important;
text-decoration:none!important;
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
$year RoadRunningZA. All rights reserved.</div>
</div>
</td></tr></tbody></table>
</td></tr></tbody></table>
</body>
</html>
EOT;
        return $html;
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
    // SITEMAP / MENU INFO
    // ==============================================================================================

    public function get_static_pages() {
        $static_pages = [
            "home" => [
                "display" => "Home",
                "loc" => base_url(),
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 day")),
                "priority" => 1,
                "changefreq" => "daily",
            ],
            "races" => [
                "display" => "Races",
//                "loc" => base_url("race/list"),
                "loc" => "",
                "lastmod" => date("Y-m-d H:i:s", strtotime("-2 day")),
                "priority" => 1,
                "changefreq" => "daily",
                "sub-menu" => [
                    "upcoming" => [
                        "display" => "Upcoming",
                        "loc" => base_url("race/upcoming"), //calendar
                        "lastmod" => date("Y-m-d H:i:s", strtotime("-2 day")),
                        "priority" => 1,
                        "changefreq" => "daily",
                        "badge" => "POPULAR",
                    ],
//                    "per_region" => [
//                        "display" => "Per Region",
//                        "loc" => base_url("race/per-region"),
//                        "lastmod" => date("Y-m-d H:i:s", strtotime("-2 day")),
//                        "priority" => 1,
//                        "changefreq" => "daily",
//                    ],
                    "featured" => [
                        "display" => "Featured",
                        "loc" => base_url("race/featured"),
                        "lastmod" => date("Y-m-d H:i:s", strtotime("-2 day")),
                        "priority" => 1,
                        "changefreq" => "daily",
                    ],
                    "top10" => [
                        "display" => "Top 10 most viewed",
                        "loc" => base_url("race/most-viewed"),
                        "lastmod" => date("Y-m-d H:i:s", strtotime("-2 day")),
                        "priority" => 1,
                        "changefreq" => "daily",
                    ],
                    "history" => [
                        "display" => "History",
                        "loc" => base_url("race/history"), //calendar/past
                        "lastmod" => date("Y-m-d H:i:s", strtotime("-2 day")),
                        "priority" => 0.8,
                        "changefreq" => "daily",
                    ],
//                    "add-listing" => [
//                        "display" => "Add Listing",
//                        "loc" => base_url("race/add"), //calendar/past
//                        "lastmod" => date("Y-m-d H:i:s", strtotime("-1 month")),
//                        "priority" => 0.8,
//                        "changefreq" => "daily",
//                    ],
                    "training" => [
                        "display" => "Training Programs",
                        "loc" => base_url("training-programs"),
                        "lastmod" => date("Y-m-d H:i:s", strtotime("-1 month")),
                        "priority" => 0.5,
                        "changefreq" => "monthly",
                    ],
                ],
            ],
            "results" => [
                "display" => "Results",
//                "loc" => base_url("race/results"),
                "loc" => "",
                "lastmod" => date("Y-m-d H:i:s", strtotime("-5 day")),
                "priority" => 0.8,
                "changefreq" => "weekly",
                "sub-menu" => [
                    "upcoming" => [
                        "display" => "Race Results",
                        "loc" => base_url("race/results"),
                        "lastmod" => date("Y-m-d H:i:s", strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                    "my-results" => [
                        "display" => "My Results",
                        "loc" => "",
//                        "loc" => base_url("result/my-results"),
                        "lastmod" => date("Y-m-d H:i:s", strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                        "badge" => "COMING SOON",
                    ],
                ],
            ],
            "faq" => [
                "display" => "FAQ",
                "loc" => base_url("faq"),
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 month")),
                "priority" => 0.5,
                "changefreq" => "monthly",
            ],
            "about" => [
                "display" => "About",
                "loc" => base_url("about"),
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 month")),
                "priority" => 0.5,
                "changefreq" => "monthly",
            ],
            "contact" => [
                "display" => "Contact",
                "loc" => "",
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 year")),
                "priority" => 0.8,
                "changefreq" => "yearly",
                "sub-menu" => [
                    "contact-us" => [
                        "display" => "Contact Us",
                        "loc" => base_url("contact"),
                        "lastmod" => date("Y-m-d H:i:s", strtotime("-1 year")),
                        "priority" => 0.8,
                        "changefreq" => "yearly",
                    ],
                    "newsletter" => [
                        "display" => "Newsletter",
                        "loc" => base_url("newsletter"),
                        "lastmod" => date("Y-m-d H:i:s", strtotime("-1 year")),
                        "priority" => 0.6,
                        "changefreq" => "yearly",
                        "badge" => "POPULAR",
                    ],
                ],
            ],
            "switch-region" => [
                "display" => "Switch Region",
                "loc" => base_url("region/switch"), //version
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 year")),
                "priority" => 0.5,
                "changefreq" => "yearly",
            ],
            "featured-regions" => [
                "display" => "Show all regions",
                "loc" => base_url("region"),
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 month")),
                "priority" => 0.8,
                "changefreq" => "monthly",
                "sub-menu" => [
                    "upcoming" => [
                        "display" => "Cape Town",
                        "loc" => base_url("region/capetown"),
                        "lastmod" => date("Y-m-d H:i:s", strtotime("-5 day")),
                        "priority" => 1,
                        "changefreq" => "weekly",
                        "badge" => "POPULAR",
                    ],
                    "gauteng" => [
                        "display" => "Gauteng",
                        "loc" => base_url("region/gauteng"),
                        "lastmod" => date("Y-m-d H:i:s", strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                    "kzn-coast" => [
                        "display" => "KZN Coast",
                        "loc" => base_url("region/kzn-coast"),
                        "lastmod" => date("Y-m-d H:i:s", strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                    "garden-route" => [
                        "display" => "Garden Route",
                        "loc" => base_url("region/garden-route"),
                        "lastmod" => date("Y-m-d H:i:s", strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                ],
            ],
            "login" => [
                "display" => "Login",
                "loc" => base_url("login"),
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 year")),
                "priority" => 1,
                "changefreq" => "yearly",
            ],
            "sitemap" => [
                "display" => "Sitemap",
                "loc" => base_url("sitemap"),
                "lastmod" => date("Y-m-d H:i:s"),
                "priority" => 0.5,
                "changefreq" => "daily",
            ],
            "terms" => [
                "display" => "Terms & Conditions",
                "loc" => base_url("terms-conditions"),
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 year")),
                "priority" => 0.2,
                "changefreq" => "yearly",
            ],
            "disclaimer" => [
                "display" => "Disclaimer",
                "loc" => base_url("disclaimer"),
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 year")),
                "priority" => 0.2,
                "changefreq" => "yearly",
            ],
        ];

        $this->session->set_userdata("static_pages", $static_pages);
        return $static_pages;
    }

    public function get_province_pages() {
        $this->load->model('event_model');
        // get province list from event model to only return those provinces that is in use
        $province_list = $this->event_model->get_province_list();

        foreach ($province_list as $province_id => $province) {
            $p_arr[$province_id] = [
                "display" => $province['province_name'],
                "loc" => base_url("province/" . $province['province_slug']),
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 week")),
                "priority" => 0.9,
                "changefreq" => "daily",
            ];
        }
        return $p_arr;
    }

    public function get_region_pages() {
        $this->load->model('event_model');
        $region_list = $this->event_model->get_region_list();

        foreach ($region_list as $region_id => $region) {
            $r_arr[$region_id] = [
                "display" => $region['region_name'],
                "loc" => base_url("region/" . $region['region_slug']),
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 week")),
                "priority" => 0.9,
                "changefreq" => "daily",
            ];
        }
        return $r_arr;
    }

    public function get_date_list() {
        $dates_to_fetch = [
            "1 month ago",
            "today",
            "+1 month",
            "+2 month",
            "+3 month",
            "+4 month",
//            "+5 month",
        ];
        foreach ($dates_to_fetch as $strtotime) {
            $date_list[date("Y", strtotime($strtotime))][date("m", strtotime($strtotime))] = date("F Y", strtotime($strtotime));
        }
        return $date_list;
    }

    // ==============================================================================================
    // CENTRAL FUNCTIONS
    // ==============================================================================================

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

    public function formulate_status_notice($edition_data) {
        $return = [];
        ;
//        echo $event_detail['edition_status'];
//        die();
        switch ($edition_data['edition_status']) {
            case 2:
                $msg = "<b>This event is set to DRAFT mode.</b> All detail has not yet been confirmed";
                $short_msg = "DRAFT";
                $state = "danger";
                $icon = "minus-circle";
                break;
            case 3:
                if (isset($edition_data['user_email'])) {
                    $email = $edition_data['user_email'];
                } else {
                    $email = '';
                }
                $msg = "<strong>This event has been CANCELLED.</strong> Please contact the event organisers for more detail on: <a href='mailto:$email' class='link' title='Email organisers'>$email</a>";
                $short_msg = "CANCELLED";
                $state = "danger";
                $icon = "times-circle";
                break;
            case 9:
                if (isset($edition_data['user_email'])) {
                    $email = $edition_data['user_email'];
                } else {
                    $email = '';
                }
                $msg = "<strong>This event has been POSTPONED until further notice.</strong> Please contact the event organisers for more detail on: <a href='mailto:$email' class='link' title='Email organisers'>$email</a><br>"
                        . "Please consider <b><a href='#subscribe'>subscribing</a></b> to the event below to receive an email once a new date is set";
                $short_msg = "POSTPONED";
                $state = "warning";
                $icon = "minus-circle";
                break;
            default:
                switch ($edition_data['edition_info_status']) {
                    case 13:
                        $msg = "<strong>PLEASE NOTE</strong><br>Dates and race times has <u>not yet been confirmed</u> by the race organisers";
                        $short_msg = "Dates not confirmed yet";
                        $state = "danger";
                        $icon = "minus-circle";
                        break;
                    case 14:
                        $msg = "<b>INFO UNCONFIRMED</b><br>Waiting for race information from the organisers";
                        $short_msg = "Dates not confirmed yet";
                        $state = "warning";
                        $icon = "info-circle";
                        break;
                    case 15:
                        $msg = "<b>INFO INCOMPLETE</b><br>All information loaded has been confirmed as correct, but listing is not complete";
                        $short_msg = "Outstanding information";
                        $state = "info";
                        $icon = "info-circle";
                        break;
                    case 16:
                        $msg = "<b>LISTING VERIFIED</b><br>All information below has been confirmed";
                        $short_msg = "Listing complete";
                        $state = "success";
                        $icon = "check-circle";
                        break;
                    case 10:
                        $msg = "<b>RESULTS PENDING</b><br>Waiting for results to be released by organisers. Note this can take up to a week";
                        $short_msg = "Waiting for results";
                        $state = "info";
                        $icon = "info-circle";
                        break;
                    case 11:
                        $slug = $edition_data['edition_slug'];
                        $msg = "<b>RESULTS LOADED</b><br>Click to <a href='" . base_url("event/$slug/results") . "'>view results</a>";
                        $short_msg = "Results loaded";
                        $state = "success";
                        $icon = "check-circle";
                        break;
                    case 12:
                        $msg = "<b>NO RESULTS EXPECTED</b><br>No official results will be released for this event";
                        $short_msg = "No results expexted";
                        $state = "warning";
                        $icon = "minus-circle";
                        break;
                }
                break;
        }

        $return['msg'] = $msg;
        $return['short_msg'] = $short_msg;
        $return['state'] = $state;
        $return['icon'] = $icon;
        return $return;
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
                $segs[$x] = "race";
            }
            // make controller prural for display purposes
            if (in_array($segs[$x], ["race"])) {
                $segs[$x] = $segs[$x] . "s";
            }

            $segs[$x] = str_replace("_", " ", $segs[$x]);
            $segs[$x] = str_replace("-", " ", $segs[$x]);
            $crumbs[ucwords($segs[$x])] = $crumb_uri;

            if ($x == 3) {
                break;
            }
        }

        return $crumbs;
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
            $params = [
                "action" => "add",
                "user_data" => $user_data,
                "role_arr" => [2],
            ];
            $user_id = $this->user_model->set_user($params);
//            $user_id = $this->user_model->set_user("add", 0, $user_data, true);
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
            $alert = "We found a subsciption already existed for the email address entered. If you believe this to be an error please <a href='mailto:info@roadrunning.co.za'>contact me</a>.";
            $status = "warning";
            $icon = "info-circle";
        } else {
            $usersubscription_data = array(
                'user_id' => $user_id,
                'linked_to' => $linked_to,
                'linked_id' => $linked_id,
            );

            $add = $this->usersubscription_model->set_usersubscription("add", $usersubscription_data);
            if ($add) {
                $email = $this->set_subscribe_confirmation_email($usersubscription_data);
                switch ($linked_to) {
                    case "edition":
                        $alert = "Thank you. You have been added to the mailing list for this race";
                        break;
                    case "newsletter":
                        $alert = "Thank you. You have successfully been subscribed to the newsletter";
                        break;
                    default:
                        $alert = "Thank you. You have successfully been subscribed";
                        break;
                }
                $status = "success";
                $icon = "check-circle";
            } else {
                $alert = "Failed to add subsciprtion. Please contact the site administrator";
                $status = "danger";
                $icon = "minus-circle";
            }
        }
        // set session flash data
        $this->session->set_flashdata([
            'alert' => $alert,
            'status' => $status,
            'icon' => $icon,
        ]);
    }

    private function set_subscribe_confirmation_email($usersub_data) {
        $this->load->model('user_model');
        $this->load->model('emailque_model');
        $this->load->model('edition_model');
        // get user data
        $user_data = $this->user_model->get_user_detail($usersub_data['user_id']);
        // get edition_data
        if ($usersub_data['linked_to'] == "edition") {
            $this->load->model('edition_model');
            $edition_data = $this->edition_model->get_edition_sum($usersub_data['linked_id']);
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
        $body_arr[] = "Hi " . $user_data['user_name'] . ",<br>";
        $body_arr[] = "This is a courtesy email to confirm you have been subscribed to receive " . $switch . "<br>";
        $body_arr[] = "If <u>this was not you</u> subscribing yourself to this awesome service, please reply to this email to be removed.<br>";
        $body_arr[] = "Kind Regards";
        $body_arr[] = "Johan from RoadRunning.co.za";
        $body = implode("<br>", $body_arr);

        $data = [
            "to" => $user_data['user_email'],
            "subject" => ucfirst($usersub_data['linked_to']) . " subscription successful",
            "body" => $body,
            "from" => $this->ini_array['email']['from_address_server'],
            "from_name" => $this->ini_array['email']['from_name_server'],
        ];

        $this->set_email($data);
        return $this->set_email($data);
    }

}
