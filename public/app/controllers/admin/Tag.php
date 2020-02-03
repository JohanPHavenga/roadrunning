<?php

class Tag extends Admin_Controller {

    private $return_url = "/admin/tag";
    private $create_url = "/admin/tag/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/tag_model');
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

        $this->data_to_view["tag_data"] = $this->tag_model->get_tag_list();
        $this->data_to_view['heading'] = ["ID", "Tag", "Tagtype","Status", "Actions"];

        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['title'] = "List of Tags";
        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Tag" => "/admin/tag",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add Tag",
                "icon" => "tag",
                "uri" => "tag/create/add",
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
        $this->load->view("/admin/tag/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0) {
        // additional models
        $this->load->model('admin/tagtype_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "Tag Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        $this->data_to_view['js_to_load'] = array("select2.js");
        $this->data_to_view['js_script_to_load'] = '$(".autocomplete").select2({minimumInputLength: 2});';
        $this->data_to_view['css_to_load'] = array("select2.css", "select2-bootstrap.css");

        $this->data_to_view['status_dropdown'] = $this->tag_model->get_status_dropdown();
        $this->data_to_view['tagtype_dropdown'] = $this->tagtype_model->get_tagtype_dropdown();

        if ($action == "edit") {
            $this->data_to_view['tag_detail'] = $this->tag_model->get_tag_detail($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {
            $this->data_to_view['tag_detail'] = $this->tag_model->get_tag_field_array();
            $this->data_to_view['tag_detail']['tag_status'] = 1;
        }

        // set validation rules
        $this->form_validation->set_rules('tag_name', 'Tag Name', 'required');
        $this->form_validation->set_rules('tag_status', 'Tag Status', 'required');
        $this->form_validation->set_rules('tagtype_id', 'Tag Type', 'required|greater_than[0]', ["greater_than" => "Please select a Tag Type"]);

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $db_write = $this->tag_model->set_tag($action, $id);
            if ($db_write) {
                $alert = "Tag has been updated";
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
                $this->return_url = base_url("admin/tag/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }

    public function delete($tag_id) {

        if (($tag_id == 0) AND ( !is_int($tag_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get edition detail for nice delete message
        $tag_detail = $this->tag_model->get_tag_detail($tag_id);
        // delete record
        $db_del = $this->tag_model->remove_tag($tag_id);


        if ($db_del) {
            $msg = "Tag <b>" . $tag_detail['tag_name'] . "</b> has been deleted";
            $status = "success";
        } else {
            $msg = "Error committing to the database ID:'.$tag_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

}
