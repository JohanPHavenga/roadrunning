<?php

class Login extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function logout($confirm=false) {
        if ($confirm != "confirm") {
            $this->session->sess_destroy();
            redirect("/logout/confirm");
        } else {
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view('login/logout', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        }
    }


    public function userlogin() {
        $this->load->model('user_model');
        $this->load->model('role_model');
        $this->data_to_views['page_title'] = "User Login";
        $this->data_to_views['form_url'] = '/login/userlogin/submit';
        $this->data_to_views['error_url'] = '/login/userlogin';
        $this->data_to_views['success_url'] = '/';

        // validation rules
        $this->form_validation->set_rules('user_email', 'Email', 'required', ["required" => "Enter your email address to log in"]);
        $this->form_validation->set_rules('user_password', 'Password', 'required', ["required" => "Please enter your password"]);

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view('login/userlogin', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {

            $check_login = $this->user_model->check_login($this->input->post('user_email'),$this->input->post('user_password'));

            if ($check_login) {
                $this->session->set_userdata("user", $check_login);
                $_SESSION['user']['logged_in'] = true;
                $_SESSION['user']['role_list'] = $this->role_model->get_role_list_per_user($check_login['user_id']);
                $this->session->set_flashdata([
                    'alert' => "Login successfull",
                    'status' => "success",
                ]);

                redirect($this->data_to_views['success_url']);
            } else {
                $this->session->set_flashdata([
                    'alert' => "Login Failed",
                    'status' => "danger",
                ]);

                redirect($this->data_to_views['error_url']);
            }

            die("Login failure");
        }
    }

}
