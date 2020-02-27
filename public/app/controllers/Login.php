<?php

class Login extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('role_model');
        $this->load->model('history_model');
        $this->load->model('region_model');
    }

    public function logout($confirm = false) {
        if ($confirm != "confirm") {
            $this->session->unset_userdata('user');
            $this->session->set_flashdata([
                'alert' => "Buckle your seat belt Dorothy, cause Kansas is going bye-bye. Also, you have been succesfully logged out of roadrunning.co.za",
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

    public function userlogin($test = false) {
        $this->data_to_views['page_title'] = "Login";
        $this->data_to_views['meta_description'] = "Log into RoadRunning.co.za";
        $this->data_to_views['form_url'] = base_url('login/userlogin/submit');
        $this->data_to_views['error_url'] = base_url('login/userlogin');
        $this->data_to_views['success_url'] = base_url('user/profile');

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
            if ($test) {
                $this->data_to_views['show_social_login'] = true;
            }
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
                    $this->log_in_user($check_login);
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

    // ================================================================================================
    //  Actual LOGIN
    // ================================================================================================

    public function log_in_user($user_row) {
        $this->session->set_userdata("user", $user_row);
        $_SESSION['user']['logged_in'] = true;
        $_SESSION['user']['role_list'] = $this->role_model->get_role_list_per_user($user_row['user_id']);

        // update history data vir user ID
        $history_data = ["user_id" => $user_row['user_id']];
        $this->history_model->update_history_field($history_data, get_cookie('session_token'));
        // update user_region table
        if ($this->session->userdata("region_selection")) {
            $this->region_model->set_user_region($user_row['user_id'], $this->session->region_selection);
        } else {
            $this->session->set_userdata("region_selection", $this->region_model->get_user_region($user_row['user_id']));
        }

        $this->session->set_flashdata([
            'alert' => "Welcome, to the real world. Your have successfully logged in <b>" . $user_row['user_name'] . "</b>",
            'status' => "success",
        ]);
        redirect(base_url('user/profile'));
    }

    // ================================================================================================
    //  GOOGLE LOGIN
    // ================================================================================================

    function glogin() {
        //Create Client Request to access Google API        
        $client = new Google_Client();
        $client->setApplicationName("RoadRunningZA");
        $client->setClientId($_SESSION['web_data']['google']['client_id']);
        $client->setClientSecret($_SESSION['web_data']['google']['client_secret']);
        $client->setRedirectUri(base_url('login/gcallback'));
        $client->addScope("email");
        $client->addScope("profile");

        //Send Client Request
        $objOAuthService = new Google_Service_Oauth2($client);

        $authUrl = $client->createAuthUrl();

        header('Location: ' . $authUrl);
    }

    function gcallback() {
        //Create Client Request to access Google API
        $client = new Google_Client();
        $client->setApplicationName("RoadRunningZA");
        $client->setClientId($_SESSION['web_data']['google']['client_id']);
        $client->setClientSecret($_SESSION['web_data']['google']['client_secret']);
        $client->setRedirectUri(base_url('login/gcallback'));
        $client->addScope("email");
        $client->addScope("profile");

        //Send Client Request
        $service = new Google_Service_Oauth2($client);

        $client->authenticate($_GET['code']);
        $_SESSION['access_token'] = $client->getAccessToken();

        // User information retrieval starts..............................
        $user = $service->userinfo->get(); //get user info 
        // check if user already exists in DB. Else create
        $user_id = $this->user_model->exists($user->email);
        if (!$user_id) {
            $user_data = [
                "user_name" => "TeST",
                "user_surname" => "TeST",
                "user_email" => $user->email,
            ];
            $role_arr = [2];
            $params = [
                "action" => "add",
                "user_data" => $user_data,
                "role_arr" => $role_arr,
            ];
            $user_id = $this->user_model->set_user($params);
        } else {
            $role_arr = $this->role_model->get_role_list_per_user($user_id);
        }
        // get user data from DB
        $user_data = $this->user_model->get_user_detail($user_id);

        // unset some data
        unset($user_data['user_password']);
        unset($user_data['created_date']);
        unset($user_data['club_id']);
        unset($user_data['club_name']);

        // add new data 
        $user_data['updated_date'] = fdateLong();
        // ADD GOOGLE DATA HERE
        $user_data['user_name'] = $user->givenName;
        $user_data['user_surname'] = $user->familyName;
        $user_data['user_gender'] = $user->gender;
        $user_data['user_locale'] = $user->locale;
        $user_data['user_picture'] = $user->picture;
        $user_data['user_link'] = $user->link;

        // set user again
        $params = [
            "action" => "edit",
            "user_data" => $user_data,
            "role_arr" => $role_arr,
        ];
        $this->user_model->set_user($params);
        $this->log_in_user($user_data);
    }

    // ================================================================================================
    //  FACEBOOK LOGIN
    // ================================================================================================

    function fblogin() {
//        ini_set('display_errors', 1);
        $fb = new Facebook\Facebook([
            'app_id' => $_SESSION['web_data']['facebook']['app_id'],
            'app_secret' => $_SESSION['web_data']['facebook']['app_secret'],
            'default_graph_version' => 'v6.0',
        ]);

        $helper = $fb->getRedirectLoginHelper();

//        $permissions = ['email', 'user_location', 'user_birthday', 'publish_actions'];
        $permissions = ['email'];
// For more permissions like user location etc you need to send your application for review

        $loginUrl = $helper->getLoginUrl(base_url('login/fbcallback'), $permissions);

        header("location: " . $loginUrl);
    }

    function fbcallback() {

        $fb = new Facebook\Facebook([
            'app_id' => $_SESSION['web_data']['facebook']['app_id'],
            'app_secret' => $_SESSION['web_data']['facebook']['app_secret'],
            'default_graph_version' => 'v6.0',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {

            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error  
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues  
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }


        try {
            // Get the Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $fb->get('/me?fields=id,name,email,first_name,last_name,birthday,location,gender', $accessToken);
            // print_r($response);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'ERROR: Graph ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'ERROR: validation fails ' . $e->getMessage();
            exit;
        }

        // User Information Retrival begins................................................
        $me = $response->getGraphUser();

//        $location = $me->getProperty('location');
//        echo "Full Name: " . $me->getProperty('name') . "<br>";
//        echo "First Name: " . $me->getProperty('first_name') . "<br>";
//        echo "Last Name: " . $me->getProperty('last_name') . "<br>";
//        echo "Gender: " . $me->getProperty('gender') . "<br>";
//        echo "Email: " . $me->getProperty('email') . "<br>";
//        echo "location: " . $location['name'] . "<br>";
//        echo "Birthday: " . $me->getProperty('birthday')->format('d/m/Y') . "<br>";
//        echo "Facebook ID: <a href='https://www.facebook.com/" . $me->getProperty('id') . "' target='_blank'>" . $me->getProperty('id') . "</a>" . "<br>";
//        $profileid = $me->getProperty('id');
//        echo "</br><img src='http://graph.facebook.com/$profileid/picture?type=normal'> ";
//        echo "</br></br>Access Token : </br>" . $accessToken;        
//        die();

        $user_id = $this->user_model->exists($me->getProperty('email'));
        if (!$user_id) {
            $user_data = [
                "user_name" => "TeST",
                "user_surname" => "TeST",
                "user_email" => $me->getProperty('email'),
            ];
            $role_arr = [2];
            $params = [
                "action" => "add",
                "user_data" => $user_data,
                "role_arr" => $role_arr,
            ];
            $user_id = $this->user_model->set_user($params);
        } else {
            $role_arr = $this->role_model->get_role_list_per_user($user_id);
        }
        // get user data from DB
        $user_data = $this->user_model->get_user_detail($user_id);

        // unset some data
        unset($user_data['user_password']);
        unset($user_data['created_date']);
        unset($user_data['club_id']);
        unset($user_data['club_name']);

        // add new data 
        $user_data['updated_date'] = fdateLong();
        // ADD GOOGLE DATA HERE
        $user_data['user_name'] = $me->getProperty('first_name');
        $user_data['user_surname'] = $me->getProperty('last_name');
        $user_data['user_gender'] = $me->getProperty('gender');
        $user_data['user_locale'] = $me->getProperty('location');

        // set user again
        $params = [
            "action" => "edit",
            "user_data" => $user_data,
            "role_arr" => $role_arr,
        ];
        $this->user_model->set_user($params);
        $this->log_in_user($user_data);
    }

}
