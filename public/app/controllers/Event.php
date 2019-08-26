<?php

// public mailer class to get list from mailques table and send it out
class Event extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('edition_model');
    }
    
    // check if method exists, if not calls "view" method
    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->detail($method, $params);
        }
    }
    
    public function index() { 
        redirect("/race-calendar"); 
    }

    public function detail($slug, $url_params=[]) {   
        // as daar nie 'n edition_slug deurgestuur word nie
        if ($slug == "index") { redirect("/race-calendar"); }
        
        $this->load->model('race_model');
        $this->load->model('file_model');
        $this->load->model('url_model');
        
        // gebruik slug om ID te kry
        $edition_data = $this->edition_model->get_edition_id_from_slug($slug);
        $this->data_to_views['edition_data'] = $this->edition_model->get_edition_detail($edition_data['edition_id']);
        $this->data_to_views['race_list'] = $this->race_model->get_race_list($edition_data['edition_id']);
        $this->data_to_views['file_list'] = $this->file_model->get_file_list("edition",$edition_data['edition_id'],true);
        $this->data_to_views['url_list'] = $this->url_model->get_url_list("edition",$edition_data['edition_id']);

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('event/detail', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }
}
