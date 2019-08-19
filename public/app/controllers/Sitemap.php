<?php

// public mailer class to get list from mailques table and send it out
class Sitemap extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('edition_model');
        // selle vir altwee functions
        $query_params = [
            "order_by" => ["edition_date" => "DESC"],
//            "where" => ["edition_date > " => date("Y-m-d H:i:s")],
        ];
        $edition_list = $this->edition_model->get_edition_list($query_params);

        $this->data_to_views['static_pages'] = $this->get_static_pages();
        $this->data_to_views['edition_arr'] = $this->chronologise_data($edition_list, "edition_date");
    }

    public function index() {

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('sitemap/view', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    function xml() {
        foreach ($this->data_to_views['edition_arr'] as $year => $year_list) {
            foreach ($year_list as $month_list) {
                foreach ($month_list as $edition_list) {
                    foreach ($edition_list as $edition_id => $edition) {
                        // SET loc
                        $loc=base_url("event/".$edition['edition_slug']);
                        // SET lastmod
                        if (!empty($edition['updated_date'])) { $lastmod = $edition['updated_date']; } else { $lastmod = $edition['created_date']; }
                        // SET priority and changefreq
                        $priority=0.1;
                        $changefreq="never";
                        // if race in next 12 months, or past 6 month
                        if (($edition['edition_date'] < date("Y-m-d H:m:s",strtotime("1 year"))) && ($edition['edition_date'] > date("Y-m-d H:m:s",strtotime("-6 months")))) { 
                            $priority = 0.5;
                            $changefreq = "monthly";
                        }
                        // if race in next 6 months, or past 2 month
                        if (($edition['edition_date'] < date("Y-m-d H:m:s",strtotime("6 months"))) && ($edition['edition_date'] > date("Y-m-d H:m:s",strtotime("-2 months")))) { 
                            $priority = 0.8;
                            $changefreq = "weekly";
                        }
                        if ($lastmod < "") {}
                        $this->data_to_views['edition_list_xml'][$edition_id]['loc'] = $loc;
                        $this->data_to_views['edition_list_xml'][$edition_id]['lastmod'] = $lastmod;
                        $this->data_to_views['edition_list_xml'][$edition_id]['priority'] = $priority;
                        $this->data_to_views['edition_list_xml'][$edition_id]['changefreq'] = $changefreq;
                    }
                }
            }
        }
        $this->load->view("sitemap/xml", $this->data_to_views);
    }

}
