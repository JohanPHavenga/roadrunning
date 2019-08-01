<?php

class MY_Controller extends CI_Controller {

    public $data_to_views = [];
    public $header_url = "/templates/header";
    public $footer_url = "/templates/footer";

    function __construct() {
        parent::__construct();
        // doen checks en set session vars
        $this->data_to_views['user'] = $this->check_login();
        $this->data_to_views['province_dropdown'] = $this->check_province_dropdown();
        // make ini file content available 
        $this->ini_array = parse_ini_file("server_config.ini", true);
    }

    // ==============================================================================================
    // SESSION CHECKS
    // ==============================================================================================
    function check_login() {
        // check of user ingelog is. set view variable to user
        if (isset($_SESSION['user']['logged_in'])) {
            return $_SESSION['user'];
        } else {
            $user['user']['logged_in'] = false;
            return $user;
        }
    }

    function check_province_dropdown() {
        // check of province dropdown in session is
        if (!$this->session->has_userdata('province_dropdown')) {
            $this->load->model('province_model');
            $this->session->set_userdata("province_dropdown", $this->province_model->get_province_dropdown_data());
        }
        return $this->session->province_dropdown;
    }
    
    
    // ==============================================================================================
    // CENTRAL MAIL FUNCTIONS
    // ==============================================================================================
    public function set_email($data) {
        // THIS FUNCTION ONLY TAKES EMAIL FIELDS AND ADD THEM TO THE EMAIL QUE TABLE
        // load emailque_model
        $this->load->model('emailque_model');
        $required_fields=['to','subject','body'];
        if (array_keys_exists($required_fields,$data)) {
            if (isset($data['from'])) { $from=$data['from']; } else { $from=$this->ini_array['email']['from_address']; }
            if (isset($data['from_name'])) { $from_name=$data['from_name']; } else { $from_name=$this->ini_array['email']['from_name']; }
            $emailque_data = array(
                    'emailque_subject' => $data['subject'],
                    'emailque_to_address' => $data['to'],
                    'emailque_body' => $data['body'],
                    'emailque_status' => 5,
                    'emailque_from_address' => $from,
                    'emailque_from_name' => $from_name,
                    'emailque_bcc_address' => $this->ini_array['email']['bcc_address'],
                );
            if ($data['to_name']) { $emailque_data['emailque_to_name']=$data['to_name']; }
            $params=[
                "action"=>"add",
                "data"=>$emailque_data,
                "id"=>false,
            ];
            return $this->emailque_model->set_emailque($params);
        
        } else {
            die("Missing required fields to send email: MY_Controller->send_mail");
        }
    }

}
