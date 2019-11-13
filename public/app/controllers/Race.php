<?php

// Race Calendar
class Race extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('edition_model');
    }
    
    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->list($method, $params);
        }
    }

    public function list() {
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"),],
            "order_by" => ["edition_date" => "ASC"],
        ];
        $edition_list = $this->edition_model->get_edition_list($query_params);
        $this->data_to_views['edition_arr'] = $this->chronologise_data($edition_list, "edition_date");

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('race/view', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function history() {
        $query_params = [
//            "where" => ["edition_date >= " => date("Y-m-d H:i:s"),"edition_date < " => date("Y-m-d H:i:s", strtotime($time_span)),],
            "where_in" => ["region_id" => $this->session->region_selection,],
            "where" => ["edition_date < " => date("Y-m-d H:i:s")],
            "order_by" => ["edition_date" => "DESC"],
        ];
        $edition_list = $this->edition_model->get_edition_list($query_params);
        $this->data_to_views['edition_arr'] = $this->chronologise_data($edition_list, "edition_date");

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('race/view', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

}
