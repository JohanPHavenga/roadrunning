<?php

class Userresult extends Admin_Controller {

    private $return_url = "/admin/userresult";
    private $create_url = "/admin/userresult/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/userresult_model');
    }

    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->view($params);
        }
    }

    public function view() {
        // load helpers / libraries
        $this->load->library('table');

        // TO PUT THIS IN SESSION 
        $this->load->model("admin/edition_model");
        $this->data_to_view["edition_list"] = $this->edition_model->get_edition_list_simple();

        $this->load->model("admin/newsletter_model");
        $this->data_to_view["newsletter_list"] = $this->newsletter_model->get_newsletter_list_simple();
        // =======================

        $usersubdata = $this->userresult_model->get_userresult_list();
        $this->data_to_view["userresult_data"] = $this->populate_view_data($usersubdata);

//        wts($this->data_to_view["userresult_data"]);
//        die();
        $this->data_to_view['heading'] = ["User ID", "Name", "Linked To Type", "Linked To Name", "Actions"];

        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['title'] = "List of User Subscriptions";

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "User Subscriptions" => "/admin/userresult",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add Subscription",
                "icon" => "present",
                "uri" => "userresult/create/add",
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
        $this->load->view("/admin/userresult/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action,$result_id) {
        // additional models
        $this->load->model('admin/user_model');
//        $this->load->model('admin/result_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "User Subscription Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action."/".$result_id;

        $this->data_to_header['css_to_load'] = array(
            "assets/admin/plugins/typeahead/typeahead.css"
        );
        $this->data_to_footer['js_to_load'] = array(
            "assets/admin/plugins/typeahead/typeahead.bundle.min.js",
        );
        $this->data_to_footer['scripts_to_load'] = array(
            "assets/admin/scripts/auto_complete_generic.js",
        );

        $this->data_to_view['user_list'] = $this->user_model->get_user_autocomplete_list();
        $this->data_to_view['result_id'] = $result_id;

        $this->form_validation->set_rules('user', 'User', 'required');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $user_id = array_search($this->input->post("user"), $this->data_to_view['user_list']);
            // SET subscription
            $userresult_data = array(
                'user_id' => $user_id,
                'result_id' => $this->input->post('result_id'),
            );
//            wts($userresult_data,1);
            $set = $this->userresult_model->set_userresult($action, $userresult_data);

            if ($set) {
                $alert = "User-Result has been " . $action . "ed";
                $status = "success";
            } else {
                $alert = "Error committing to the database";
                $status = "danger";
            }

            $this->session->set_flashdata([
                'alert' => $alert,
                'status' => $status,
            ]);
            // get race id for redirect
            redirect(base_url("admin/result/create/edit/".$result_id));
        }
    }

    public function delete($user_id, $result_id) {

        $this->return_url = base_url("admin/result/create/edit/" . $result_id);

        if ($user_id == 0) {
            $this->session->set_flashdata('alert', 'Cannot delete record: user#' . $user_id . " | result#" . $result_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // delete record
        $db_del = $this->userresult_model->remove_userresult($user_id, $result_id);

        if ($db_del) {
            $msg = "User-result link has successfully been deleted: user#" . $user_id . " | result#" . $result_id;
            $status = "success";
        } else {
            $msg = "Error in deleting the record: user#" . $user_id . " | result#" . $result_id;
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

    function exists($linked_type, $linked_id, $user_id) {
        return $this->userresult_model->check_userresult_exists($linked_type, $linked_id, $user_id);
    }

}
