<?php

class Timingprovider extends Admin_Controller {

    private $return_url = "/admin/timingprovider";
    private $create_url = "/admin/timingprovider/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/timingprovider_model');
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

        $this->data_to_view["timingprovider_data"] = $this->timingprovider_model->get_timingprovider_list();
        $this->data_to_view['heading'] = ["ID", "Timing Provider", "Abbr", "Status", "Actions"];

        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['title'] = "List of RaceTypes";
        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Timingprovider" => "/admin/timingprovider",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add Timingprovider",
                "icon" => "bell",
                "uri" => "timingprovider/create/add",
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
        $this->load->view("/admin/timingprovider/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0) {
        // additional models
        $this->load->model('admin/town_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "Timing Provider Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        $this->data_to_view['js_to_load'] = array("select2.js");
        $this->data_to_view['js_script_to_load'] = '$(".autocomplete").select2({minimumInputLength: 2});';
        $this->data_to_view['css_to_load'] = array("select2.css", "select2-bootstrap.css");

        $this->data_to_view['status_dropdown'] = $this->timingprovider_model->get_status_dropdown();

        if ($action == "edit") {
            $this->data_to_view['timingprovider_detail'] = $this->timingprovider_model->get_timingprovider_detail($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {
            $this->data_to_view['timingprovider_detail'] = $this->timingprovider_model->get_timingprovider_field_array();
            $this->data_to_view['timingprovider_detail']['timingprovider_status'] = 1;
        }

        // set validation rules
        $this->form_validation->set_rules('timingprovider_name', 'Timing Provider Name', 'required');
        $this->form_validation->set_rules('timingprovider_abbr', 'Timing Provider Abbrivation', 'required');
        $this->form_validation->set_rules('timingprovider_status', 'Timing Provider Status', 'required');
        $this->form_validation->set_rules('timingprovider_url', 'Timing Provider URL', 'required');
        $this->form_validation->set_rules('timingprovider_img', 'Timing Provider Image', 'required');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $db_write = $this->timingprovider_model->set_timingprovider($action, $id);
            if ($db_write) {
                $alert = "Timing Provider has been updated";
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
                $this->return_url = base_url("admin/timingprovider/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }

    public function delete($timingprovider_id) {

        if (($timingprovider_id == 0) AND ( !is_int($timingprovider_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $timingprovider_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get edition detail for nice delete message
        $timingprovider_detail = $this->timingprovider_model->get_timingprovider_detail($timingprovider_id);
        // delete record
        $db_del = $this->timingprovider_model->remove_timingprovider($timingprovider_id);


        if ($db_del) {
            $msg = "Entry Type <b>" . $timingprovider_detail['timingprovider_name'] . "</b> has been deleted";
            $status = "success";
        } else {
            $msg = "Error committing to the database ID:'.$timingprovider_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

}
