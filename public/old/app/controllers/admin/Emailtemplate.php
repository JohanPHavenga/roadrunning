<?php

class Emailtemplate extends Admin_Controller {

    private $return_url = "/admin/emailtemplate/view";
    private $create_url = "/admin/emailtemplate/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('emailtemplate_model');
        $this->ini_array = parse_ini_file("server_config.ini", true);
    }

    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            redirect($this->return_url);
        }
    }

    public function view() {
        // load helpers / libraries
        $this->load->library('table');
        $this->data_to_view['heading'] = ["ID", "Template Name", "Linked To", "Actions"];
        $this->data_to_header['title'] = "List of email templates";

        $this->data_to_view["emailtemplate_data"] = $this->emailtemplate_model->get_emailtemplate_list();
        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Create Template",
                "icon" => "envelope-letter",
                "uri" => "emailtemplate/create/add",
            ],
        ];
        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Email Templates" => "/admin/emailtemplate",
            "List" => "",
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
        $this->load->view("/admin/emailtemplate/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0) {
        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');
        // set data
        $this->data_to_header['title'] = "Email Template Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;

        $this->data_to_header['css_to_load'] = array("plugins/bootstrap-summernote/summernote.css",);
        $this->data_to_footer['js_to_load'] = array("plugins/moment.min.js", "plugins/bootstrap-summernote/summernote.min.js",);
        $this->data_to_footer['scripts_to_load'] = array("scripts/admin/components-editors.js",);

        $this->data_to_view['linked_to_dropdown'] = $this->emailtemplate_model->get_linked_to_dropdown(50, 0);

        if ($action == "edit") {
            $this->data_to_view['emailtemplate_detail'] = $this->emailtemplate_model->get_emailtemplate_detail($id);
        } else {
            
        }

        // set validation rules
        $this->form_validation->set_rules('emailtemplate_name', 'Name', 'required');
        $this->form_validation->set_rules('emailtemplate_body', 'Body', 'required');
        $this->form_validation->set_rules('emailtemplate_linked_to', 'Linked To', 'required');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {

            $data = array(
                'emailtemplate_name' => $this->input->post('emailtemplate_name'),
                'emailtemplate_body' => $this->input->post('emailtemplate_body'),
                'emailtemplate_linked_to' => $this->input->post('emailtemplate_linked_to'),
            );
            $return_id=$this->emailtemplate_model->set_emailtemplate($action, $id, $data);

            if ($return_id) {
                $alert = "Email Template has been " . $action . "ed";
                $status = "success";

                // take person back to the right screen
                switch ($this->input->post('save-btn')) {
                    case "save_only":
                        $this->return_url = base_url("admin/emailtemplate/create/edit/" . $return_id);
                        break;
                }
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

    public function delete($emailtemplate_id = 0) {

        if (($emailtemplate_id == 0) AND ( !is_int($emailtemplate_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $emailtemplate_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get emailtemplate detail for nice delete message
        $emailtemplate_detail = $this->emailtemplate_model->get_emailtemplate_detail($emailtemplate_id);
        // delete record
        $db_del = $this->emailtemplate_model->remove_emailtemplate($emailtemplate_id);

        if ($db_del) {
            $msg = "Email has successfully been deleted: <b>" . $emailtemplate_detail['emailtemplate_name'] . "</b>";
            $status = "warning";
        } else {
            $msg = "Error in deleting the record:'.$emailtemplate_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

}
