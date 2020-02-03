<?php

class Entrytype extends Admin_Controller {

    private $return_url = "/admin/entrytype";
    private $create_url = "/admin/entrytype/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/entrytype_model');
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

        $this->data_to_view["entrytype_data"] = $this->entrytype_model->get_entrytype_list();
        $this->data_to_view['heading'] = ["ID", "EntryType", "Status", "Actions"];

        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['title'] = "List of RaceTypes";
        $this->data_to_header['crumbs'] = [
                    "Home" => "/admin",
                    "EntryType" => "/admin/entrytype",
                    "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
                    [
                        "name" => "Add EntryType",
                        "icon" => "flag",
                        "uri" => "entrytype/create/add",
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
        $this->load->view("/admin/entrytype/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0) {
        // additional models
        $this->load->model('admin/town_model');

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

        $this->data_to_view['status_dropdown'] = $this->entrytype_model->get_status_dropdown();
        $this->data_to_view['town_dropdown'] = $this->town_model->get_town_dropdown();

        if ($action == "edit") {
            $this->data_to_view['entrytype_detail'] = $this->entrytype_model->get_entrytype_detail($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {            
            $this->data_to_view['entrytype_detail'] = $this->entrytype_model->get_entrytype_field_array();
            $this->data_to_view['entrytype_detail']['entrytype_status']=1;
        }

        // set validation rules
        $this->form_validation->set_rules('entrytype_name', 'Entrytype Name', 'required');
        $this->form_validation->set_rules('entrytype_status', 'Entrytype Status', 'required');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $db_write = $this->entrytype_model->set_entrytype($action, $id);
            if ($db_write) {
                $alert = "Entrytype has been updated";
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
                $this->return_url = base_url("admin/entrytype/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }

    public function delete($entrytype_id) {

        if (($entrytype_id == 0) AND ( !is_int($entrytype_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get edition detail for nice delete message
        $entrytype_detail = $this->entrytype_model->get_entrytype_detail($entrytype_id);
        // delete record
        $db_del = $this->entrytype_model->remove_entrytype($entrytype_id);


        if ($db_del) {
            $msg = "Entry Type <b>" . $entrytype_detail['entrytype_name'] . "</b> has been deleted";
            $status = "success";
        } else {
            $msg = "Error committing to the database ID:'.$entrytype_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

}
