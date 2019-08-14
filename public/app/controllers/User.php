<?php

class User extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    // CALL BACK FUNCTIONS
    public function is_password_strong($password) {
        if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password)) {
            return TRUE;
        }
        return FALSE;
    }

    public function email_exists($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($this->user_model->check_email($email) == 1) {
                return TRUE;
            }
        }
        return FALSE;
    }

    // REGISTER 
    public function register() {
        $this->load->model('user_model');
        $this->load->model('role_model');
        $this->data_to_views['page_title'] = "Register";
        $this->data_to_views['form_url'] = '/register';
        $this->data_to_views['error_url'] = '/register';

        // validation rules
        $this->form_validation->set_rules('user_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('user_surname', 'Surname', 'trim|required');
        $this->form_validation->set_rules('user_email', 'email address', 'trim|required|valid_email|is_unique[users.user_email]',
                array(
                    'required' => 'You have not provided an %s.',
                    'is_unique' => 'This %s is already in use. Please use the <a href="/forgot-password">forgot password</a> workflow reset your password.'
                )
        );
        $this->form_validation->set_rules('user_password', 'Password', 'trim|required|min_length[8]|max_length[32]|callback_is_password_strong',
                array(
                    "is_password_strong" => "Password should be at least 8 characters in length and should include at least one upper case letter and one number",
                )
        );
        $this->form_validation->set_rules('user_password_conf', 'Password Confirmation', 'trim|required|matches[user_password]');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view('user/register', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            // set user_data from post
            foreach ($this->input->post() as $field => $value) {
                switch ($field) {
                    case "user_password":
                        $value = hash_pass($value);
                        break;
                    case "user_password_conf":
                        continue 2;
                }
                $user_data[$field] = $value;
            }
            // add guid
            $user_data['user_confirm_guid'] = md5(uniqid(rand(), true));
            $user_data['user_guid_expire'] = date("Y-m-d H:i:s", strtotime($this->ini_array['register']['guid_valid']));
            // set params for model call
            $params = [
                "action" => "add",
                "user_data" => $user_data,
                "role_arr" => [2],
            ];
            $user_id = $this->user_model->set_user($params);
            if ($user_id) {
                $mail_id = $this->send_confirmation_email($user_data);
                $this->data_to_views['conf_type'] = "register";
                $this->data_to_views['mail_id'] = $mail_id;
                $this->data_to_views['email'] = $this->input->post('user_email');
                $this->load->view($this->header_url, $this->data_to_views);
                $this->load->view('user/confirmation', $this->data_to_views);
                $this->load->view($this->footer_url, $this->data_to_views);
            } else {
                die("User registration failed: User:register");
            }
        }
    }

    // PASSWORD RESET 
    public function forgot_password() {
        $this->data_to_views['page_title'] = "Forgot Password";
        $this->data_to_views['form_url'] = '/forgot-password';
        $this->data_to_views['error_url'] = '/forgot-password';

        // validation rules
        $this->form_validation->set_rules('user_email', 'email address', 'trim|required|valid_email|callback_email_exists',
                array(
                    'required' => 'You have not provided an %s.',
                    'email_exists' => 'The email address you provided was not found. Please try again'
                )
        );

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view('user/forgot_password', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            $user_id = $this->user_model->get_user_id($this->input->post("user_email"));
            $user_data['user_email'] = $this->input->post("user_email");
            $user_data['user_password'] = "";
            $user_data['user_isconfirmed'] = false;
            $user_data['user_confirm_guid'] = md5(uniqid(rand(), true));
            $user_data['user_guid_expire'] = date("Y-m-d H:i:s", strtotime($this->ini_array['register']['guid_valid']));

            $update_user = $this->user_model->update_user_field($user_data, $user_id);
            if ($update_user) {
                $mail_id = $this->send_confirmation_email($user_data, "forgot_password");
                $this->data_to_views['conf_type'] = "forgot_password";
                $this->data_to_views['mail_id'] = $mail_id;
                $this->data_to_views['email'] = $this->input->post('user_email');
                $this->load->view($this->header_url, $this->data_to_views);
                $this->load->view('user/confirmation', $this->data_to_views);
                $this->load->view($this->footer_url, $this->data_to_views);
            } else {
                die("User update failed: User:forgot_passsword");
            }
        }
    }

    public function reset_password($guid = null) {
        if (is_null($guid)) {
            redirect("/404");
            die();
        }
        $this->data_to_views['page_title'] = "Reset Password";
        $this->data_to_views['form_url'] = '/user/reset_password/' . $guid;
        $this->data_to_views['error_url'] = '/user/reset_password/' . $guid;
        $user_id = $this->user_model->check_user_guid($guid);
        if ($user_id) {

            $this->form_validation->set_rules('user_password', 'Password', 'trim|required|min_length[8]|max_length[32]|callback_is_password_strong',
                    array(
                        "is_password_strong" => "Password should be at least 8 characters in length and should include at least one upper case letter and one number",
                    )
            );
            $this->form_validation->set_rules('user_password_conf', 'Password Confirmation', 'trim|required|matches[user_password]');
            // load correct view
            if ($this->form_validation->run() === FALSE) {
                $this->load->view($this->header_url, $this->data_to_views);
                $this->load->view('user/reset_password', $this->data_to_views);
                $this->load->view($this->footer_url, $this->data_to_views);
            } else {
                foreach ($this->input->post() as $field => $value) {
                    switch ($field) {
                        case "user_password":
                            $value = hash_pass($value);
                            break;
                        case "user_password_conf":
                            continue 2;
                    }
                    $user_data[$field] = $value;
                }
                $user_data['user_isconfirmed'] = true;
                $user_data['user_confirm_guid'] = "";

                $update_user = $this->user_model->update_user_field($user_data, $user_id);
                if ($update_user) {
                    $this->data_to_views['conf_type'] = "reset_password";
                    $this->load->view($this->header_url, $this->data_to_views);
                    $this->load->view('user/confirmation', $this->data_to_views);
                    $this->load->view($this->footer_url, $this->data_to_views);
                } else {
                    die("User update failed: User:reset_passsword");
                }
            }
        } else {
            $this->data_to_views['conf_type'] = "guid_not_found_pass";
        }

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('user/confirmation', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    // CONFIRM EMAIL ADDRESS
    public function confirm_email($guid = null) {
        if (is_null($guid)) {
            redirect("/404");
            die();
        }
        $user_id = $this->user_model->check_user_guid($guid);

        if ($user_id) {
            $user_data = [
                "user_confirm_guid" => "",
                "user_isconfirmed" => 1
            ];
            $user_set = $this->user_model->update_user_field($user_data, $user_id);
            $this->data_to_views['conf_type'] = "confirm_email";
        } else {
            $this->data_to_views['conf_type'] = "guid_not_found";
        }

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('user/confirmation', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    // SEND CONFIRMATION EMAIL
    private function send_confirmation_email($user_data, $conf_type = "register") {
        // test email
        switch ($conf_type) {
            case "register":
                $url = base_url("user/confirm_email/" . $user_data['user_confirm_guid']);
                $data = [
                    "to" => $user_data['user_email'],
                    "body" => "<h2>Welcome</h2><p>Hi " . $user_data['user_name'] . "<br>Please click on the link below to confirm your email address.</p><p><a href='$url'>$url</a></p>",
                    "subject" => "Registration Confirmation",
                ];
                break;
            case "forgot_password":
                $url = base_url("user/reset_password/" . $user_data['user_confirm_guid']);
                $data = [
                    "to" => $user_data['user_email'],
                    "body" => "<h2>Password Reset</h2><p>You have requested a password reset on your account.<br>Please click on the link below to set your new password.</p><p><a href='$url'>$url</a></p>",
                    "subject" => "Password Reset",
                ];
                break;
        }

        return $this->set_email($data);
    }

    // VIEW PROFILE
    public function profile() {
        // load helpers / libraries
        $this->load->library('table');
        $this->data_to_view['title'] = "User Profile";

        // load view
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('user/profile', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

}
