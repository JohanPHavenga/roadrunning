<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('edition_model');
        $this->load->model('history_model');
        // featured events
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"), "edition_isfeatured " => 1],
        ];
        $this->data_to_views['featured_events_new'] = $this->edition_model->get_edition_list_incl_races($query_params);

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
        $this->data_to_views['history_sum_month']=$this->history_model->get_history_summary($query_params);
        
        

//        $this->data_to_views['featured_events'] = $this->chronologise_data($edition_list, "edition_date");

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('main/home', $this->data_to_views);
        $this->load->view($this->footer_url);
    }

    public function custom_404() {
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('main/404', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function about() {
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('main/about', $this->data_to_views);
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

}
