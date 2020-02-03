<?php

class Venue extends Admin_Controller {

    private $return_url = "/admin/venue";
    private $create_url = "/admin/venue/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('venue_model');
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

        $this->data_to_view["venue_data"] = $this->venue_model->get_venue_list();
        $this->data_to_view['heading'] = ["ID", "Venue", "Province", "Status", "Actions"];

        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['title'] = "List of Venues";
        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Venue" => "/admin/venue",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add Venue",
                "icon" => "pin",
                "uri" => "venue/create/add",
            ],
        ];

        $this->data_to_view['url'] = $this->url_disect();

        $this->data_to_header['css_to_load'] = array(
            "plugins/datatables/datatables.min.css",
            "plugins/datatables/plugins/bootstrap/datatables.bootstrap.css",
        );

        $this->data_to_footer['js_to_load'] = array(
            "scripts/admin/datatable.js",
            "plugins/datatables/datatables.min.js",
            "plugins/datatables/plugins/bootstrap/datatables.bootstrap.js",
            "plugins/bootstrap-confirmation/bootstrap-confirmation.js",
        );

        $this->data_to_footer['scripts_to_load'] = array(
            "scripts/admin/table-datatables-managed.js",
        );

        // load view
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/admin/venue/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0) {
        // additional models
        $this->load->model('province_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "Venue Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        $this->data_to_view['js_to_load'] = array("select2.js");
        $this->data_to_view['js_script_to_load'] = '$(".autocomplete").select2({minimumInputLength: 2});';
        $this->data_to_view['css_to_load'] = array("select2.css", "select2-bootstrap.css");

        $this->data_to_view['status_dropdown'] = $this->venue_model->get_status_dropdown();
        $this->data_to_view['province_dropdown'] = $this->province_model->get_province_dropdown();

        if ($action == "edit") {
            $this->data_to_view['venue_detail'] = $this->venue_model->get_venue_detail($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {
            $this->data_to_view['venue_detail'] = $this->venue_model->get_venue_field_array();
            $this->data_to_view['venue_detail']['venue_status'] = 1;
        }

        // set validation rules
        $this->form_validation->set_rules('venue_name', 'Venue Name', 'required');
        $this->form_validation->set_rules('venue_status', 'Venue Status', 'required');
        $this->form_validation->set_rules('province_id', 'Province', 'required|numeric|greater_than[0]', ["greater_than" => "Please select a Province"]);

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $db_write = $this->venue_model->set_venue($action, $id);
            if ($db_write) {
                $alert = "Venue has been updated";
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
            if (array_key_exists("save_only", $this->input->post())) {
                $this->return_url = base_url("admin/venue/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }

    public function delete($venue_id) {

        if (($venue_id == 0) AND ( !is_int($venue_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get edition detail for nice delete message
        $venue_detail = $this->venue_model->get_venue_detail($venue_id);
        // delete record
        $db_del = $this->venue_model->remove_venue($venue_id);


        if ($db_del) {
            $msg = "Venue <b>" . $venue_detail['venue_name'] . "</b> has been deleted";
            $status = "success";
        } else {
            $msg = "Error committing to the database ID:'.$venue_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

}
