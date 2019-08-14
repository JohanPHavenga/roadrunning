<?php

// public mailer class to get list from mailques table and send it out
class Sitemap extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('event_model');
    }

    public function index() {
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('sitemap/view', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

}
