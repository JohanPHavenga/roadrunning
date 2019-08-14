<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->load->view($this->header_url,$this->data_to_views);    
        $this->load->view('main/home',$this->data_to_views);
        $this->load->view($this->footer_url);
    }

    public function custom_404() {
        $this->load->view($this->header_url,$this->data_to_views);
        $this->load->view('main/404',$this->data_to_views);
        $this->load->view($this->footer_url,$this->data_to_views);
    }
    
    public function about() {
        $this->load->view($this->header_url,$this->data_to_views);
        $this->load->view('main/about',$this->data_to_views);
        $this->load->view($this->footer_url,$this->data_to_views);
    }
    
    public function terms_conditions() {
        $this->data_to_views['companyName']="RoadRunningZA";
        $this->load->view($this->header_url,$this->data_to_views);
        $this->load->view('main/terms_conditions',$this->data_to_views);
        $this->load->view($this->footer_url,$this->data_to_views);
    }

}
