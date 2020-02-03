<?php

class Date extends Admin_Controller {

    private $return_url = "/admin/date";
    private $create_url = "/admin/date/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('date_model');
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
        // unset edition return date session
        $this->session->unset_userdata('edition_return_url');


        $this->data_to_view["date_data"] = $this->date_model->get_date_list();
        $this->data_to_view['heading'] = ["ID", "Start Date", "End Date", "Datetype", "Linked To", "ID", "Actions"];

        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['title'] = "List of Dates";

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Users" => "/admin/date",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add Date",
                "icon" => "calendar",
                "uri" => "date/create/add",
            ],
        ];

//        $this->data_to_view['date'] = $this->date_disect();

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
        $this->load->view("/admin/date/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0, $linked_type = NULL) {

        // set return date to session should it exists
        if ($this->session->has_userdata('edition_return_url')) {
            $this->return_url = $this->session->edition_return_url;
        }

        // additional models
        $this->load->model('datetype_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "Date Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        $this->data_to_header['css_to_load'] = array(
            "plugins/typeahead/typeahead.css",
            "plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css",
            "plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css",
        );

        $this->data_to_footer['js_to_load'] = array(
            "plugins/typeahead/handlebars.min.js",
            "plugins/typeahead/typeahead.bundle.min.js",
            "plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js",
        );

        $this->data_to_footer['scripts_to_load'] = array(
            "scripts/admin/autocomplete.js",
            "scripts/admin/linked_to_hide_show.js",
            "scripts/admin/components-date-time-pickers.js",
        );

        $this->data_to_view['datetype_dropdown'] = $this->datetype_model->get_datetype_dropdown();
        $this->data_to_view['linked_to_dropdown'] = $this->datetype_model->get_linked_to_dropdown();
        $this->data_to_view['linked_to_list'] = $this->datetype_model->get_linked_to_list();

        // dynamically get drop downs using the linked_to_table
        foreach ($this->data_to_view['linked_to_list'] as $linked_to_id => $linked_to_name) {
            $dropdown = $linked_to_name . "_dropdown";
            $model = $linked_to_name . "_model";
            $method = "get_" . $linked_to_name . "_dropdown";

            $this->load->model($model);
            $this->data_to_view[$dropdown] = $this->$model->$method();
        }

        if ($action == "edit") {
            $this->data_to_view['date_detail'] = $this->date_model->get_date_detail($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {
            if ($id > 0) {
                $this->data_to_view['date_detail']['linked_id'] = $id;
                $this->data_to_view['date_detail']['date_linked_to'] = $linked_type;
            }
        }

//        wts($this->data_to_view['date_detail']);
        // set validation rules
        $this->form_validation->set_rules('date_start', 'Start Date', 'required|min_length[8]');
        $this->form_validation->set_rules('datetype_id', 'Date Type', 'required|greater_than[0]', ["greater_than" => "Please select a Date Type"]);


        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            // SET Date
            $id = $this->date_model->set_date($action, $id);
            // set the results flag
            $results_link_arr = ['edition', 'race'];
            if (in_array($this->input->post("date_linked_to"), $results_link_arr)) {
                $id_type = $this->input->post("date_linked_to") . "_id";
                $linked_id = $this->input->post($id_type);
                $set = $this->set_results_flag($this->input->post("date_linked_to"), $linked_id);
            }

            if ($id) {
                $alert = "Date details has been " . $action . "ed";
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
                $this->return_url = base_url("admin/date/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }

    public function delete($date_id = 0, $hash = null) {

        // set return date to session should it exists
        if ($this->session->has_userdata('edition_return_url')) {
            $this->return_url = $this->session->edition_return_url . "#".$hash;
        }

        if (($date_id == 0) AND ( !is_int($date_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $date_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get date detail for nice delete message
        $date_detail = $this->date_model->get_date_detail($date_id);
        // delete record
        $db_del = $this->date_model->remove_date($date_id);

        if ($db_del) {
            $msg = "Date has successfully been deleted: " . $date_detail['date_name'];
            $status = "success";
        } else {
            $msg = "Error in deleting the record:'.$date_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

    public function copy($date_id, $hash = null) {

        // set return date to session should it exists
        if ($this->session->has_userdata('edition_return_url')) {
            $this->return_url = $this->session->edition_return_url. "#" . $hash;
        }

        $new_date_id = $this->date_model->copy($date_id);

        if ($new_date_id) {
            $msg = "Date has successfully copied";
            $status = "success";
        } else {
            $msg = "Error trying to copy date ID:'.$date_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

    public function delete_group($date_id = 0) {
        // set return url to session should it exists
        if ($this->session->has_userdata('edition_return_url')) {
            $this->return_url = $this->session->edition_return_url;
        }
    }

    function exists($linked_type, $linked_id, $datetype_id) {
        return $this->date_model->exists($linked_type, $linked_id, $datetype_id);
    }

}
