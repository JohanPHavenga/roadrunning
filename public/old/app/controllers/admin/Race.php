<?php

class Race extends Admin_Controller {

    private $return_url = "/admin/race";
    private $create_url = "/admin/race/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('race_model');
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
        // unset edition return url session
        $this->session->unset_userdata('edition_return_url');

        $this->data_to_view["race_data"] = $this->race_model->get_race_list();
        $this->data_to_view['heading'] = ["ID", "Edition", "Race Type", "Race Time", "Race Fees", "Status", "Actions"];

        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['title'] = "List of Races";
        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Races" => "/admin/race",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add Race",
                "icon" => "speedometer",
                "uri" => "race/create/add",
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
        $this->load->view("/admin/race/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0) {

        // set return url to session should it exists
        if ($this->session->has_userdata('edition_return_url')) {
            $this->return_url = $this->session->edition_return_url . "#races";
        }

        // additional models
        $this->load->model('edition_model');
        $this->load->model('racetype_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "Race Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        $this->data_to_header['css_to_load'] = array(
            "plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css",
            "plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css",
            "plugins/bootstrap-summernote/summernote.css",
        );

        $this->data_to_header['js_to_load'] = array(
            "plugins/moment.min.js",
            "plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js",
            "plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js",
            "plugins/bootstrap-summernote/summernote.min.js",
        );

        $this->data_to_footer['scripts_to_load'] = array(
            "scripts/admin/components-date-time-pickers.js",
            "scripts/admin/components-editors.js",
        );


        $this->data_to_view['edition_dropdown'] = $this->edition_model->get_edition_dropdown();
        $this->data_to_view['status_dropdown'] = $this->race_model->get_status_dropdown();
        $this->data_to_view['racetype_dropdown'] = $this->racetype_model->get_racetype_dropdown();

        $num_fields = ['race_fee_flat', 'race_fee_senior_licenced', 'race_fee_senior_unlicenced', 'race_fee_junior_licenced', 'race_fee_junior_unlicenced'];
        if ($action == "edit") {
            $this->data_to_view['race_detail'] = $this->race_model->get_race_detail($id);
            $this->data_to_view['edition_detail'] = $this->edition_model->get_edition_detail($this->data_to_view['race_detail']['edition_id']);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {
            $this->data_to_view['race_detail'] = $this->race_model->get_race_field_array();
            $this->data_to_view['race_detail']['race_status'] = 1;
            $this->data_to_view['race_detail']['racetype_id'] = 4;
            $this->data_to_view['race_detail']["race_time_end"] = "";
            if ($id > 0) {
                $this->data_to_view['edition_detail'] = $this->edition_model->get_edition_detail($id);
                $this->data_to_view['race_detail']['edition_id'] = $id;
            } else {
                $this->data_to_view['edition_detail']['edition_date'] = '';
                $this->data_to_view['edition_detail']['edition_address'] = '';
            }
        }

        // make all the numeric fields 0, not empty
        foreach ($num_fields as $field) {
            if (empty($this->data_to_view['race_detail'][$field])) {
                $this->data_to_view['race_detail'][$field] = 0;
            }
        }

//        if (!isset($race_detail['race_isover70free'])) { $race_detail['race_isover70free']=false; }
        // set validation rules
        $this->form_validation->set_rules('race_distance', 'race distance', 'required|numeric|less_than[1000]');
        $this->form_validation->set_rules('race_time_start', 'race start time', 'required');
        $this->form_validation->set_rules('race_status', 'race status', 'required|greater_than[0]', ["greater_than" => "Please select an status for the race"]);
        $this->form_validation->set_rules('racetype_id', 'race type', 'required|numeric|greater_than[0]', ["greater_than" => "Please select a race type"]);
        $this->form_validation->set_rules('edition_id', 'Edition', 'required|numeric|greater_than[0]', ["greater_than" => "Please select an edition"]);
        $this->form_validation->set_rules('race_fee_flat', "Race Flat Fee", 'numeric');
        $this->form_validation->set_rules('race_fee_senior_licenced', "Senior Race Fee Licenced", 'numeric|less_than[1000]|greater_than[-1]');
        $this->form_validation->set_rules('race_fee_senior_unlicenced', "Senior Race Fee Unlicenced", 'numeric');
        $this->form_validation->set_rules('race_fee_junior_licenced', "Junior Race Fee Licenced", 'numeric');
        $this->form_validation->set_rules('race_fee_junior_unlicenced', "Junior Race Fee Unlicenced", 'numeric');
        $this->form_validation->set_rules('race_minimum_age', "minimum age", 'numeric|less_than[100]|greater_than[-1]');
        $this->form_validation->set_rules('race_entry_limit', "minimum age", 'numeric');
        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            // check for empty flag
            if (empty($this->input->post('race_isover70free'))) {
                $over70 = false;
            } else {
                $over70 = $this->input->post('race_isover70free');
            }
            // Set Race Data from POST
            $race_data = array(
                'race_name' => $this->input->post('race_name'),
                'race_distance' => $this->input->post('race_distance'),
                'race_time_start' => $this->input->post('race_time_start'),
                'race_time_end' => $this->input->post('race_time_end'),
                'race_date' => $this->input->post('race_date'),
                'race_status' => $this->input->post('race_status'),
                'edition_id' => $this->input->post('edition_id'),
                'racetype_id' => $this->input->post('racetype_id'),
                'race_fee_flat' => intval($this->input->post('race_fee_flat')),
                'race_fee_senior_licenced' => intval($this->input->post('race_fee_senior_licenced')),
                'race_fee_senior_unlicenced' => intval($this->input->post('race_fee_senior_unlicenced')),
                'race_fee_junior_licenced' => intval($this->input->post('race_fee_junior_licenced')),
                'race_fee_junior_unlicenced' => intval($this->input->post('race_fee_junior_unlicenced')),
                'race_minimum_age' => $this->input->post('race_minimum_age'),
                'race_isover70free' => $over70,
                'race_address' => $this->input->post('race_address'),
                'race_notes' => $this->input->post('race_notes'),
                'race_entry_limit' => $this->input->post('race_entry_limit'),
            );

            // get edition info
            $edition_info = $this->edition_model->get_edition_detail($this->input->post('edition_id'));

            // as dit 'n ASA regulated race is
            if ($edition_info['edition_asa_member'] > 0) {
                $race_data = $this->race_fill_blanks($race_data, $edition_info);
            }

//            wts($race_data);
//            wts($edition_info);
//            die();
            // set auto values

            $id = $this->race_model->set_race($action, $id, $race_data, false);
            if ($id) {
                $alert = "Race has been updated";
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
                $this->return_url = base_url("admin/race/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }

    public function delete($race_id = 0) {

        // set return url to session should it exists
        if ($this->session->has_userdata('edition_return_url')) {
            $this->return_url = $this->session->edition_return_url;
        }

        if (($race_id == 0) AND ( !is_int($race_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $race_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get race detail for nice delete message
        $race_detail = $this->race_model->get_race_detail($race_id);
        // delete record
        $db_del = $this->race_model->remove_race($race_id);

        if ($db_del) {
            $msg = "Race has successfully been deleted: " . $race_detail['race_name'];
            $status = "success";
        } else {
            $msg = "Error in deleting the record:'.$race_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

    // ==========================================================================================
    // TEMP DATA GENERATION SCRIPTS
    // ==========================================================================================
    // create race names for all races
    function generate_race_names() {
        // function to port old URLs from fields directly on Edition to URl table
        $this->load->model('race_model');
        $racelist = $this->race_model->get_race_list();
        $n = 0;
        foreach ($racelist as $r_id => $race) {
            if ($race['race_name'] == "") {
                $race_name = $this->get_race_name_from_status($race['race_name'], $race['race_distance'], $race['racetype_name'], $race['race_status']);
                $this->race_model->update_field($r_id, "race_name", $race_name);
                $n++;
            }
        }

        echo "Done<br>";
        echo "<b>" . $n . "</b> names were updated<br>";
    }

}
