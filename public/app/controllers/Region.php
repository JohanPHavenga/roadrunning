<?php

// MAIN Region controller
class Region extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('region_model');
    }

    // check if method exists, if not calls "view" method
    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->calendar($method, $params = array());
        }
    }

    public function list() {
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('region/list', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function calendar($slug) {
        $this->load->model('edition_model');
        // as daar nie 'n region naam deurgestuur word nie
        if ($slug == "index") {
            redirect("/region/list");
        }

        // kry eers die ID
        $region_id = $this->region_model->get_region_id_from_slug($slug);
        // kry al die editions vir die provinsie 
        $query_params = [
            "order_by" => ["edition_date" => "DESC"],
            "where" => ["regions.region_id" => $region_id],
        ];
        $edition_list = $this->edition_model->get_edition_list($query_params);
        $this->data_to_views['edition_arr'] = $this->chronologise_data($edition_list, "edition_date");

        $this->data_to_views['region_id'] = $region_id;
        $this->data_to_views['region_name'] = $slug;

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('region/calendar', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function switch() {
        $this->data_to_views['page_title'] = "Region Selection";
        $this->load->model('region_model');
        $this->data_to_views['region_dropdown'] = $this->region_model->get_region_dropdown();
        $this->data_to_views['form_url'] = base_url("region/switch");

        $this->form_validation->set_rules('site_version[]', 'Region', 'required');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view($this->notice_url, $this->data_to_views);
            $this->load->view('region/switch', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            $this->session->set_flashdata([
                'alert' => "<b>SUCCESS!</b> Selected regions updated",
                'status' => "success",
            ]);
            if ($this->input->post("site_version") == [0]) {
                $region_list = $this->region_model->get_all_region_ids();
            } else {
                $region_list = $this->input->post("site_version");
            }

            if ($this->session->user['logged_in'] == true) {
                $this->region_model->set_user_region($this->session->user['user_id'], $region_list);
            }
            $this->session->set_userdata("region_selection", $region_list);
            redirect(base_url("region/switch"));
        }
    }

}
