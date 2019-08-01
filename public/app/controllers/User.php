<?php

class User extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function is_password_strong($password) {
        if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password)) {
            return TRUE;
        }
        return FALSE;
    }

    private function hash_pass($password) {
        if ($password) {
            return sha1($password . "37");
        } else {
            return NULL;
        }
    }

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
                    'is_unique' => 'This %s is already in use. Please use the forget password link below to reset your password.'
                )
        );
        $this->form_validation->set_rules('user_password', 'Password', 'trim|required',
//        $this->form_validation->set_rules('user_password', 'Password', 'trim|required|min_length[8]|max_length[32]|callback_is_password_strong', 
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
                        $value = $this->hash_pass($value);
                        break;
                    case "user_password_conf":
                        continue 2;
                        break;
                }
                $user_data[$field] = $value;
            }
            // add guid
            $user_data['user_confirm_guid']=md5(uniqid(rand(), true));
            // set params for model call
            $params = [
                "action" => "add",
                "user_data" => $user_data,
                "role_arr" => [2],
            ];
            wts($params);
            die();
            $user_id=$this->user_model->set_user($params);

            if ($user_id) {
                $mail_sent=$this->send_confirmation_email($user_data);
                $this->data_to_views['conf_type'] = "register";
                $this->data_to_views['email'] = $this->input->post('user_email');
                $this->load->view($this->header_url, $this->data_to_views);
                $this->load->view('user/confirmation', $this->data_to_views);
                $this->load->view($this->footer_url, $this->data_to_views);
            } else {
                die("User registration failed: User:register");
            }
        }
    }
    
    private function send_confirmation_email($user_data) {
        // test email
        $data = [
            "to" => $user_data['user_email'],
            "body" => "test email",
            "subject" => "Registration Confirmation",
        ];
        $this->set_email($data);
        return true;
    }
    
    
    

    public function create($action, $id = 0) {
        // additional models
        $this->load->model('club_model');
        $this->load->model('role_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "User Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

//        $this->data_to_view['js_to_load']=array("select2.js");
//        $this->data_to_view['js_script_to_load']='$(".autocomplete").select2({minimumInputLength: 2});';
//        $this->data_to_view['css_to_load']=array("select2.css","select2-bootstrap.css");

        $this->data_to_view['club_dropdown'] = $this->club_model->get_club_dropdown();
        $this->data_to_view['role_dropdown'] = $this->role_model->get_role_dropdown();

        if ($action == "edit") {
            $this->data_to_view['user_detail'] = $this->user_model->get_user_detail($id);
            $this->data_to_view['user_detail']['role_id'] = $this->role_model->get_role_list_per_user($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        }
        // set default sponsor
        if (empty($this->data_to_view['user_detail']['club_id'])) {
            $this->data_to_view['user_detail']['club_id'] = 8;
        }

        // set validation rules
        $this->form_validation->set_rules('user_name', 'Name', 'required');
        $this->form_validation->set_rules('user_surname', 'Surame', 'required');
        $this->form_validation->set_rules('user_email', 'Email', 'valid_email');
        $this->form_validation->set_rules('user_contact', 'Phone', 'numeric');
        $this->form_validation->set_rules('club_id', 'Club', 'required|numeric|greater_than[0]', ["greater_than" => "Please select a club for the user"]);
        $this->form_validation->set_rules('role_id[]', 'Role', 'required');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            if ($action == "add") {
                $this->data_to_view['user_detail']['role_id'][] = 3;
            }
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $id = $this->user_model->set_user($action, $id);
            if ($id) {
                $alert = "User has been updated";
                $status = "success";
            } else {
                $alert = "Error committing to the database";
                $status = "danger";
            }

            $this->session->set_flashdata([
                'alert' => $alert,
                'status' => $status,
            ]);

            // save_only takes you back to the edit page.
            if (array_key_exists("save_only", $_POST)) {
                $this->return_url = base_url("admin/user/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }

    public function delete($user_id = 0) {

        if (($user_id == 0) AND ( !is_int($user_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $club_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get user detail for nice delete message
        $user_detail = $this->user_model->get_user_detail($user_id);
        // delete record
        $db_del = $this->user_model->remove_user($user_id);

        if ($db_del) {
            $msg = "User has successfully been deleted: " . $user_detail['user_name'] . " " . $user_detail['user_surname'];
            $status = "success";
        } else {
            $msg = "Error in deleting the record:'.$user_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

    public function delete_old($confirm = false) {

        $id = $this->encryption->decrypt($this->input->post('user_id'));

        if ($id == 0) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        if ($confirm == 'confirm') {
            $db_del = $this->user_model->remove_user($id);
            if ($db_del) {
                $msg = "Event has been deleted";
                $status = "success";
            } else {
                $msg = "Error committing to the database ID:'.$id";
                $status = "danger";
            }

            $this->session->set_flashdata('alert', $msg);
            $this->session->set_flashdata('status', $status);
            redirect($this->return_url);
        } else {
            $this->session->set_flashdata('alert', 'Cannot delete record');
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }
    }

    public function export() {
        $this->load->dbutil();
        $this->load->helper('download');
        /* get the object   */
        $export = $this->user_model->export();
        /*  pass it to db utility function  */
        $new_report = $this->dbutil->csv_from_result($export);
        /*  Force download the file */
        force_download('users.csv', $new_report);
        /*  Done    */
    }

    public function profile() {
        // load helpers / libraries
        $this->load->library('table');

        $this->data_to_view['title'] = "User Profile";


        // load view
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view('/admin/user/profile', $this->data_to_view);
        $this->load->view($this->footer_url);
    }

}
