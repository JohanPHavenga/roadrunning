<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {
	
    public function index()
    {
            $this->load->view('main/home');
    }
    
    public function custom_404()
    {
            $this->load->view('main/404');
    }
    
}
