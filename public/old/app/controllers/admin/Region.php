<?php

class Region extends Admin_Controller {

    private $return_url = "/admin/region";
    private $create_url = "/admin/region/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('region_model');
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

        $this->data_to_view["region_data"] = $this->region_model->get_region_list();
        $this->data_to_view['heading'] = ["ID", "Region Name", "Slug", "Province", "Status", "Actions"];

        $this->data_to_header['title'] = "List of Regions";
        $this->data_to_view['create_link'] = $this->create_url;

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Region" => "/admin/region",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add Region",
                "icon" => "map",
                "uri" => "region/create/add",
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
        $this->load->view("/admin/region/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0) {

        // additional models
        $this->load->model('province_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "Region Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        $this->data_to_view['js_to_load'] = array("select2.js");
        $this->data_to_view['js_script_to_load'] = '$(".autocomplete").select2({minimumInputLength: 2});';
        $this->data_to_view['css_to_load'] = array("select2.css", "select2-bootstrap.css");

        $this->data_to_view['status_dropdown'] = $this->region_model->get_status_dropdown();
        $this->data_to_view['province_dropdown'] = $this->province_model->get_province_dropdown();

        if ($action == "edit") {
            $this->data_to_view['region_detail'] = $this->region_model->get_region_detail($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {
            $this->data_to_view['region_detail']['region_status'] = 1;
            $this->data_to_view['region_detail']['province_id'] = 12;
        }

        // set validation rules
        $this->form_validation->set_rules('region_name', 'Region Name', 'required');
        $this->form_validation->set_rules('region_status', 'Region Status', 'required');
        $this->form_validation->set_rules('region_status', 'Region Status', 'required|numeric|greater_than[0]', ["greater_than" => "Please select a Status"]);
        $this->form_validation->set_rules('province_id', 'Province', 'required|numeric|greater_than[0]', ["greater_than" => "Please select a Province"]);

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $db_write = $this->region_model->set_region($action, $id);
            if ($db_write) {
                $alert = "Region has been updated";
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
                $this->return_url = base_url("admin/region/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }

    public function delete($id) {


        if ($id == 0) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        } else {
            $db_del = $this->region_model->remove_region($id);
            if ($db_del) {
                $msg = "Region has been deleted";
                $status = "success";
            } else {
                $msg = "Error deleting region - ID:'.$id";
                $status = "danger";
            }

            $this->session->set_flashdata('alert', $msg);
            $this->session->set_flashdata('status', $status);
            redirect($this->return_url);
        }
    }

    // create slugs for all the regions
    function generate_slugs() {
        $this->load->model('region_model');
        $region_list = $this->region_model->get_region_list();
        $n = 0;
        foreach ($region_list as $id => $region) {
            $this->region_model->update_field($id, "region_slug", url_title($region['region_name']));
            $n++;
        }

        echo "Done<br>";
        echo "<b>" . $n . "</b> slugs were updated<br>";
    }

}
