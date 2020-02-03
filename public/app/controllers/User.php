<?php

class User extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    // VIEW PROFILE
    public function profile() {
        if (empty($this->logged_in_user)) {
            $this->session->set_flashdata([
                'alert' => "You are not currently logged in, or your session has expired. Please use the form below to log in or register",
                'status' => "warning",
                'icon' => "info-circle",
            ]);
            redirect(base_url("login"));
        }
        // load helpers / libraries        
        $this->load->library('table');
        $this->data_to_views['page_title'] = "User Profile";
        $this->data_to_views['meta_description'] = "Information regarding the logged in user";

        // load view
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('user/profile', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    // EDIT PROFILE
    public function edit() {
        if (empty($this->logged_in_user)) {
            $this->session->set_flashdata([
                'alert' => "You are not currently logged in, or your session has expired. Please use the form below to log in or register",
                'status' => "warning",
                'icon' => "info-circle",
            ]);
            redirect(base_url("login"));
        }
        // load helpers / libraries        
        $this->load->library('table');
        $this->data_to_views['page_title'] = "Edit Profile";
        $this->data_to_views['meta_description'] = "Editing information regarding the logged in user";

        $this->data_to_views['form_url'] = '/user/edit';
        $this->data_to_views['error_url'] = '/user/edit';

        $this->data_to_views['scripts_to_load'] = ["https://www.google.com/recaptcha/api.js"];

        // validation rules
        $this->form_validation->set_rules('user_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('user_surname', 'Surname', 'trim|required');
        $this->form_validation->set_rules('user_email', 'email address', 'trim|required|valid_email');
        $this->form_validation->set_rules('user_contact', 'Phone Number', 'trim|min_length[10]|max_length[12]');
//        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view($this->notice_url, $this->data_to_views);
            $this->load->view('user/edit', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            // set user_data from post
            foreach ($this->input->post() as $field => $value) {
                if ($field == "user_contact") {
                    $value = $this->int_phone($value);
                }
                $user_data[$field] = $value;
                $_SESSION['user'][$field] = $value;
            }
//            wts($this->logged_in_user,1);
            $user_data['user_id'] = $this->logged_in_user['user_id'];
            $params = [
                "action" => "edit",
                "user_data" => $user_data,
                "role_arr" => $this->logged_in_user['role_list'],
                "user_id" => $this->logged_in_user['user_id']
            ];
            $user_id = $this->user_model->set_user($params);

            $this->session->set_flashdata([
                'alert' => "Your details has been updated",
                'status' => "success",
                'icon' => "check-circle",
            ]);

            redirect(base_url("user/profile"));
        }
    }

    // RESULTS
    public function my_results() {
        $this->data_to_views['banner_img'] = "run_03";
        $this->data_to_views['banner_pos'] = "15%";
        $this->data_to_views['page_title'] = "My Results";
        $this->data_to_views['meta_description'] = "Dashboard showing a consolidated view of your own results";

        // load view
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('user/my_results', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    // SUBSCRIPTIONS
    public function my_subscriptions() {
        if (empty($this->logged_in_user)) {
            $this->session->set_flashdata([
                'alert' => "You are not currently logged in, or your session has expired. Please use the form below to log in or register",
                'status' => "warning",
                'icon' => "info-circle",
            ]);
            redirect(base_url("login"));
        }
        $this->load->model('usersubscription_model');
        $this->data_to_views['page_title'] = "My Subscriptions";
        $this->data_to_views['meta_description'] = "Listing the subscriptions the logged in user is part of";

        // GET user subsciptions
        $newsletter_subs = $this->usersubscription_model->get_usersubscription_list($this->logged_in_user['user_id'], "newsletter");
        if ($newsletter_subs) {
            foreach ($newsletter_subs as $sub) {
                $sub['unsubscribe_url'] = $this->formulate_unsubscribe_url($this->logged_in_user['user_id'], "newsletter", $sub['linked_id']);
                $this->data_to_views['newsletter_subs'][] = $sub;
            }
        }
//        wts($this->data_to_views['newsletter_subs'], 1);
        $edition_subs = $this->usersubscription_model->get_usersubscription_list($this->logged_in_user['user_id'], "edition");
        if ($edition_subs) {
            foreach ($edition_subs as $sub) {
                $sub['unsubscribe_url'] = $this->formulate_unsubscribe_url($this->logged_in_user['user_id'], "edition", $sub['linked_id']);
                $this->data_to_views['edition_subs'][] = $sub;
            }
        }
//        wts($this->data_to_views['edition_subs'], 1);
        // load view
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('user/my_subscriptions', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    // get unsubscribe URL
    private function formulate_unsubscribe_url($user_id, $linked_to, $linked_id) {
        $crypt = my_encrypt($user_id . "|" . $linked_to . "|" . $linked_id);
        $url = base_url("user/unsubscribe/" . $crypt);
        return $url;
    }

    // CALL BACK FUNCTIONS
    public function is_password_strong($password) {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);

        if (!$uppercase || !$lowercase || !$number || strlen($password) < 8 || strlen($password) > 32) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function email_exists($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($this->user_model->check_email($email) == 1) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function subscribe($type, $slug = false) {
        $this->load->model('edition_model');
        $this->load->helper('email');
        $return_url = base_url();


        switch ($type) {
            case "event":
                // get basic edition_data
                $edition_data = $this->edition_model->get_edition_id_from_slug($slug);
                if (!$edition_data) {
                    $this->session->set_flashdata([
                        'alert' => "Subscription to mailing list was unsuccessful. Please try again",
                        'status' => "warning",
                        'icon' => "info-circle",
                    ]);
                    redirect($return_url);
                } else {
                    $return_url = base_url("event/" . $slug);
                    $this->data_to_views['form_url'] = base_url('user/subscribe/event/' . $slug);
                    $this->data_to_views['cancel_url'] = $return_url;
                    $linked_to_id = $edition_data['edition_id'];
                    $this->data_to_views['page_title'] = "Mailing List Addition";
                    $this->data_to_views['meta_description'] = "Adding user to the mailing list for the " . $edition_data['edition_name'] . " event";
                }
                break;
            case "newsletter":
                $return_url = base_url("newsletter");
                $this->data_to_views['form_url'] = base_url('user/subscribe/newsletter');
                $this->data_to_views['cancel_url'] = $return_url;
                $this->data_to_views['page_title'] = "Newsletter Subscriptions";
                $this->data_to_views['meta_description'] = "User subscription to the monthly newsletter";
                $linked_to_id = 0;
                break;
            default:
                break;
        }

//        wts($slug);
//        wts($type);
//        wts($_POST);
//        wts($edition_data, true);
        // check vir valid email
        if (valid_email($this->input->post("user_email"))) {
            set_cookie("sub_email", $this->input->post("user_email"), 7200);
            $user_id = $this->user_model->get_user_id($this->input->post("user_email"));

            if ($user_id) {
                $user_info = $this->user_model->get_user_name($user_id);
                $success = $this->subscribe_user($user_info, $type, $linked_to_id);
                redirect($return_url);
            } else {
                $this->form_validation->set_rules('user_name', 'Name', 'trim|required');
                $this->form_validation->set_rules('user_surname', 'Surname', 'trim|required');
                $this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email');

                // load correct view
                if ($this->form_validation->run() === FALSE) {
                    $this->load->view($this->header_url, $this->data_to_views);
                    $this->load->view('user/subscribe', $this->data_to_views);
                    $this->load->view($this->footer_url, $this->data_to_views);
                } else {
                    $user_data = [
                        "user_name" => $this->input->post('user_name'),
                        "user_surname" => $this->input->post('user_surname'),
                        "user_email" => $this->input->post('user_email'),
                    ];
                    $success = $this->subscribe_user($user_data, "edition", $linked_to_id);
                    redirect($return_url);
                }
            }
        } else {
            $this->session->set_flashdata([
                'alert' => "You entered an invalid email address when attempting subscribing. Please try again",
                'status' => "danger",
                'icon' => "minus-circle",
            ]);
            redirect($return_url);
        }
    }

    public function unsubscribe($crypt) {
        // get data
        $str = my_decrypt($crypt);
        $data = explode("|", $str);
        $user_id = $data[0];
        $linked_to = $data[1];
        $linked_id = $data[2];
        // load moadels        
        $this->load->model('usersubscription_model');
        // set negative return msg
        $this->session->set_flashdata([
            'alert' => "Subscription not found. Please contact the site administrator.",
            'status' => "danger",
            'icon' => "minus-circle",
        ]);
        // check if the subscription exists
        if ($this->usersubscription_model->exists($user_id, $linked_to, $linked_id)) {
            $remove = $this->usersubscription_model->remove_usersubscription($user_id, $linked_to, $linked_id);
            if ($remove) {
                $this->session->set_flashdata([
                    'alert' => "Subscription successfully removed.",
                    'status' => "success",
                    'icon' => "minus-circle",
                ]);
            }
        }

        redirect(base_url("user/my-subscriptions"));
    }

    // REGISTER 
    public function register() {
        $this->load->model('user_model');
        $this->load->model('role_model');
        $this->data_to_views['page_title'] = "Register";
        $this->data_to_views['meta_description'] = "Register as a new user for roadrunning.co.za";
        $this->data_to_views['form_url'] = '/register';
        $this->data_to_views['error_url'] = '/register';

        // validation rules
        $this->form_validation->set_rules('user_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('user_surname', 'Surname', 'trim|required');
        $this->form_validation->set_rules('user_email', 'email address', 'trim|required|valid_email|is_unique[users.user_email]',
                array(
                    'required' => 'You have not provided an %s.',
                    'is_unique' => 'This %s is already in use. Please <a href="' . base_url('login') . '">login</a> or <a href="' . base_url('forgot-password') . '">reset your password</a> if you have forgotten it.'
                )
        );
        $this->form_validation->set_rules('user_contact', 'Phone Number', 'trim|min_length[10]|alpha_numeric_spaces');
        $this->form_validation->set_rules('user_password', 'Password', 'trim|required|min_length[8]|max_length[32]|callback_is_password_strong',
                array(
                    "is_password_strong" => "Password should be between 8 & 32 characters in length and should include at least one upper case letter and one number",
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
                    case "user_contact":
                        $value = $this->int_phone($value);
                        break;
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
        $this->data_to_views['page_title'] = "Password Reset";
        $this->data_to_views['meta_description'] = "Reset your password for roadrunning.co.za";
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
        $this->data_to_views['page_title'] = "Set New Password";
        $this->data_to_views['meta_description'] = "Setting a new password for roadrunning.co.za";
        $this->data_to_views['form_url'] = '/user/reset_password/' . $guid;
        $this->data_to_views['error_url'] = '/user/reset_password/' . $guid;
        $user_id = $this->user_model->check_user_guid($guid);
        if ($user_id) {

            $this->form_validation->set_rules('user_password', 'Password', 'trim|required|min_length[8]|max_length[32]|callback_is_password_strong',
                    array(
                        "is_password_strong" => "Your password should be <u>between 8 & 32 characters</u> in length, contain <u>one upper case letter</u> and <u>one number</u>",
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

        $this->data_to_views['page_title'] = "Email Confirm";
        $this->data_to_views['meta_description'] = "Confirm your email address";
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
//                    "body" => "<h2>Welcome</h2><p>Hi " . $user_data['user_name'] . "<br>Please click on the link below to confirm your email address.</p><p><a href='$url'>$url</a></p>",
                    "subject" => "Registration on RoadRunning.co.za",
                    "body" => "<h2>Welcome</h2>"
                    . "<p>Hi " . $user_data['user_name']
                    . "<p>Please click on the link below to confirm your email address to complete creating an account on "
                    . "<a href = 'https://www.roadrunning.co.za/' style = 'color:#222222 !important;text-decoration:underline !important;'>RoadRunning.co.za</a>."
                    . "<p style='padding-left: 15px; border-left: 4px solid #ccc;'><b>Click to confirm:</b><br><a href='$url' style = 'color:#222222 !important;text-decoration:underline !important;'>$url</a></p>"
                    . "<p>If this was not you, you can safely ignore this email.</p>",
                    "from" => "no-reply@roadrunning.co.za",
                    "from_name" => "No-Reply@RoadRunning.co.za",
                ];
                break;
            case "forgot_password":
                $url = base_url("user/reset_password/" . $user_data['user_confirm_guid']);
                $data = [
                    "to" => $user_data['user_email'],
                    "subject" => "Password Reset for RoadRunning.co.za",
                    "body" => "<h2>Password Reset</h2>"
                    . "<p>We have received a password reset request on "
                    . "<a href = 'https://www.roadrunning.co.za/' style = 'color:#222222 !important;text-decoration:underline !important;'>RoadRunning.co.za</a> for the email address "
                    . "<b>" . $user_data['user_email'] . "</b>."
                    . "<p>Please click on the link below to confirm this was you, and set a new password:</p>"
                    . "<p style='padding-left: 15px; border-left: 4px solid #ccc;'><b>Click to confirm:</b><br><a href='$url' style = 'color:#222222 !important;text-decoration:underline !important;'>$url</a></p>"
                    . "<p>If this was not you, you can safely ignore this email.</p>",
                    "from" => "no-reply@roadrunning.co.za",
                    "from_name" => "No-Reply@RoadRunning.co.za",
                ];
                break;
        }

        return $this->set_email($data);
    }

    private function int_phone($phone) {
        $phone = trim($phone);
        $phone = str_replace(" ", "", $phone);
        $phone = str_replace("-", "", $phone);
        return preg_replace('/^(?:\+?27|0)?/', '+27', $phone);
    }

}
