<?php

class Calendar extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('event_model');
    }

    // check if method exists, if not calls "view" method
    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->upcoming($method, $params);
        }
    }

    public function upcoming($year = NULL, $args = array()) {
        // load helpers / libraries
        $this->load->library('table');
        $this->data_to_header['section'] = "events";

        $this->data_to_header['title'] = "Running Events Calendar";
        $this->data_to_header['page_heading'] = "Upcoming Running Events Calendar";
        $this->data_to_header['meta_description'] = "List of upcoming road running race events";
        $this->data_to_header['keywords'] = "Calendar, Upcoming, Races, Events, Listing, Race, Run, Marathon, Half-Marathon, 10k, Fun Run";


        if (is_numeric($year)) {

            $day = 1;
            $month = 1;
            $t_month = 12;
            $to_date_format = "Y-m-t";

            $this->data_to_header['title'] = "Road Running Races in " . $year;
            $this->data_to_header['page_heading'] = "Running Events for <strong>" . $year . "</strong>";
            $crumbs = [
                "Home" => "/",
                "Events Calendar" => base_url("calendar"),
                "$year" => base_url("calendar/" . $year),
            ];

            if (isset($args[0])) {
                $month = $t_month = $args[0];
                $month_name = date('F', mktime(0, 0, 0, $month, $day, $year));
                $this->data_to_header['title'] = "Road Running Races in " . $month_name . " " . $year;
                $this->data_to_header['page_heading'] = "Running Events for <strong>" . $month_name . " " . $year . "</strong>";
                $crumbs[$month_name] = base_url("calendar/" . $year . "/" . $month);

                if (isset($args[1])) {

                    $day = $args[1];
                    $this->data_to_header['title'] = "Road Running Races on " . $day . " " . $month_name . " " . $year;
                    $this->data_to_header['page_heading'] = "Running Events on <strong>" . $day . " " . $month_name . " " . $year . "</strong>";
                    $to_date_format = "Y-m-d";
                    $dotw = date('l jS', mktime(0, 0, 0, $month, $day, $year));
                    $crumbs[$dotw] = base_url("calendar/" . $year . "/" . $month . "/" . $day);
                }
            } else {
                $month = 1;
                $t_month = 12;
            }
            // check for valid dat, else 404
            if (!checkdate($month, $day, $year)) {
                redirect("404");
            }

            // set to and from dates
            $from = date("Y-m-d", mktime(0, 0, 0, $month, $day, $year));
            $to = date($to_date_format, mktime(0, 0, 0, $t_month, $day, $year));
            $params = ["date_from" => $from, "date_to" => $to];
        } else {
            $params = ["date_from" => date("Y-m-d")];
            $crumbs = [
                "Home" => "/",
                "Events Calendar" => base_url("calendar"),
            ];
        }
        // set crumbs;
        $this->crumbs_arr = array_reverse($crumbs, true);

//        $params["only_active"]=true;
        // get race info
        $race_summary = $this->event_model->get_event_list_summary($from = "date_range", $params);
        // render html
        $this->data_to_view['upcoming_race_list_html'] = $this->render_races_accordian_html($race_summary);

        // set title bar
        $this->data_to_view["title_bar"] = $this->render_topbar_html(
                [
                    "title" => $this->data_to_header['title'],
                    "crumbs" => $this->crumbs_arr,
        ]);

        // load view
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("calendar/list", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function results($year = NULL) {
        // load helpers / libraries
        $this->load->library('table');
        $this->data_to_header['section'] = "results";

        $this->data_to_header['title'] = "Results Calendar";
        $this->data_to_header['meta_description'] = "List of results for running races";
        $this->data_to_header['keywords'] = "Results, Past, Races, Events, Listing, Race, Run, Marathon, Half-Marathon, 10k, Fun Run";

        // get race info
        $past_date = date("Y-m-d", strtotime("-11 months", time()));
        $past_race_summary = $this->event_model->get_event_list_summary($from = "date_range", $params = ["date_from" => $past_date, "date_to" => date("Y-m-d"), "sort" => "DESC", "only_active" => true]);
        // render html
        $this->data_to_view['past_race_list_html'] = $this->render_races_accordian_html($past_race_summary);

        $this->crumbs_arr = [
            "Results" => base_url("calendar/results"),
            "Home" => "/",
        ];

        // set title bar
        $this->data_to_view["title_bar"] = $this->render_topbar_html(
                [
                    "title" => $this->data_to_header['title'],
                    "crumbs" => $this->crumbs_arr,
        ]);

        // load view
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/calendar/results", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    // to be changed.

    function formulate_structured_data($event_detail) {
        $start_date = date("Y-m-d", strtotime($event_detail['edition_date']));

        $h[] = '<script type="application/ld+json">';
        $h[] = '{';
        $h[] = '"@context": "http://schema.org",';
        $h[] = '"@type": "SportsEvent",';
        $h[] = '"name": "' . $event_detail['edition_name'] . '",';
        $h[] = '"startDate": "' . $start_date . '",';
        $h[] = '"location": { ';
        $h[] = '"@type": "Place",';
        $h[] = '"name": "' . $event_detail['edition_address_end'] . '",';
        $h[] = '"address": { ';
        $h[] = '"@type": "PostalAddress",';
        $h[] = '"streetAddress": "' . $event_detail['edition_address_end'] . '",';
        $h[] = '"addressLocality": "' . $event_detail['town_name'] . '",';
        $h[] = '"addressRegion": "WC",';
        $h[] = '"addressCountry": "ZA"';
        $h[] = '}';
        $h[] = '},';
        $h[] = '"description": "Join us for the annual ' . $event_detail['event_name'] . ' road running race in ' . $event_detail['town_name'] . '.",';
        if ($event_detail['edition_logo']) {
            $img_url = base_url("uploads/admin/edition/" . $event_detail['edition_id'] . "/" . $event_detail['edition_logo']);
            $h[] = '"image": "' . $img_url . '",';
        }

        $h[] = '"subEvent": [ ';

        foreach ($event_detail['race_list'] as $race) {
            if (!empty($race['race_name'])) {
                $rn = $race['race_name'];
            } else {
                $rn = fraceDistance($race['race_distance']) . " " . $race['racetype_name'];
            }
            if ($race['race_fee_flat'] > 0) {
                $price = $race['race_fee_flat'];
            } elseif ($race['race_fee_senior_licenced'] > 0) {
                $price = $race['race_fee_senior_licenced'];
            } else {
                $price = 0;
            }
            if ($race['race_date'] > 0) {
                $race_start_date = date("Y-m-d", strtotime($race['race_date']));
            } else {
                $race_start_date = $start_date;
            }

            $today_date = date("Y-m-d") . 'T' . "00:00:00+02:00";

            $h[] = '{';
            $h[] = '"@type": "SportsEvent",';
            $h[] = '"name": "' . $rn . '",';
            $h[] = '"startDate": "' . $race_start_date . 'T' . $race['race_time_start'] . '+02:00",';
            if ($race['race_time_end']) {
                $h[] = '"endDate": "' . $race_start_date . 'T' . $race['race_time_end'] . '+02:00",';
            }
            $h[] = '"location": { ';
            $h[] = '"@type": "Place",';
            $h[] = '"name": "' . $event_detail['edition_address_end'] . '",';
            $h[] = '"address": { ';
            $h[] = '"@type": "PostalAddress",';
            $h[] = '"streetAddress": "' . $event_detail['edition_address_end'] . '",';
            $h[] = '"addressLocality": "' . $event_detail['town_name'] . '",';
            $h[] = '"addressRegion": "WC",';
            $h[] = '"addressCountry": "ZA"';
            $h[] = '}';
            if ($price > 0) {
                $h[] = '},';
                $h[] = '"offers": { ';
                $h[] = '"@type": "Offer",';
                $h[] = '"price": "' . $price . '",';
                $h[] = '"priceCurrency": "ZAR",';
                if ($event_detail['edition_url_entry']) {
                    $h[] = '"url": "' . $event_detail['edition_url_entry'] . '",';
                    $h[] = '"availability": "http://schema.org/InStock",';
                    $h[] = '"validFrom": "' . $today_date . '"';
                }
                $h[] = '}';
            } else {
                $h[] = '}';
            }

            // loose comma at the end of the last one
            if ($race === end($event_detail['race_list'])) {
                $h[] = '}';
            } else {
                $h[] = '},';
            }
        }

        $h[] = ']';

        $h[] = '}';
        $h[] = '</script>';

        $html = implode("\n\r", $h);

        //    echo $html;
        //    wts($event_detail);
        //    die();

        return $html;
    }

}
