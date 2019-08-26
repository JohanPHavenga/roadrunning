<?php

class MY_Controller extends CI_Controller {

    public $data_to_views = [];
    public $header_url = "/templates/header";
    public $footer_url = "/templates/footer";

    function __construct() {
        parent::__construct();
        // make ini file content available 
        $this->ini_array = parse_ini_file("server_config.ini", true);
        // doen checks en set session vars
        $this->data_to_views['user'] = $this->check_if_user_is_logged_in();
        $this->data_to_views['history'] = $this->check_history();
        $this->check_value_refresh();
        $this->data_to_views['static_pages'] = $this->get_static_pages();
        $this->data_to_views['province_pages'] = $this->check_province_session();
        $this->data_to_views['region_pages'] = $this->check_region_session();
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
            $this->session->unset_userdata("static_pages");
            $this->session->unset_userdata("province_pages");
            $this->session->unset_userdata("region_pages");
            $this->session->set_userdata("session_value_refresh", time());
        }
    }

    private function check_province_session() {
        if (!$this->session->has_userdata('province_pages')) {
            $this->session->set_userdata("province_pages", $this->get_province_pages());
        }
        return $this->session->province_pages;
    }

    private function check_region_session() {
        if (!$this->session->has_userdata('region_pages')) {
            $this->session->set_userdata("region_pages", $this->get_region_pages());
        }
        return $this->session->region_pages;
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
        $seg=explode("/", $uri_string);
        if ((in_array($uri_string, $this->ini_array['history']['exclusion'])) || (in_array($seg[0]."/*",$this->ini_array['history']['exclusion']))) {
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
                'emailque_body' => $data['body'],
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
            return $this->emailque_model->set_emailque($params);
        } else {
            die("Missing required fields to send email: MY_Controller->send_mail");
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
            "calendar" => [
                "display" => "Race Calendar",
                "loc" => base_url("race-calendar"),
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 day")),
                "priority" => 1,
                "changefreq" => "daily",
            ],
            "past" => [
                "display" => "Past Events",
                "loc" => base_url("calendar/past"),
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 day")),
                "priority" => 0.8,
                "changefreq" => "weekly",
            ],
            "about" => [
                "display" => "About",
                "loc" => base_url("about"),
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 month")),
                "priority" => 1,
                "changefreq" => "monthly",
            ],
            "contact" => [
                "display" => "Contact",
                "loc" => base_url("contact"),
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 year")),
                "priority" => 1,
                "changefreq" => "yearly",
            ],
            "site_version" => [
                "display" => "Site Version",
                "loc" => base_url("version"),
                "lastmod" => date("Y-m-d H:i:s", strtotime("-1 year")),
                "priority" => 0.5,
                "changefreq" => "yearly",
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
                "priority" => 0.5,
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

}
