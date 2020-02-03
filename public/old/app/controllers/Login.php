<?php

class Login extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    // check if method exists, if not calls "view" method
    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->userlogin($method, $params = array());
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect("/");
    }

    public function userlogin() {
        $this->data_to_header['title'] = "User Login";
        $this->data_to_header['meta_robots'] = "noindex, nofollow";
        $this->data_to_view['form_url'] = '/login/userlogin/submit';
        $this->data_to_view['error_url'] = '/login';
        $this->data_to_view['success_url'] = '/';

        // set validation rules
        $this->form_validation->set_rules('user_username', 'Username', 'required');
        $this->form_validation->set_rules('user_password', 'Password', 'required');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view('login/userlogin', $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {

            $check_login = $this->user_model->check_login();

            if ($check_login) {
                $this->session->set_userdata("user_logged_in", true);
                $this->session->set_userdata("user", $check_login);
                $this->session->set_flashdata([
                    'alert' => "Login successfull",
                    'status' => "success",
                ]);

                redirect($this->data_to_view['success_url']);
            } else {
                $this->session->set_flashdata([
                    'alert' => "Login Failed",
                    'status' => "danger",
                ]);

                redirect($this->data_to_view['error_url']);
            }

            die("Login failure");
        }
    }

    public function admin($submit = false) {
        $this->data_to_header['title'] = "Admin Login";
        $this->data_to_header['meta_robots'] = "noindex, nofollow";
        $this->data_to_view['form_url'] = '/login/admin/submit';
        $this->data_to_view['error_url'] = '/login/admin';
        $this->data_to_view['success_url'] = '/admin';

        // set validation rules
        $this->form_validation->set_rules('user_username', 'Username', 'required');
        $this->form_validation->set_rules('user_password', 'Password', 'required');

        $crumbs = [
            "Admin Login" => "",
            "Home" => "/",
        ];
        // set title bar
        $this->data_to_view["title_bar"] = $this->render_topbar_html(
                [
                    "title" => $this->data_to_header['title'],
                    "crumbs" => $crumbs,
        ]);

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view('login/adminlogin', $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $check_login = $this->user_model->check_login("admin");
            
            if ($check_login) {

                $this->session->set_userdata("admin_logged_in", true);
                $this->session->set_userdata("admin_user", $check_login);

                $this->session->set_flashdata([
                    'alert' => "Login successfull",
                    'status' => "success",
                ]);

                redirect($this->data_to_view['success_url']);
                exit();
            } else {
                $this->session->set_flashdata([
                    'alert' => "Login Failed",
                    'status' => "danger",
                ]);

                redirect($this->data_to_view['error_url']);
                exit();
            }

            die("Login failure");
        }
    }

}
