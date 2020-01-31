<?php

class Login extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function logout($confirm = false) {
        if ($confirm != "confirm") {
            $this->session->unset_userdata('user');
            $this->session->set_flashdata([
                'alert' => "Buckle your belt Dorothy, cause Kansas is going bye-bye. Also, you have been succesfully logged out of roadrunning.co.za",
                'status' => "success"
            ]);
            redirect("/logout/confirm");
        } else {
            $this->data_to_views['page_title'] = "Logout";
            $this->data_to_views['meta_description'] = "Logged out of RoadRunning.co.za";
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view($this->notice_url, $this->data_to_views);
            $this->load->view('login/logout', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        }
    }

    public function destroy($confirm = false) {
        // testing function
        $this->session->sess_destroy();
        delete_cookie("session_token");
        delete_cookie('region_selection');
        redirect("/logout/confirm");
    }

    public function userlogin() {
        $this->load->model('user_model');
        $this->load->model('role_model');
        $this->load->model('history_model');
        $this->load->model('region_model');
        $this->data_to_views['page_title'] = "Login";
        $this->data_to_views['meta_description'] = "Log into RoadRunning.co.za";
        $this->data_to_views['form_url'] = base_url('login/userlogin/submit');
        $this->data_to_views['error_url'] = base_url('login/userlogin');
        $this->data_to_views['success_url'] = base_url();

        if ($this->session->flashdata('email') != null) {
            $this->data_to_views['reset_password_url'] = base_url('forgot-password/?email=' . $this->session->flashdata('email'));
            $this->data_to_views['register_url'] = base_url('register/?email=' . $this->session->flashdata('email'));
        } else {
            $this->data_to_views['reset_password_url'] = base_url('forgot-password');
            $this->data_to_views['register_url'] = base_url('register');
        }

        // validation rules
        $this->form_validation->set_rules('user_email', 'Email', 'required|valid_email',
                [
                    "required" => "Enter your email address to log in",
                    "valid_email" => "Please enter a valid email address",
                ]
        );
        $this->form_validation->set_rules('user_password', 'Password', 'required', ["required" => "Please enter your password"]);

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view($this->notice_url, $this->data_to_views);
            $this->load->view('login/userlogin', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            $this->session->set_flashdata(['email' => $this->input->post('user_email'),]);
            // check die login credentials. If fail, give nice error message
            $check_login = $this->user_model->check_credentials($this->input->post('user_email'), $this->input->post('user_password'));
            if ($check_login) {
                // check of email address confirmed is. Anders, gee nice error message
                $is_confirmed = $this->user_model->check_user_is_confirmed($check_login['user_id']);
                if ($is_confirmed) {
                    $this->session->set_userdata("user", $check_login);
                    $_SESSION['user']['logged_in'] = true;
                    $_SESSION['user']['role_list'] = $this->role_model->get_role_list_per_user($check_login['user_id']);
                    $this->session->set_flashdata([
                        'alert' => "Login successfull",
                        'status' => "success",
                    ]);
                    // update history data vir user ID
                    $history_data = ["user_id" => $check_login['user_id']];
                    $this->history_model->update_history_field($history_data, get_cookie('session_token'));
                    // update user_region table
                    if ($this->session->userdata("region_selection")) {
                        $this->region_model->set_user_region($check_login['user_id'], $this->session->region_selection);
                    } else {
                        $this->session->set_userdata("region_selection", $this->region_model->get_user_region($check_login['user_id']));
                    }
                    redirect($this->data_to_views['success_url']);
                } else {
                    $this->session->set_flashdata([
                        'alert' => "<b>Login failed.</b> Seems your email address has not been confirmed yet. Please <a href='" . base_url('forgot-password?email=' . $this->input->post('user_email')) . "'>reset your password</a>.",
                        'status' => "warning",
                    ]);

                    redirect($this->data_to_views['error_url']);
                }
            } else {
                $this->session->set_flashdata([
                    'alert' => "<b>Login failed.</b> Sorry, either your username or password was incorrect. Please try again.",
                    'status' => "danger",
                ]);

                redirect($this->data_to_views['error_url']);
            }
            die("Login failure");
        }
    }

}
