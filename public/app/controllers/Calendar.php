<?php
// Race Calendar
class Calendar extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('edition_model');
    }

    public function index() {
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('calendar/view', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

}
