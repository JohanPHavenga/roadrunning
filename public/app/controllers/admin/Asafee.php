<?php

class Asafee extends Admin_Controller {

    private $return_url = "/admin/asafee";
    private $create_url = "/admin/asafee/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/asafee_model');
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

        $this->data_to_view["asafee_data"] = $this->asafee_model->get_asafee_list();
        $this->data_to_view['heading'] = ["ID", "ASA Memeber", "Year", "Distance From", "Distance To", "Senior Fee", "Junior Fee", "Actions"];

//        $this->data_to_view['delete_arr']=["controller"=>"asafee","id_field"=>"asa_fee_id"];
        $this->data_to_header['title'] = "List of ASA Licence Fees";
        $this->data_to_view['create_link'] = $this->create_url;

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "ASA Licence Fees" => "/admin/asafee",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add ASA Licence Fee",
                "icon" => "credit-card",
                "uri" => "asafee/create/add",
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
        $this->load->view("/admin/asafee/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0) {
        // additional models
        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('admin/asamember_model');

        // set data
        $this->data_to_header['title'] = "ASA Licence Fees Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "ASA Licence Fees" => "/admin/asafee",
            ucfirst($action) => "",
        ];

        $this->data_to_view['js_to_load'] = array("select2.js");
        $this->data_to_view['js_script_to_load'] = '$(".autocomplete").select2({minimumInputLength: 2});';
        $this->data_to_view['css_to_load'] = array("select2.css", "select2-bootstrap.css");

        $this->data_to_view['status_dropdown'] = $this->asafee_model->get_status_dropdown();
        $this->data_to_view['asamember_dropdown'] = $this->asamember_model->get_asamember_dropdown();

        if ($action == "edit") {
            $this->data_to_view['asafee_detail'] = $this->asafee_model->get_asafee_detail($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {
            $this->data_to_view['asafee_detail'] = $this->asafee_model->get_asafee_field_array();
            $this->data_to_view['asafee_detail']['asa_fee_status'] = 1;
        }

        // set validation rules
        $this->form_validation->set_rules('asa_member_id', 'ASA Member', 'required|greater_than[0]');
        $this->form_validation->set_rules('asa_fee_year', 'Year', 'required|numeric');
        $this->form_validation->set_rules('asa_fee_distance_from', 'Distance from', 'required');
        $this->form_validation->set_rules('asa_fee_distance_to', 'Distance to', 'required');
        $this->form_validation->set_rules('asa_fee_snr', 'Senior Fee', 'required');

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
            $db_write = $this->asafee_model->set_asafee($action, $id, $data);
            if ($db_write) {
                $alert = "ASA Licence Fee has been updated";
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
                $this->return_url = base_url("admin/asafee/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }

    public function delete($asa_fee_id = 0) {
        $this->load->model('admin/asamember_model');
        if (($asa_fee_id == 0) AND ( !is_int($asa_fee_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $asa_fee_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get detail for nice delete message
        $asafee_detail = $this->asafee_model->get_asafee_detail($asa_fee_id);
        $asamember_detail = $this->asamember_model->get_asamember_detail($asafee_detail['asa_member_id']);
        // wts($asamember_detail,1);
        // delete record
        $db_del = $this->asafee_model->remove_asafee($asa_fee_id);

        if ($db_del) {
            $msg = "Temp license fee structure has successfully been removed for <b>" . $asamember_detail['asa_member_abbr']."</b>";
            $status = "success";
        } else {
            $msg = "Error in deleting the record:'.$asa_fee_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);

    }

    public function copy($old_id) {

        $this->load->model('admin/asamember_model');
        // get data
        $asafee_detail = $this->asafee_model->get_asafee_detail($old_id);
        $asamember_detail = $this->asamember_model->get_asamember_detail($asafee_detail['asa_member_id']);

        // wts($asafee_detail);
        // wts($asamember_detail);
        
        $asafee_data=$asafee_detail;
        $asafee_data['asa_fee_year']=$asafee_detail['asa_fee_year']+1;
        // unset fields not needed
        unset($asafee_data['asa_fee_id']);
        unset($asafee_data['created_date']);
        unset($asafee_data['updated_date']);

        // wts($asafee_data,1);

        $new_id = $this->asafee_model->set_asafee("add", NULL, $asafee_data);

        if ($new_id) {
            $alert = "ASA fee structure for <b>".$asamember_detail['asa_member_abbr']."</b> has been copied to ". $asafee_data['asa_fee_year'];
            $status = "success";
            $return_url = base_url("admin/asafee/view");
        } else {
            $alert = "Error trying to add a copy of <b>" . $asamember_detail['asa_member_abbr'] . "</b> fee structure";
            $status = "danger";
            $return_url = base_url("admin/asafee/view");
        }

        $this->session->set_flashdata([
            'alert' => $alert,
            'status' => $status,
        ]);

        redirect($return_url);
        die();
    }

}
