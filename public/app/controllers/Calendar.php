<?php

// Race Calendar
class Calendar extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('edition_model');
    }

    public function index() {
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"),],
            "order_by" => ["edition_date" => "ASC"],
        ];
        $edition_list = $this->edition_model->get_edition_list($query_params);
        $this->data_to_views['edition_arr'] = $this->chronologise_data($edition_list, "edition_date");

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('calendar/view', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function past() {
        $query_params = [
//            "where" => ["edition_date >= " => date("Y-m-d H:i:s"),"edition_date < " => date("Y-m-d H:i:s", strtotime($time_span)),],
            "where_in" => ["region_id" => $this->session->region_selection,],
            "where" => ["edition_date < " => date("Y-m-d H:i:s")],
            "order_by" => ["edition_date" => "DESC"],
        ];
        $edition_list = $this->edition_model->get_edition_list($query_params);
        $this->data_to_views['edition_arr'] = $this->chronologise_data($edition_list, "edition_date");

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('calendar/view', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

}
