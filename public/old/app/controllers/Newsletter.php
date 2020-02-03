<?php

class Newsletter extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
        $this->data_to_header['section'] = "newsletter";
    }

    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->view($method, $params);
        }
    }

    public function view() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->data_to_header['title'] = "Newsletter Subsciption";

        // set validation rules
        $this->form_validation->set_rules('dname', 'Name', 'required', 'Please enter your name');
        $this->form_validation->set_rules('dsurname', 'Surname', 'required', 'Please enter your last name');
        $this->form_validation->set_rules('demail', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');

        $this->form_validation->set_message('required', 'Please complete the <b>{field}</b> field');
//        wts($this->input->post());
        // set title bar
        $this->data_to_header["title_bar"] = $this->render_topbar_html(
                ["crumbs" => ["Newsletter Subsciption" => "/newsletter", "Home" => "/"],
        ]);

        $this->data_to_footer['scripts_to_load'] = array(
            "https://www.google.com/recaptcha/api.js"
        );

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['form_data'] = $_POST;

            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view('newsletter/view', $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $user_info = [
                "user_email" => $this->input->post('demail'),
                "user_name" => $this->input->post('dname'),
                "user_surname" => $this->input->post('dsurname'),
            ];
            $success = $this->subscribe_user($user_info, "newsletter", 0);

            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view('newsletter/view', $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        }
    }

}
