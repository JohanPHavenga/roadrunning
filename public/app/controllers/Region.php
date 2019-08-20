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
        if ($slug == "index") { redirect("/region/list"); }
        
        // kry eers die ID
        $region_id = $this->region_model->get_region_id_from_slug($slug);
        // kry al die editions vir die provinsie 
        $query_params = [
            "order_by" => ["edition_date" => "DESC"],
            "where" => ["regions.region_id" => $region_id],
        ];
        $edition_list = $this->edition_model->get_edition_list($query_params);
        $this->data_to_views['edition_arr'] = $this->chronologise_data($edition_list, "edition_date");
        
        $this->data_to_views['region_id']=$region_id;
        $this->data_to_views['region_name']=$slug;
        
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('region/calendar', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }
    

}
