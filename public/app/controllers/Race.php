<?php

// Race Calendar
class Race extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('edition_model');
        $this->load->model('race_model');
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
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"), "edition_date <= " => date("Y-m-d H:i:s", strtotime("3 months")),],
            "order_by" => ["edition_date" => "ASC"],
        ];

        $this->data_to_views['edition_list'] = $this->race_model->add_race_info($this->edition_model->get_edition_list($query_params));
        foreach ($this->data_to_views['edition_list'] as $edition_id=>$edition_data) {
            $this->data_to_views['edition_list'][$edition_id]['status_info']=$this->formulate_status_notice($edition_data);
        }
//        wts($this->data_to_views['edition_list'],true);

        $this->data_to_views['banner_img'] = "run_02";
        $this->data_to_views['banner_pos'] = "40%";
        $this->data_to_views['page_title'] = "Upcoming Race Calendar";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('templates/search_form');
        $this->load->view('templates/race_list', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function featured() {
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"), "edition_isfeatured " => 1,],
            "order_by" => ["edition_date" => "ASC"],
        ];

        $this->data_to_views['edition_list'] = $this->race_model->add_race_info($this->edition_model->get_edition_list($query_params));
//        $this->data_to_views['edition_arr'] = $this->chronologise_data($edition_list, "edition_date");

        $this->data_to_views['banner_img'] = "run_01";
        $this->data_to_views['banner_pos'] = "40%";
        $this->data_to_views['page_title'] = "Featured Races";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('templates/race_list', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }
    
    public function most_viewed() {
        $this->load->model('history_model');
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "order_by" => ["historysum_countmonth" => "DESC"],
            "limit" => "10",
        ];
        $most_viewed = $this->history_model->get_history_summary($query_params);
        
        $query_params = [
            "where_in" => ["edition_id" => array_keys($most_viewed),],
            "order_by" => ["edition_date" => "ASC"],
        ];
        $this->data_to_views['edition_list'] = $this->race_model->add_race_info($this->edition_model->get_edition_list($query_params));
        
        $this->data_to_views['banner_img'] = "run_05";
        $this->data_to_views['banner_pos'] = "50%";
        $this->data_to_views['page_title'] = "Top 10 most views races";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('templates/race_list', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function history($year = null) {

        if ($year) {
            $query_params = [
//            "where" => ["edition_date >= " => date("Y-m-d H:i:s"),"edition_date < " => date("Y-m-d H:i:s", strtotime($time_span)),],
                "where_in" => ["region_id" => $this->session->region_selection,],
                "order_by" => ["edition_date" => "DESC"],
            ];
            if ($year == date("Y")) {
                $query_params["where"] = ["edition_date < " => date("Y-m-d H:i:s"), "edition_date >= " => date("$year-01-01 00:00:00"),];
            } else {
                $query_params["where"] = ["edition_date <= " => date("$year-12-31 23:59:59"), "edition_date >= " => date("$year-01-01 00:00:00"),];
            }
            $edition_list = $this->race_model->add_race_info($this->edition_model->get_edition_list($query_params));
            $this->data_to_views['edition_arr'] = $this->chronologise_data($edition_list, "edition_date");
            $this->data_to_views['year'] = $year;
            $view = 'templates/race_accordian';
        } else {
            $view = 'race/history';
        }


        $this->data_to_views['banner_img'] = "run_04";
        $this->data_to_views['banner_pos'] = "50%";
        $this->data_to_views['page_title'] = "Races History";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view($view, $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

}
