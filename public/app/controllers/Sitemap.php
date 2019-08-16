<?php

// public mailer class to get list from mailques table and send it out
class Sitemap extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('edition_model');
    }

    public function index() {
        $query_params = [
            "order_by" => ["edition_date" => "DESC"],
//            "where" => ["edition_date > " => date("Y-m-d H:i:s")],
        ];
        $edition_list = $this->edition_model->get_edition_list($query_params);

        $this->data_to_views['static_pages'] = $this->get_static_pages();
        $this->data_to_views['edition_list'] = $this->chronologise_data($edition_list, "edition_date");

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('sitemap/view', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

}
