<?php

// public mailer class to get list from mailques table and send it out
class Province extends MY_Controller {

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
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('province/list', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function calendar($slug) {   
        $this->load->model('edition_model');
        // as daar nie 'n province naam deurgestuur word nie
        if ($slug == "index") { redirect("/province/list"); }
        
        // kry eers die ID
        $province_id = $this->province_model->get_province_id_from_slug($slug);
        // kry al die editions vir die provinsie 
        $query_params = [
            "order_by" => ["edition_date" => "DESC"],
            "where" => ["provinces.province_id" => $province_id],
        ];
        $edition_list = $this->edition_model->get_edition_list($query_params);
        $this->data_to_views['edition_arr'] = $this->chronologise_data($edition_list, "edition_date");
        
        $this->data_to_views['province_id']=$province_id;
        $this->data_to_views['province_name']=$slug;
        
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('province/calendar', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }
    

}
