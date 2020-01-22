<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('edition_model');
        $this->load->model('race_model');

        // SET BAIC STATUS CHECK 
        $search_params['where']["edition_status !="] = 2;

        // Get correct search paramaters from post
        // QUERY
        if ($this->input->post("query")) {
            $search_params['group_start'] = "";
            $search_params['like']["edition_name"] = $this->input->post("query");
            $search_params['or_like']["event_name"] = $this->input->post("query");
            $search_params['or_like']["town_name"] = $this->input->post("query");
            $search_params['group_end'] = "";

            $this->edition_model->log_search($this->input->post("query"));
        }
        // WHERE
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
                $from_date = date("Y-m-d 00:00:00");
                $to_date = date("Y-m-d 23:59:59", strtotime("6 months"));
                break;
        }        
        $search_params['where']["edition_date >= "] = $from_date;
        $search_params['where']["edition_date <= "] = $to_date;
        
        
        // SHOW AS 
        set_cookie("listing_pref", $this->input->post("show"), 7200);
        if ($this->input->post("show")=="grid") {
            $view_to_load='race_grid';
        } else {
            $view_to_load='race_list';
        }
        
        // SORT
        $sort="ASC";
        if (strtotime($from_date) < strtotime("Y-m-d 00:00:00")) {
            $sort="DESC";
        }
        $search_params['order_by']["edition_date"] = $sort;
        
//        wts($search_params,true);

        // DO TEH SEARCH
        $this->data_to_views['edition_list'] = $this->race_model->add_race_info($this->edition_model->get_edition_list($search_params), $race_search_params);
        if (!empty($this->data_to_views['edition_list'])) {
            foreach ($this->data_to_views['edition_list'] as $edition_id => $edition_data) {
                $this->data_to_views['edition_list'][$edition_id]['status_info'] = $this->formulate_status_notice($edition_data);
            }
        }

//        echo $view_to_load;
//        wts($this->input->post());
//        wts($search_params, true);
//        wts($this->data_to_views['search_result'], true);

        $this->data_to_views['page_title'] = "Search";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('templates/search_form');
        $this->load->view('templates/'.$view_to_load, $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

}
