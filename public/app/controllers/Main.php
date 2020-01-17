<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('edition_model');
        $this->load->model('race_model');
        $this->load->model('history_model');
        $this->load->model('file_model');
        $this->load->model('entrytype_model');

        // featured events
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"), "edition_isfeatured " => 1],
            "limit" => "5",
        ];
        $this->data_to_views['featured_events'] = $this->race_model->add_race_info($this->edition_model->get_edition_list($query_params));

        // upcoming events
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"), "edition_date <= " => date("Y-m-d H:i:s", strtotime("13 days")), "edition_status" => 1],
            "order_by" => ["editions.edition_date" => "ASC"],
        ];
        $upcoming_events = $this->race_model->add_race_info($this->edition_model->get_edition_list($query_params));
        $this->data_to_views['upcoming_events'] = $this->chronologise_data($upcoming_events, "edition_date");
//        wts($this->data_to_views['upcoming_events'],true);
        // last edited
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"), "editions.updated_date > " => date("Y-m-d H:i:s", strtotime("-1 year"))],
            "order_by" => ["editions.updated_date" => "DESC"],
            "limit" => 10,
        ];
        $this->data_to_views['last_edited_events'] = $this->edition_model->get_edition_list($query_params);

        // history summary
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "order_by" => ["historysum_countmonth" => "DESC"],
            "limit" => "10",
        ];
        $this->data_to_views['history_sum_month'] = $this->history_model->get_history_summary($query_params);


        // QUOTES for banner
        $this->data_to_views['quote_arr'] = $this->get_quote_data(3);

//        $this->data_to_views['featured_events'] = $this->chronologise_data($this->data_to_views['featured_events'], "edition_date");

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('templates/banner_home', $this->data_to_views);
//        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('main/home', $this->data_to_views);
        $this->load->view($this->footer_url);
    }

    public function custom_404() {
        if ($this->session->flashdata('alert') !== null) {
            $this->data_to_views['page_title'] = $this->session->flashdata('alert');
        } else {
            $this->data_to_views['page_title'] = "404 Error";
        }
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('main/404', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function search() {
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
                
                
        $search_params['order_by']["edition_date"] = "ASC";

        // DO TEH SEARCH
        $this->data_to_views['edition_list'] = $this->race_model->add_race_info($this->edition_model->get_edition_list($search_params), $race_search_params);
        if (!empty($this->data_to_views['edition_list'])) {
            foreach ($this->data_to_views['edition_list'] as $edition_id=>$edition_data) {
                $this->data_to_views['edition_list'][$edition_id]['status_info']=$this->formulate_status_notice($edition_data);
            }
        }

//        wts($search_params);
//        wts($this->data_to_views['search_result'], true);

        $this->data_to_views['page_title'] = "Search";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('templates/search_form');
        $this->load->view('templates/race_list', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function about() {
        $this->data_to_views['banner_img'] = "run_02";
        $this->data_to_views['banner_pos'] = "40%";
        $this->data_to_views['page_title'] = "About Me";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('main/about', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function newsletter() {
        $this->data_to_views['page_title'] = "Newsletter Subscription";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('main/newsletter', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function terms_conditions() {
        $this->data_to_views['companyName'] = "RoadRunningZA";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('main/terms_conditions', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function site_version() {
        $this->load->model('region_model');
        $this->data_to_views['region_dropdown'] = $this->region_model->get_region_dropdown();
        $this->data_to_views['form_url'] = base_url("main/site_version");

        $this->form_validation->set_rules('site_version[]', 'Region', 'required|greater_than[0]');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view('main/site_version', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            $this->session->set_flashdata([
                'alert' => "Site version updated",
                'status' => "success",
            ]);
            if ($this->session->user['logged_in'] == true) {
                $this->region_model->set_user_region($this->session->user['user_id'], $this->input->post("site_version"));
            }
            $this->session->set_userdata("region_selection", $this->input->post("site_version"));
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view('main/site_version', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        }
    }

    private function get_quote_data($count) {
        $this->load->model('quote_model');
        $this->load->helper('file');
        $img_base_url = "assets/img/slider/";

        // get random quotes
        $quote_arr = $this->quote_model->get_quote_list(true, $count);
        // get random bg image
        $img_arr = get_filenames($img_base_url);
        $img_count = sizeof($img_arr);

        // set return_arr
        $rand_img_num_arr = [];
        foreach ($quote_arr as $quote_id => $quote) {
            do {
                $rand_img_num = rand(1, $img_count);
            } while (in_array($rand_img_num, $rand_img_num_arr));
            $rand_img_num_arr[] = $rand_img_num;

            $num = sprintf("%02d", $rand_img_num);
            $return_arr[$quote_id]['quote'] = $quote['quote_quote'];
            $return_arr[$quote_id]['img_url'] = base_url($img_base_url . "run_" . $num . ".webp");
        }

        return ($return_arr);
    }

}
