<?php

class Usersubscription extends Admin_Controller {

    private $return_url = "/admin/usersubscription";
    private $create_url = "/admin/usersubscription/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/usersubscription_model');
    }

    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->view($params);
        }
    }
    
    private function populate_view_data($usersubdata) {
        $this->load->model('admin/user_model');
        // add better data for view
        foreach ($usersubdata as $id=>$data_arr) {
            foreach ($data_arr as $field=>$data) {
                switch ($field) {
                    case "user_id":
                        $user_data=$this->user_model->get_user_name($data);
                        $usersubdata[$id]['user_name']=$user_data['user_name'];
                        $usersubdata[$id]['user_surname']=$user_data['user_surname'];
                        break;
                }
            }
        }
        return $usersubdata;
    }

    public function view() {
        // load helpers / libraries
        $this->load->library('table');
        
        // TO PUT THIS IN SESSION 
        $this->load->model("admin/edition_model");
        $this->data_to_view["edition_list"]=$this->edition_model->get_edition_list_simple();                
                
        $this->load->model("admin/newsletter_model");
        $this->data_to_view["newsletter_list"]=$this->newsletter_model->get_newsletter_list_simple();
        // =======================

        $usersubdata=$this->usersubscription_model->get_usersubscription_list();
        $this->data_to_view["usersubscription_data"] = $this->populate_view_data($usersubdata);
        
//        wts($this->data_to_view["usersubscription_data"]);
//        die();
        $this->data_to_view['heading'] = ["User ID", "Name", "Linked To Type", "Linked To Name", "Actions"];

        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['title'] = "List of User Subscriptions";

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "User Subscriptions" => "/admin/usersubscription",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add Subscription",
                "icon" => "present",
                "uri" => "usersubscription/create/add",
            ],
        ];

        $this->data_to_view['url'] = $this->url_disect();

        $this->data_to_header['css_to_load'] = array(
            "assets/admin/plugins/datatables/datatables.min.css",
            "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css",
        );

        $this->data_to_footer['js_to_load'] = array(
            "assets/admin/scripts/datatable.js",
            "assets/admin/plugins/datatables/datatables.min.js",
            "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js",
            "assets/admin/plugins/bootstrap-confirmation/bootstrap-confirmation.js",
        );

        $this->data_to_footer['scripts_to_load'] = array(
            "assets/admin/scripts/table-datatables-managed.js",
        );

        // load view
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/admin/usersubscription/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action) {

        // additional models
        $this->load->model('admin/user_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "User Subscription Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        $this->data_to_header['css_to_load'] = array(
            "assets/admin/plugins/typeahead/typeahead.css"
        );

        $this->data_to_footer['js_to_load'] = array(
            "assets/admin/plugins/typeahead/handlebars.min.js",
            "assets/admin/plugins/typeahead/typeahead.bundle.min.js",
        );

        $this->data_to_footer['scripts_to_load'] = array(
            "assets/admin/scripts/autocomplete.js",
            "assets/admin/scripts/linked_to_hide_show.js",
        );

        $this->data_to_view['user_dropdown'] = $this->user_model->get_user_dropdown();
        $this->data_to_view['linked_to_dropdown'] = $this->usersubscription_model->get_linked_to_dropdown(7);
        $this->data_to_view['linked_to_list'] = $this->usersubscription_model->get_linked_to_list(7);
        
        // unset linked to we are not using for now
        $unset_arr=[1,3,4,5,6];
        foreach ($unset_arr as $unset) {
            $name=$this->data_to_view['linked_to_list'][$unset];
            unset($this->data_to_view['linked_to_list'][$unset]);
            unset($this->data_to_view['linked_to_dropdown'][$name]);
        }        

        // dynamically get drop downs using the linked_to_table
        foreach ($this->data_to_view['linked_to_list'] as $linked_to_id => $linked_to_name) {
            $dropdown = $linked_to_name . "_dropdown";
            $model = $linked_to_name . "_model";
            $method = "get_" . $linked_to_name . "_dropdown";

            $this->load->model("admin/".$model);
            $this->data_to_view[$dropdown] = $this->$model->$method();
            $this->data_to_view[$dropdown][0] = "All";                    
        }
        
        $this->form_validation->set_rules('user_id', 'User', 'required|greater_than[0]', ["greater_than" => "Please select a User"]);
        $this->form_validation->set_rules('linked_id', 'Linked ID', 'integer');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            // SET subscription
            $set = $this->usersubscription_model->set_usersubscription($action);            

            if ($set) {
                $alert = "User Subscription has been " . $action . "ed";
                $status = "success";
            } else {
                $alert = "Error committing to the database";
                $status = "danger";
            }

            $this->session->set_flashdata([
                'alert' => $alert,
                'status' => $status,
            ]);

            redirect($this->return_url);
        }
    }

    public function delete($user_id, $linked_to, $linked_id) {


        if ($user_id == 0) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $user_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // delete record
        $db_del = $this->usersubscription_model->remove_usersubscription($user_id, $linked_to, $linked_id);

        if ($db_del) {
            $msg = "User subsciprtion has successfully been deleted: " . $user_id;
            $status = "success";
        } else {
            $msg = "Error in deleting the record:'.$user_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

    function exists($linked_type, $linked_id, $user_id) {
        return $this->usersubscription_model->check_usersubscription_exists($linked_type, $linked_id, $user_id);
    }

}
