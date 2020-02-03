<?php

class Asareg extends Admin_Controller {

    private $return_url = "/admin/asareg";
    private $create_url = "/admin/asareg/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('asareg_model');
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

        $this->data_to_view["asareg_data"] = $this->asareg_model->get_asareg_list();
        $this->data_to_view['heading'] = ["ID", "IAAF Distance", "Name", "Distance From", "Distance To", "Minimum Age", "Actions"];

//        $this->data_to_view['delete_arr']=["controller"=>"asareg","id_field"=>"asa_reg_id"];
        $this->data_to_header['title'] = "List of ASA Regulations";
        $this->data_to_view['create_link'] = $this->create_url;

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "ASA Regulations" => "/admin/asareg",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add ASA Regulation",
                "icon" => "notebook",
                "uri" => "asareg/create/add",
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
        $this->load->view("/admin/asareg/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0) {
        // additional models
        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "ASA Regulations Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "ASA Regulations" => "/admin/asareg",
            ucfirst($action) => "",
        ];

        $this->data_to_view['js_to_load'] = array("select2.js");
        $this->data_to_view['js_script_to_load'] = '$(".autocomplete").select2({minimumInputLength: 2});';
        $this->data_to_view['css_to_load'] = array("select2.css", "select2-bootstrap.css");

        $this->data_to_view['status_dropdown'] = $this->asareg_model->get_status_dropdown();

        if ($action == "edit") {
            $this->data_to_view['asareg_detail'] = $this->asareg_model->get_asareg_detail($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {
            $this->data_to_view['asareg_detail'] = $this->asareg_model->get_asareg_field_array();
        }

        // set validation rules
        $this->form_validation->set_rules('asa_reg_distance_name', 'Regulation name', 'required');
        $this->form_validation->set_rules('asa_reg_iaaf', 'IAAF distance', 'required');
        $this->form_validation->set_rules('asa_reg_distance_from', 'Distance from', 'required');
        $this->form_validation->set_rules('asa_reg_distance_to', 'Distance to', 'required');
        $this->form_validation->set_rules('asa_reg_minimum_age', 'Minimum age', 'required|greater_than[1]|less_than[100]');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $data = $this->input->post();
            if (isset($data['save_only'])) {
                unset($data['save_only']);
            }
            $db_write = $this->asareg_model->set_asareg($action, $id, $data);
            if ($db_write) {
                $alert = "ASA Regulation has been updated";
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
                $this->return_url = base_url("admin/asareg/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }

    public function delete($confirm = false) {

        $id = $this->encryption->decrypt($this->input->post('asa_reg_id'));

        if ($id == 0) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        if ($confirm == 'confirm') {
            $db_del = $this->asareg_model->remove_asareg($id);
            if ($db_del) {
                $msg = "ASA Regulation has been deleted";
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

}
