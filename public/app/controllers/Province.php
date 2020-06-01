<?php

// public mailer class to get list from mailques table and send it out
class Province extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('province_model');
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
        $this->load->model('edition_model');
        $this->data_to_views['banner_img'] = "run_04";
        $this->data_to_views['banner_pos'] = "20%";
        $this->data_to_views['page_title'] = "Province List";        
        
        foreach ($this->session->province_pages as $province_id=>$province) {
            $province_count=$this->edition_model->edition_count($province_id);
            $this->data_to_views['province_list'][$province_id]=$province;
            $this->data_to_views['province_list'][$province_id]['edition_count']=$province_count;
        }

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view('province/list', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function calendar($slug="index") {   
        $this->load->model('edition_model');
        $this->load->model('race_model');
        // as daar nie 'n province naam deurgestuur word nie
        if ($slug == "index") { redirect("/province/list"); }
        
        // kry eers die ID
        $province_id = $this->province_model->get_province_id_from_slug($slug);
        // kry al die editions vir die provinsie 
        $query_params = [
            "order_by" => ["edition_date" => "ASC"],
            "where" => ["provinces.province_id" => $province_id, "edition_date >= " => date("Y-m-d H:i:s")],
        ];
        
        $this->data_to_views['edition_list'] = $this->race_model->add_race_info($this->edition_model->get_edition_list($query_params));
        if ($this->data_to_views['edition_list']) {
            foreach ($this->data_to_views['edition_list'] as $edition_id => $edition_data) {
                $this->data_to_views['edition_list'][$edition_id]['status_info'] = $this->formulate_status_notice($edition_data);
            }
        } 
        
        $this->data_to_views['page_title'] = "Races in " . str_replace("-"," ",$slug) . " province";
        $this->data_to_views['banner_img'] = "run_04";
        $this->data_to_views['banner_pos'] = "45%";

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
//        $this->load->view('region/calendar', $this->data_to_views);
        if (!$this->data_to_views['edition_list']) {
            $this->load->view('templates/search_form');
        }
        $this->load->view('templates/race_list', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
//        
//        $edition_list = $this->edition_model->get_edition_list($query_params);
//        $this->data_to_views['edition_arr'] = $this->chronologise_data($edition_list, "edition_date");
//        
//        $this->data_to_views['province_id']=$province_id;
//        $this->data_to_views['province_name']=$slug;
//        
//        $this->load->view($this->header_url, $this->data_to_views);
//        $this->load->view('province/calendar', $this->data_to_views);
//        $this->load->view($this->footer_url, $this->data_to_views);
    }
    

}
