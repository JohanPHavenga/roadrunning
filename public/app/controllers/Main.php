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
            "limit" => "40",
        ];
        $this->data_to_views['featured_events'] = $this->race_model->add_race_info($this->edition_model->get_edition_list($query_params));

        // last edited
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"), "edition_date <= " => date("Y-m-d H:i:s", strtotime("1 week")), "edition_status" => 1],
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

    public function about() {
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
