<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

    public function index() {

        $this->load->view($this->header_url,$this->data_to_views);    
        $this->load->view('main/home',$this->data_to_views);
        $this->load->view($this->footer_url);
    }

    public function custom_404() {
        $this->load->view($this->header_url);
        $this->load->view('m$this->ain/404',$this->data_to_views);
        $this->load->view($this->footer_url);
    }

}
