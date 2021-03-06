<?php

// public mailer class to get list from mailques table and send it out
class Sitemap extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('edition_model');
        $this->load->model('province_model');
        $this->load->model('region_model');
        // selle vir altwee functions
        $query_params = [
            "order_by" => ["edition_date" => "DESC"],
            "where" => ["edition_status != " => 2],
        ];
        $edition_list = $this->edition_model->get_edition_list($query_params);
        $this->data_to_views['edition_arr'] = $this->chronologise_data($edition_list, "edition_date");
    }

    public function index() {
//        wts($this->data_to_views['edition_arr'],true);
        $this->data_to_views['banner_img'] = "run_01";
        $this->data_to_views['banner_pos'] = "40%";
        $this->data_to_views['page_title'] = "Sitemap";
        $this->data_to_views['meta_description'] = "Sitemap linking to all the pages on the website";

        foreach ($this->data_to_views['edition_arr'] as $year => $year_list) {

            $this->data_to_views['calendar'][$year]['loc'] = base_url() . "calendar/" . $year;
            $this->data_to_views['calendar'][$year]['display'] = $year;

            foreach ($year_list as $month => $month_list) {
                $month_num = date("m", strtotime("$month-$year"));
                $this->data_to_views['calendar']["$year-$month_num"]['loc'] = base_url() . "calendar/" . $year . "/" . $month_num;
                $this->data_to_views['calendar']["$year-$month_num"]['display'] = $month . " " . $year;
            }
        }

//        wts($this->data_to_views['calendar'], 1);

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('sitemap/view', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    function xml() {
        foreach ($this->data_to_views['edition_arr'] as $year => $year_list) {

            if ($year == date("Y")) {
                $lastmod = date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 week"));
                $priority = 0.5;
                $changefreq = "weekly";
            } else {
                $lastmod = date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year"));
                $priority = 0.2;
                $changefreq = "yearly";
            }
            $this->data_to_views['calendar_xml'][$year]['loc'] = base_url() . "calendar/" . $year;
            $this->data_to_views['calendar_xml'][$year]['lastmod'] = $lastmod;
            $this->data_to_views['calendar_xml'][$year]['priority'] = $priority;
            $this->data_to_views['calendar_xml'][$year]['changefreq'] = $changefreq;

            foreach ($year_list as $month => $month_list) {

                $month_num = date("m", strtotime("$month-$year"));

                // in last 3 months + future
                $todayDate = time();
                $dateToCheck = strtotime("$month-$year");
                if (($dateToCheck > $todayDate) || (($todayDate - $dateToCheck) < 7889238)) {
                    $lastmod = date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 week"));
                    $priority = 0.8;
                    $changefreq = "weekly";
                    $this->data_to_views['calendar_xml'][$year]['lastmod'] = $lastmod;
                    $this->data_to_views['calendar_xml'][$year]['priority'] = $priority;
                    $this->data_to_views['calendar_xml'][$year]['changefreq'] = $changefreq;
                } else {
                    $lastmod = date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year"));
                    $priority = 0.2;
                    $changefreq = "yearly";
                }
                $this->data_to_views['calendar_xml']["$year-$month_num"]['loc'] = base_url() . "calendar/" . $year . "/" . $month_num;
                $this->data_to_views['calendar_xml']["$year-$month_num"]['lastmod'] = $lastmod;
                $this->data_to_views['calendar_xml']["$year-$month_num"]['priority'] = $priority;
                $this->data_to_views['calendar_xml']["$year-$month_num"]['changefreq'] = $changefreq;

                foreach ($month_list as $edition_list) {
                    foreach ($edition_list as $edition_id => $edition) {
                        // SET loc
                        $loc = base_url("event/" . $edition['edition_slug']);
                        // SET lastmod
                        if (!empty($edition['updated_date'])) {
                            $lastmod = $edition['updated_date'];
                        } else {
                            $lastmod = $edition['created_date'];
                        }
                        // SET priority and changefreq
                        $priority = 0.1;
                        $changefreq = "never";
                        // if race in next 12 months, or past 6 month
                        if (($edition['edition_date'] < date("Y-m-d H:i:s ", strtotime("1 year"))) && ($edition['edition_date'] > date("Y-m-d H:i:s ", strtotime("-6 months")))) {
                            $priority = 0.5;
                            $changefreq = "monthly";
                        }
                        // if race in next 3 months
                        if (($edition['edition_date'] < date("Y-m-d H:i:s ", strtotime("3 months"))) && ($edition['edition_date'] >= date("Y-m-d H:i:s ", strtotime("today")))) {
                            $priority = 1;
                            $changefreq = "weekly";
                        }
                        // if race in past 1 month
                        if (($edition['edition_date'] <= date("Y-m-d H:i:s ", strtotime("today"))) && ($edition['edition_date'] > date("Y-m-d H:i:s ", strtotime("-1 month")))) {
                            $priority = 0.8;
                            $changefreq = "weekly";
                        }
                        if ($lastmod < "") {
                            
                        }
                        $lastmod=date('Y-m-d\TH:i:s' . '+02:00', strtotime($lastmod));
                        $this->data_to_views['edition_list_xml'][$edition_id]['loc'] = $loc;
                        $this->data_to_views['edition_list_xml'][$edition_id]['lastmod'] = $lastmod;
                        $this->data_to_views['edition_list_xml'][$edition_id]['priority'] = $priority;
                        $this->data_to_views['edition_list_xml'][$edition_id]['changefreq'] = $changefreq;

                        // SUB PAGES
                        if (strtotime($edition['edition_date']) < time()) {
                            $in_past = true;
                        } else {
                            $in_past = false;
                        }
                        $skip_pages = ["summary", "previous", "next"];
                        $page_menu = $this->get_event_menu($edition['edition_slug'], $edition['event_id'], $edition_id, $in_past);
                        foreach ($page_menu as $item_slug => $item) {
                            if (in_array($item_slug, $skip_pages)) {
                                continue;
                            }
                            if ($item_slug == "more") {
                                foreach ($item['sub_menu'] as $sub_item_slug => $sub_item) {
                                    if (in_array($sub_item_slug, $skip_pages)) {
                                        continue;
                                    }
                                    $key = $edition_id . $sub_item_slug;
                                    $this->data_to_views['edition_list_xml'][$key]['loc'] = $sub_item['loc'];
                                    $this->data_to_views['edition_list_xml'][$key]['lastmod'] = $lastmod;
                                    $this->data_to_views['edition_list_xml'][$key]['priority'] = $priority;
                                    $this->data_to_views['edition_list_xml'][$key]['changefreq'] = $changefreq;
                                }
                            } else {
                                $key = $edition_id . $item_slug;
                                $this->data_to_views['edition_list_xml'][$key]['loc'] = $item['loc'];
                                $this->data_to_views['edition_list_xml'][$key]['lastmod'] = $lastmod;
                                $this->data_to_views['edition_list_xml'][$key]['priority'] = $priority;
                                $this->data_to_views['edition_list_xml'][$key]['changefreq'] = $changefreq;
                            }
                        }
//                        wts($page_menu);
                    }
                }
            }
        }
//        wts($this->data_to_views['edition_list_xml'], 1);

        $this->load->view("sitemap/xml", $this->data_to_views);
    }

}
