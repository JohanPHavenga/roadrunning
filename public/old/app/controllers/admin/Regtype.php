<?php

class Regtype extends Admin_Controller {

    private $return_url = "/admin/regtype";
    private $create_url = "/admin/regtype/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('regtype_model');
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

        $this->data_to_view["regtype_data"] = $this->regtype_model->get_regtype_list();
        $this->data_to_view['heading'] = ["ID", "Regtype", "Status", "Actions"];

        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['title'] = "List of RaceTypes";
        $this->data_to_header['crumbs'] = [
                    "Home" => "/admin",
                    "Regtype" => "/admin/regtype",
                    "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
                    [
                        "name" => "Add Regtype",
                        "icon" => "bell",
                        "uri" => "regtype/create/add",
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
        $this->load->view("/admin/regtype/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0) {
        // additional models
        $this->load->model('town_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "RaceType Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        $this->data_to_view['js_to_load'] = array("select2.js");
        $this->data_to_view['js_script_to_load'] = '$(".autocomplete").select2({minimumInputLength: 2});';
        $this->data_to_view['css_to_load'] = array("select2.css", "select2-bootstrap.css");

        $this->data_to_view['status_dropdown'] = $this->regtype_model->get_status_dropdown();

        if ($action == "edit") {
            $this->data_to_view['regtype_detail'] = $this->regtype_model->get_regtype_detail($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {            
            $this->data_to_view['regtype_detail'] = $this->regtype_model->get_regtype_field_array();
            $this->data_to_view['regtype_detail']['regtype_status']=1;
        }

        // set validation rules
        $this->form_validation->set_rules('regtype_name', 'Regtype Name', 'required');
        $this->form_validation->set_rules('regtype_status', 'Regtype Status', 'required');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $db_write = $this->regtype_model->set_regtype($action, $id);
            if ($db_write) {
                $alert = "Regtype has been updated";
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
                $this->return_url = base_url("admin/regtype/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }

    public function delete($regtype_id) {

        if (($regtype_id == 0) AND ( !is_int($regtype_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get edition detail for nice delete message
        $regtype_detail = $this->regtype_model->get_regtype_detail($regtype_id);
        // delete record
        $db_del = $this->regtype_model->remove_regtype($regtype_id);


        if ($db_del) {
            $msg = "Entry Type <b>" . $regtype_detail['regtype_name'] . "</b> has been deleted";
            $status = "success";
        } else {
            $msg = "Error committing to the database ID:'.$regtype_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

}
