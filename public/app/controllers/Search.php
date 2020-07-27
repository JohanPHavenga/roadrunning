<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('edition_model');
        $this->load->model('race_model');
    }

    public function index() {

        $this->load->model('admin/result_model');

        // SET BAIC STATUS CHECK 
        $search_params['where_in']["edition_status"] = [1, 3, 9];

        // Get correct search paramaters from post
        // QUERY
        if ($this->input->post("query")) {
            $search_params['group_start'] = "";
            $search_params['like']["edition_name"] = $this->input->post("query");
            $search_params['or_like']["event_name"] = $this->input->post("query");
            $search_params['or_like']["town_name"] = $this->input->post("query");
            $search_params['or_like']["province_name"] = $this->input->post("query");
            $search_params['group_end'] = "";

            $this->edition_model->log_search($this->input->post("query"));
        }
        // WHERE
        if ($this->input->post("where") !== NULL) {
            switch ($this->input->post("where")) {
                case "my":
                    if (isset($this->session->region_selection)) {
                        $search_params['where_in']["region_id"] = $this->session->region_selection;
                    }
                    break;
                case "all":
                    break;
                default:
                    $field_parts = explode("_", $this->input->post("where"));
                    // REGION
                    if ($field_parts[0] == "reg") {
                        $search_params['where']["region_id"] = $field_parts[1];
                    }
                    // PROVINCE
                    if ($field_parts[0] == "pro") {
                        $search_params['where']["provinces.province_id"] = $field_parts[1];
                    }
                    break;
            }
        } else {
            $search_params['where_in']["region_id"] = $this->session->region_selection;
        }

        // DISTANCE
        // search dalk eers races, en pass dan 'n lys van edition IDs
        switch ($this->input->post("distance")) {
            case 'fun':
                $from_dist = 0;
                $to_dist = 10;
                break;
            case '10':
                $from_dist = 10;
                $to_dist = 15;
                break;
            case '15':
                $from_dist = 15;
                $to_dist = 21.1;
                break;
            case '21':
                $from_dist = 21.1;
                $to_dist = 30;
                break;
            case '30':
                $from_dist = 30;
                $to_dist = 42.2;
                break;
            case '42':
                $from_dist = 42.2;
                $to_dist = 42.2;
                break;
            case 'ultra':
                $from_dist = 42.2;
                $to_dist = 1000;
                break;
            default:
                $from_dist = 0;
                $to_dist = 1000;
                break;
        }
        $race_search_params['where']['race_distance >='] = $from_dist;
        $race_search_params['where']['race_distance <'] = $to_dist;


        // WHEN
        set_cookie("search_when_pref", $this->input->post("when"), 7200);
        switch ($this->input->post("when")) {
            case "any":
                $from_date = date("2016-10-01 00:00:00");
                $to_date = date("Y-m-d H:i:s", strtotime("1 year"));
                break;
            case "weekend":
                $from_date = date("Y-m-d 00:00:00");
                $to_date = date("Y-m-d 23:59:59", strtotime("next sunday"));
                break;
            case "plus_30d":
                $from_date = date("Y-m-d 00:00:00");
                $to_date = date("Y-m-d 23:59:59", strtotime("30 days"));
                break;
            case "plus_3m":
                $from_date = date("Y-m-d 00:00:00");
                $to_date = date("Y-m-d 23:59:59", strtotime("3 months"));
                break;
            case "plus_6m":
                $from_date = date("Y-m-d 00:00:00");
                $to_date = date("Y-m-d 23:59:59", strtotime("6 months"));
                break;
            case "plus_1y":
                $from_date = date("Y-m-d 00:00:00");
                $to_date = date("Y-m-d 23:59:59", strtotime("1 year"));
                break;
            case "minus_6m":
                $from_date = date("Y-m-d 00:00:00", strtotime("-6 months"));
                $to_date = date("Y-m-d 23:59:59");
                break;
            default:
                $from_date = date("Y-m-d 00:00:00", strtotime("-2 weeks"));
                $to_date = date("Y-m-d 23:59:59", strtotime("1 year"));
                break;
        }
        $search_params['where']["edition_date >= "] = $from_date;
        $search_params['where']["edition_date <= "] = $to_date;


        // SHOW AS
        set_cookie("listing_pref", $this->input->post("show"), 7200);
        if ($this->input->post("show") == "grid") {
            $view_to_load = 'race_grid';
        } else {
            $view_to_load = 'race_list';
        }

        // SORT
        $sort = "ASC";
        if (strtotime($from_date) < strtotime("Y-m-d 00:00:00")) {
            $sort = "DESC";
        }
        $search_params['order_by']["edition_date"] = $sort;
        // LIMIT
        $search_params['limit'] = 50;

//        wts($search_params,true);
        // DO THE SEARCH
        $this->data_to_views['edition_list'] = $this->race_model->add_race_info($this->edition_model->get_edition_list($search_params), $race_search_params);
        if (!empty($this->data_to_views['edition_list'])) {
            foreach ($this->data_to_views['edition_list'] as $edition_id => $edition_data) {
                $this->data_to_views['edition_list'][$edition_id]['status_info'] = $this->formulate_status_notice($edition_data);
                // set has result field for both races and editions
                $this->data_to_views['edition_list'][$edition_id]['has_results'] = false;
                foreach ($edition_data['race_list'] as $race_id => $race) {
                    $has_result = $this->result_model->result_exist_for_race($race_id);
                    $this->data_to_views['edition_list'][$edition_id]['race_list'][$race_id]['has_results'] = $has_result;
                    if ($has_result) {
                        $this->data_to_views['edition_list'][$edition_id]['has_results'] = $has_result;
                    }
                }
            }
        }

//        echo $view_to_load;
//        wts($this->input->post());
//        wts($search_params, true);
//        wts($this->data_to_views['edition_list'], true);

        $this->data_to_views['page_title'] = "Search";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('templates/search_form');
        $this->load->view('templates/' . $view_to_load, $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function tag($tag_type, $query) {

        $search_params['where']["edition_date >= "] = date("Y-m-d 00:00:00");
        $search_params['where']["edition_date <= "] = date("Y-m-d 23:59:59", strtotime("1 year"));
        $race_search_params = [];

        $query = urldecode($query);

        switch ($tag_type) {
            case "race_name":
                $race_search_params['where']["race_name"] = $query;
                break;
            case "race_distance":
                $race_search_params['where']["race_distance"] = floatval(str_replace("km", "", $query));
                break;
            case "region_name":
                $search_params['where']["region_name"] = $query;
                break;
            case "province_name":
                $search_params['where']["province_name"] = $query;
                break;
            case "club_name":
                $search_params['where']["club_name"] = $query;
                break;
            case "town_name":
                $search_params['where']["town_name"] = $query;
                break;
            case "event_name":
                $search_params['where']["edition_date >= "] = date("2016-01-01 00:00:00");
                $search_params['where']["event_name"] = $query;
                break;
            case "edition_year":
                redirect(base_url("calendar/" . $query));
                break;
            case "edition_month":
                $query_part = explode(" ", $query);
                $month_num = date("m", strtotime("$query_part[0]-$query_part[1]"));
                redirect(base_url("calendar/" . $query_part[1] . "/" . $month_num));
                break;
            case "asa_member_abbr":
                $search_params['where']["asa_member_abbr"] = $query;
                break;
            default:
                redirect("404");
                break;
        }

        // DO THE SEARCH
        $this->data_to_views['edition_list'] = $this->race_model->add_race_info($this->edition_model->get_edition_list($search_params), $race_search_params);
        if (!empty($this->data_to_views['edition_list'])) {
            foreach ($this->data_to_views['edition_list'] as $edition_id => $edition_data) {
                $this->data_to_views['edition_list'][$edition_id]['status_info'] = $this->formulate_status_notice($edition_data);
            }
        }

//        echo $view_to_load;
//        wts($this->input->post());
//        wts($this->data_to_views['edition_list']);
//        wts($race_search_params);
//        wts($search_params, true);
        // SHOW AS 
        set_cookie("listing_pref", $this->input->post("show"), 7200);
        if ($this->input->post("show") == "grid") {
            $view_to_load = 'race_grid';
        } else {
            $view_to_load = 'race_list';
        }
        $this->data_to_views['page_title'] = "Search";
        $this->data_to_views['tag'] = $query;

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('templates/search_form');
        $this->load->view('templates/' . $view_to_load, $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

}
