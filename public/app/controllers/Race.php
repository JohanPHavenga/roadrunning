<?php

// Race Calendar
class Race extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('edition_model');
        $this->load->model('race_model');
    }

//    public function _remap($method, $params = array()) {
//        if (method_exists($this, $method)) {
//            return call_user_func_array(array($this, $method), $params);
//        } else {
//            $this->list($method, $params);
//        }
//    }

    public function list($year = null, $month = null, $day = null) {
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"), "edition_date <= " => date("Y-m-d H:i:s", strtotime("3 months")),],
            "order_by" => ["edition_date" => "ASC"],
        ];

//        wts($this->uri->segment(1));
//        wts($this->router->fetch_class(),1);

        if ($year !== null) {
            if (is_numeric($year)) {
                $this->data_to_views['page_title'] = "Running Races in " . $year;
                $query_params = [
                    "where" => ["edition_date >= " => "$year-1-1 00:00:00", "edition_date <= " => "$year-12-31 23:59:59",],
                ];

                if ($month !== null) {
                    if (is_numeric($month)) {
                        $month_name = date('F', mktime(0, 0, 0, $month, 1));
                        $this->data_to_views['page_title'] = "Running Races in " . $month_name . " " . date($year);
                        $query_params = [
                            "where" => ["edition_date >= " => "$year-$month-1 00:00:00", "edition_date <= " => date("$year-$month-t 23:59:59")],
                        ];
                        $this->data_to_views['crumbs_arr'] = replace_key($this->data_to_views['crumbs_arr'], $month, $month_name);

                        if ($day !== null) {
                            if (is_numeric($day)) {
                                $this->data_to_views['page_title'] = "Running Races on " . $day . " " . $month_name . " " . date($year);
                                $query_params = [
                                    "where" => ["edition_date >= " => "$year-$month-$day 00:00:00", "edition_date <= " => "$year-$month-$day 23:59:59"],
                                ];
                            } else {
                                redirect("404");
                            }
                        }
                    } else {
                        redirect("404");
                    }
                }
            } else {
                redirect("404");
            }
        } else {
            $this->data_to_views['page_title'] = "Upcoming Race Calendar";
        }

        $this->data_to_views['edition_list'] = $this->race_model->add_race_info($this->edition_model->get_edition_list($query_params));
        if ($this->data_to_views['edition_list']) {
            foreach ($this->data_to_views['edition_list'] as $edition_id => $edition_data) {
                $this->data_to_views['edition_list'][$edition_id]['status_info'] = $this->formulate_status_notice($edition_data);
            }
        }
        // check cookie vir listing preference.
        if (get_cookie("listing_pref") == "grid") {
            $view_to_load = 'race_grid';
        } else {
            $view_to_load = 'race_list';
        }
        $this->data_to_views['banner_img'] = "run_02";
        $this->data_to_views['banner_pos'] = "40%";

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        if (($this->uri->segment(1) == "race") || (!($this->data_to_views['edition_list']))) {
            $this->load->view('templates/search_form');
        }
        $this->load->view('templates/' . $view_to_load, $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function featured() {
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"), "edition_isfeatured " => 1,],
            "order_by" => ["edition_date" => "ASC"],
        ];

        $this->data_to_views['edition_list'] = $this->race_model->add_race_info($this->edition_model->get_edition_list($query_params));
        if ($this->data_to_views['edition_list']) {
            foreach ($this->data_to_views['edition_list'] as $edition_id => $edition_data) {
                $this->data_to_views['edition_list'][$edition_id]['status_info'] = $this->formulate_status_notice($edition_data);
            }
        }
        // check cookie vir listing preference.
        if (get_cookie("listing_pref") == "grid") {
            $view_to_load = 'race_grid';
        } else {
            $view_to_load = 'race_list';
        }

        $this->data_to_views['banner_img'] = "run_01";
        $this->data_to_views['banner_pos'] = "40%";
        $this->data_to_views['page_title'] = "Featured Races";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('templates/search_form');
        $this->load->view('templates/' . $view_to_load, $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function most_viewed() {
        $this->load->model('history_model');
        $this->data_to_views['edition_list'] = [];
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "order_by" => ["historysum_countmonth" => "DESC"],
            "limit" => "10",
        ];
        $most_viewed = $this->history_model->get_history_summary($query_params);

        if ($most_viewed) {
            $query_params = [
                "where_in" => ["edition_id" => array_keys($most_viewed),],
                "order_by" => ["edition_date" => "ASC"],
            ];
            $this->data_to_views['edition_list'] = $this->race_model->add_race_info($this->edition_model->get_edition_list($query_params));
            if ($this->data_to_views['edition_list']) {
                foreach ($this->data_to_views['edition_list'] as $edition_id => $edition_data) {
                    $this->data_to_views['edition_list'][$edition_id]['status_info'] = $this->formulate_status_notice($edition_data);
                }
            }
        }

        $this->data_to_views['banner_img'] = "run_05";
        $this->data_to_views['banner_pos'] = "50%";
        $this->data_to_views['page_title'] = "Top 10 most views races";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('templates/race_grid', $this->data_to_views);
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

    public function results() {
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s", strtotime("-1 months")), "edition_date <= " => date("Y-m-d H:i:s"),],
            "order_by" => ["edition_date" => "DESC"],
        ];

        $this->data_to_views['edition_list'] = $this->race_model->add_race_info($this->edition_model->get_edition_list($query_params));
        if ($this->data_to_views['edition_list']) {
            foreach ($this->data_to_views['edition_list'] as $edition_id => $edition_data) {
                $this->data_to_views['edition_list'][$edition_id]['status_info'] = $this->formulate_status_notice($edition_data);
            }
        }
//        wts($this->data_to_views['edition_list'],true);

        $this->data_to_views['banner_img'] = "run_02";
        $this->data_to_views['banner_pos'] = "40%";
        $this->data_to_views['page_title'] = "Race Results";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
//        $this->load->view('templates/search_form');
        $this->load->view('templates/race_list', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function parkrun() {
        $this->data_to_views['banner_img'] = "run_04";
        $this->data_to_views['banner_pos'] = "15%";
        $this->data_to_views['page_title'] = "parkrun";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('race/parkrun', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

}
