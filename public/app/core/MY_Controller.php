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
        //$this->data_to_views['province_dropdown'] = $this->check_province_dropdown();
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

    private function check_province_dropdown() {
        // check of province dropdown in session is
        if (!$this->session->has_userdata('province_dropdown')) {
            $this->load->model('province_model');
            $this->session->set_userdata("province_dropdown", $this->province_model->get_province_dropdown_data());
        }
        return $this->session->province_dropdown;
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

            // chcek if segment 1 in uri not in exclusion list
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

    private function segment_exclusion_list($segment_1) {
        if (in_array($segment_1, $this->ini_array['history']['exclusion'])) {
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
    // SITEMAP
    // ==============================================================================================

    public function get_static_pages() {
        return [
            "home" => "",
            "about" => "about",
            "contact" => "contact",
            "login" => "login",
            "sitemap" => "sitemap",
            "terms & conditions" => "terms-conditions",
        ];
    }

    // ==============================================================================================
    // CENTRAL FUNCTIONS
    // ==============================================================================================

    public function chronologise_data($data_arr, $date_field) {
        foreach ($data_arr as $id => $row) {
            $year = date("Y", strtotime($row[$date_field]));
            $month = date("F", strtotime($row[$date_field]));
            $day = date("d", strtotime($row[$date_field]));

            $return_data[$year][$month][$day][$id] = $row;
        }
        return $return_data;
    }

}
