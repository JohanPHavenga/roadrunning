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
            $this->detail($method, $params = array());
        }
    }
    
    public function index() { 
        redirect("/race-calendar"); 
    }

    public function detail($slug) {   
        // as daar nie 'n edition_slug deurgestuur word nie
        if ($slug == "index") { redirect("/race-calendar"); }
        
        // gebruik slug om ID te kry
        $this->data_to_views['edition_data'] = $this->edition_model->get_edition_id_from_slug($slug);

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('event/detail', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

}
