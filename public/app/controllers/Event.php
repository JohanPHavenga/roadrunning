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
            $this->detail($method, $params);
        }
    }

    public function index() {
        redirect("/race-calendar");
    }

    public function detail($slug, $url_params = []) {
        // as daar nie 'n edition_slug deurgestuur word nie
        if ($slug == "index") {
            redirect("/race-calendar");
        } else {
            $this->load->helper('form');
            $this->load->library('form_validation');
            // set a few vars to use
            $this->data_to_views['slug'] = $slug;
            $this->data_to_views['contact_url'] = base_url("contact/event/" . $slug);
            $this->data_to_views['subscribe_url'] = base_url("user/subscribe/event/" . $slug);
        }

        // check vir sub-page
        if ((empty($url_params)) || (!file_exists(APPPATH . "views/event/" . $url_params[0] . ".php"))) {
            $url_params[0] = "summary";
            $this->data_to_views['page_title_small'] = "";
        } else {
            $this->data_to_views['page_title_small'] = "less_padding";
        }
        $this->data_to_views['page_title_small'] = "less_padding"; // make all small banners for now
        $this->data_to_views['url_params'] = $url_params;

        $this->load->model('race_model');
        $this->load->model('file_model');
        $this->load->model('url_model');
        $this->load->model('date_model');
        $this->load->model('entrytype_model');
        $this->load->model('regtype_model');
        $this->load->model('tag_model');

//        wts($this->data_to_views['crumbs_arr'],true);
        // gebruik slug om ID te kry
        $edition_sum = $this->edition_model->get_edition_id_from_slug($slug);
        $edition_id = $edition_sum['edition_id'];
        $this->data_to_views['edition_data'] = $edition_data = $this->edition_model->get_edition_detail($edition_id);

        // lists
        $this->data_to_views['race_list'] = $this->race_model->get_race_list(["where" => ["races.edition_id" => $edition_id]]);
        $this->data_to_views['file_list'] = $file_list = $this->file_model->get_file_list("edition", $edition_id, true);
        $this->data_to_views['url_list'] = $url_list = $this->url_model->get_url_list("edition", $edition_id, true);
        $this->data_to_views['date_list'] = $this->date_model->get_date_list("edition", $edition_id, false, true);
        $this->data_to_views['tag_list'] = $tag_list = $this->tag_model->get_edition_tag_list($edition_id);

        // extended edition info
        $this->data_to_views['edition_data']['race_summary'] = $this->get_set_race_suammry($this->data_to_views['race_list'], $edition_data['edition_date'], $edition_data['edition_info_prizegizing']);
        $this->data_to_views['edition_data']['entrytype_list'] = $this->entrytype_model->get_edition_entrytype_list($edition_id);
        $this->data_to_views['edition_data']['regtype_list'] = $this->regtype_model->get_edition_regtype_list($edition_id);
        $this->data_to_views['edition_data']['club_url_list'] = $this->url_model->get_url_list("club", $edition_data['club_id'], false);

        // calc values
        if (strtotime($edition_data['edition_date']) < time()) {
            $this->data_to_views['in_past'] = true;
        } else {
            $this->data_to_views['in_past'] = false;
        }
        $this->data_to_views['address'] = $edition_data['edition_address_end'] . ", " . $edition_data['town_name'];
        $this->data_to_views['address_nospaces'] = url_title($this->data_to_views['address'] . ", ZA");
        $this->data_to_views['page_title'] = substr($edition_data['edition_name'], 0, -5) . " - " . fdateTitle($edition_data['edition_date']);
        $this->data_to_views['page_menu'] = $this->get_event_menu($slug, $edition_data['event_id'], $edition_id);
        $this->data_to_views['status_notice'] = $this->formulate_status_notice($edition_data);
        $this->data_to_views['race_status_name'] = $this->edition_model->get_status_name($edition_data['edition_info_status']);

        $gps_parts = explode(",", $edition_data['edition_gps']);
        $this->data_to_views['gps']['lat'] = $gps_parts[0];
        $this->data_to_views['gps']['long'] = $gps_parts[1];
        $this->data_to_views['edition_date_minus_one'] = date("Y-m-d", strtotime($edition_data['edition_date']) - 86400);

        // google data
        $this->data_to_views['structured_data'] = $this->load->view('/event/structured_data', $this->data_to_views, TRUE);
        $this->data_to_views['google_cal_url'] = $this->formulate_google_cal($edition_data, $this->data_to_views['race_list']);

        // if results loaded, get URLS to use
        if ($edition_data['edition_info_status'] == 11) {
            $this->data_to_views['results'] = $this->get_result_arr($slug);
        }
        if ((isset($this->data_to_views['url_list'][8])) || (isset($this->data_to_views['file_list'][7]))) {
            $this->data_to_views['route_maps'] = $this->get_routemap_arr($slug);
        }

//        wts($this->data_to_views['file_list'],true);

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('templates/banner_event', $this->data_to_views);
        $this->load->view('templates/page_menu', $this->data_to_views);
        $this->load->view('event/' . $url_params[0], $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    private function get_result_arr($slug) {
        $results=[];
        if (isset($this->data_to_views['file_list'][4])) {
            $results['edition']['url'] = base_url("file/edition/" . $slug . "/results/" . $this->data_to_views['file_list'][4][0]['file_name']);
            $results['edition']['text'] = "Download results summary";
            $results['edition']['icon'] = "file-excel";
        } elseif (isset($this->data_to_views['url_list'][4])) {
            $results['edition']['url'] = $this->data_to_views['url_list'][4][0]['url_name'];
            $results['edition']['text'] = "View results";
            $results['edition']['icon'] = "external-link-alt";
        }

        // get race file and url lists
        foreach ($this->data_to_views['race_list'] as $race_id => $race) {
            $race_file_list = $this->file_model->get_file_list("race", $race_id, true);
            $race_url_list = $this->url_model->get_url_list("race", $race_id, true);
            if (isset($race_file_list[4])) {
                $results['race'][$race_id]['url'] = base_url("file/race/" . $slug . "/results/" . url_title($race['race_name']) . "/" . $race_file_list[4][0]['file_name']);
                $results['race'][$race_id]['text'] = $race['race_name'] . " " . $race_file_list[4][0]['filetype_buttontext'];
                $results['race'][$race_id]['icon'] = "file-excel";
            } elseif (isset($race_url_list[4])) {
                $results['race'][$race_id]['url'] = $race_url_list[4][0]['url_name'];
                $results['race'][$race_id]['text'] = $race['race_name'] . " Results";
                $results['race'][$race_id]['icon'] = "external-link-alt";
            }
        }

        return $results;
    }
    
    private function get_routemap_arr($slug) {
        $route_maps=[];
        if (isset($this->data_to_views['file_list'][7])) {
            $route_maps['edition']['url'] = base_url("file/edition/" . $slug . "/route map/" . $this->data_to_views['file_list'][7][0]['file_name']);
            $route_maps['edition']['text'] = "Download route map";
            $route_maps['edition']['icon'] = "file-image";
        } elseif (isset($this->data_to_views['url_list'][8])) {
            $route_maps['edition']['url'] = $this->data_to_views['url_list'][8][0]['url_name'];
            $route_maps['edition']['text'] = "View Route Map";
            $route_maps['edition']['icon'] = "external-link-alt";
        }

        // get race file and url lists
        foreach ($this->data_to_views['race_list'] as $race_id => $race) {
            $race_file_list = $this->file_model->get_file_list("race", $race_id, true);
            $race_url_list = $this->url_model->get_url_list("race", $race_id, true);
            if (isset($race_file_list[7])) {
                $route_maps['race'][$race_id]['url'] = base_url("file/race/" . $slug . "/route map/" . url_title($race['race_name']) . "/" . $race_file_list[7][0]['file_name']);
                $route_maps['race'][$race_id]['text'] = $race['race_name'] . " " . $race_file_list[7][0]['filetype_buttontext'];
                $route_maps['race'][$race_id]['icon'] = "file-image";
            } elseif (isset($race_url_list[8])) {
                $route_maps['race'][$race_id]['url'] = $race_url_list[8][0]['url_name'];
                $route_maps['race'][$race_id]['text'] = $race['race_name'] . " Route Map";
                $route_maps['race'][$race_id]['icon'] = "external-link-alt";
            }
        }

        return $route_maps;
    }

    private function get_set_race_suammry($race_list, $edition_date, $prize_giving_time) {
        if (!$race_list) {
            return false;
        }
        $return_arr = [
            "times" => [
                "start" => "",
                "end" => ""
            ],
            "fees" => [
                "from" => 10000,
                "to" => 0
            ],
            "list" => [],
        ];

//        wts($race_list,true);

        foreach ($race_list as $race) {
            // START TIME
            $start_datetime = strtotime($edition_date) + 86400;
            if (strtotime($race['race_date']) == strtotime($edition_date)) {


                if ((strtotime($edition_date) + time_to_sec($race['race_time_start'])) < $start_datetime) {
                    $start_datetime = strtotime($edition_date) + time_to_sec($race['race_time_start']);
                }
                $return_arr['times']['start'] = date("Y-m-d H:i:s", $start_datetime);

                // END TIME
                if (time_to_sec($prize_giving_time) > 0) {
                    $return_arr['times']['end'] = date("Y-m-d H:i:s", strtotime($edition_date) + time_to_sec($prize_giving_time));
                }
            }

            // FEES
            $fee_fields_to_check = ['race_fee_flat', 'race_fee_senior_licenced', 'race_fee_senior_unlicenced'];
            foreach ($fee_fields_to_check as $field) {
                if ($race[$field] != 0) {
                    if ($race[$field] < $return_arr['fees']['from']) {
                        $return_arr['fees']['from'] = $race[$field];
                    }
                    if ($race[$field] > $return_arr['fees']['to']) {
                        $return_arr['fees']['to'] = $race[$field];
                    }
                }
            }
            // LIST
            $return_arr['list'][] = [
                'distance' => $race['race_distance'],
                'type' => $race['racetype_name'],
                'abbr' => $race['racetype_abbr'],
                'color' => $race['race_color'],
                'name' => $race['race_name'],
            ];
        }
        return $return_arr;
    }

    private function get_event_start_end_times($edition_date, $prize_giving_time, $race_list) {
        $return_arr = [
            "start" => "",
            "end" => ""
        ];
        // START
        $start_datetime = strtotime($edition_date) + 86400;
        foreach ($race_list as $race) {
            if (strtotime($race['race_date']) > 0) {
                continue;
            }

            if ((strtotime($edition_date) + time_to_sec($race['race_time_start'])) < $start_datetime) {
                $start_datetime = strtotime($edition_date) + time_to_sec($race['race_time_start']);
            }
        }
        $return_arr['start'] = date("Y-m-d H:i:s", $start_datetime);
        // END
        if (time_to_sec($prize_giving_time) > 0) {
            $return_arr['end'] = date("Y-m-d H:i:s", strtotime($edition_date) + time_to_sec($prize_giving_time));
        }
        return $return_arr;
    }

    private function get_fee_from_to($race_list) {
        $return_arr = [
            "from" => 10000,
            "to" => 0
        ];
        foreach ($race_list as $race) {
            $fields_to_check = ['race_fee_flat', 'race_fee_senior_licenced', 'race_fee_senior_unlicenced'];
            // check flat fee
            foreach ($fields_to_check as $field) {
                if ($race[$field] != 0) {
                    if ($race[$field] < $return_arr['from']) {
                        $return_arr['from'] = $race[$field];
                    }
                    if ($race[$field] > $return_arr['to']) {
                        $return_arr['to'] = $race[$field];
                    }
                }
            }
        }
        return $return_arr;
    }

    private function get_event_menu($slug, $event_id, $edition_id) {

        $menu_arr = [
            "summary" => [
                "display" => "Summary",
                "loc" => base_url("event/" . $slug),
            ],
            "entries" => [
                "display" => "How to enter",
                "loc" => base_url("event/" . $slug . "/entries"),
            ],
            "race_day" => [
                "display" => "Race day info",
                "loc" => base_url("event/" . $slug . "/race-day-information"),
            ],
            "distances" => [
                "display" => "Distances",
                "loc" => base_url("event/" . $slug . "/distances"),
            ],
            "route_maps" => [
                "display" => "Route Maps",
                "loc" => base_url("event/" . $slug . "/route-maps"),
            ],
            "results" => [
                "display" => "Results",
                "loc" => base_url("event/" . $slug . "/results"),
            ],
            "contact" => [
                "display" => "Race Contact",
                "loc" => base_url("event/" . $slug . "/contact"),
            ],
            "more" => [
                "display" => "More",
                "sub_menu" => [
                    "accom" => [
                        "display" => "Accommodation",
                        "loc" => base_url("event/" . $slug . "/accommodation"),
                    ],
                    "subscribe" => [
                        "display" => "Get Notifications",
                        "loc" => base_url("event/" . $slug . "/subscribe"),
                    ],
                    "sponsors" => [
                        "display" => "Sponsors",
                        "loc" => base_url("event/" . $slug . "/sponsors"),
                    ],
                    "club" => [
                        "display" => "More events by this Club",
                        "loc" => base_url("event/" . $slug . "/club-other-races"),
                    ],
                    "previous" => [
                        "display" => "Previous year's edition",
                        "loc" => base_url("event/" . $slug . "/"),
                    ],
                    "next" => [
                        "display" => "Next year's edition",
                        "loc" => base_url("event/" . $slug . "/"),
                    ],
                    "print" => [
                        "display" => "Print",
                        "loc" => base_url("event/" . $slug . "/print"),
                    ],
                ],
            ],
        ];

        // get event history to know if links should be in menu
        $event_history = $this->get_event_history($event_id, $edition_id);
        // previous
        if (isset($event_history['past'])) {
            $menu_arr['more']['sub_menu']['previous']['loc'] = base_url("event/" . $event_history['past']['edition_slug']);
        } else {
            unset($menu_arr['more']['sub_menu']['previous']);
        }
        //next
        if (isset($event_history['future'])) {
            $menu_arr['more']['sub_menu']['next']['loc'] = base_url("event/" . $event_history['future']['edition_slug']);
        } else {
            unset($menu_arr['more']['sub_menu']['next']);
        }

        // check if in past else hide to hide accommodation link
        if ($this->data_to_views['in_past']) {
            unset($menu_arr['more']['sub_menu']['accom']);
        }

        // check for route maps
//        if ((!isset($this->data_to_views['url_list'][8])) && (!isset($this->data_to_views['file_list'][7]))) {
//            unset($menu_arr['route_maps']);
//        }
        // to use later
        unset($menu_arr['more']['sub_menu']['sponsors']);
        unset($menu_arr['more']['sub_menu']['club']);
        unset($menu_arr['more']['sub_menu']['print']);
        return $menu_arr;
    }

    public function get_event_history($event_id, $edition_id) {
        $return = [];
        // get list of editions linked to this event
        $query_params = [
            "where" => ["event_id" => $event_id],
        ];
        $edition_list = $this->edition_model->get_edition_list($query_params);

        // remove the one you are looking at
        $current_year = date("Y", strtotime($edition_list[$edition_id]['edition_date']));
        unset($edition_list[$edition_id]);

        if ($edition_list) {
            foreach ($edition_list as $edition) {
                $edition['edition_year'] = fdateYear($edition['edition_date']);
                if ($edition['edition_year'] < $current_year) {
                    if (isset($return['past'])) {
                        if ($edition['edition_year'] > $return['past']['edition_year']) {
                            $return['past'] = $edition;
                        }
                    } else {
                        $return['past'] = $edition;
                    }
                } elseif ($edition['edition_year'] > $current_year) {
                    if (isset($return['future'])) {
                        if ($edition['edition_year'] < $return['future']['edition_year']) {
                            $return['future'] = $edition;
                        }
                    } else {
                        $return['future'] = $edition;
                    }
                }
            }
        }
        return $return;
    }

    function ics($edition_slug) {

        $this->load->model('edition_model');
        $this->load->model('race_model');

        $edition_info = $this->edition_model->get_edition_id_from_slug($edition_slug);
        $edition_id = $edition_info['edition_id'];

        $edition_data = $this->edition_model->get_edition_detail($edition_id);
        $race_list = $this->race_model->get_race_list(["where" => ["races.edition_id" => $edition_id]]);

        $edition_url = base_url("event/" . $edition_data['edition_slug']);
        $address = $edition_data['edition_address_end'] . ", " . $edition_data['town_name'];


        // dates
        $date = $edition_data['edition_date'];
        $time = "23:59:00";
        foreach ($race_list as $race) {
            $race_time = $race['race_time_start'];
            if ($race_time < $time) {
                $time = $race_time;
            }
        }
        $sdate = strtotime(str_replace("00:00:00", $time, $date));
        if ($edition_data['edition_info_prizegizing'] != "00:00:00") {
            $edate = strtotime(str_replace("00:00:00", $edition_data['edition_info_prizegizing'], $date));
        } else {
            $edate = $sdate + (5 * 60 * 60);
        }

        $this->data_to_views['summary'] = $edition_data['edition_name'];
        $this->data_to_views['datestart'] = $sdate;
        $this->data_to_views['dateend'] = $edate;
        $this->data_to_views['address'] = $address;
        $this->data_to_views['uri'] = $edition_url;
        $this->data_to_views['description'] = "website: " . $edition_url;
        $this->data_to_views['filename'] = $edition_slug . ".ics";
        $this->data_to_views['uid'] = $edition_id;

        $this->load->view("/event/ics", $this->data_to_views);

//        wts($this->data_to_views);
//        wts($edition_info);
//        die($edition_id);
    }

    function formulate_google_cal($edition_data, $race_list) {


        $base_url = "http://www.google.com/calendar/event?action=TEMPLATE&trp=true";

        $edition_url = base_url("event/" . $edition_data['edition_slug']);
        $address = $edition_data['edition_address_end'] . ", " . $edition_data['town_name'];
        $text = $edition_data['edition_name'];

        // dates
        $date = $edition_data['edition_date'];
        $time = "23:59:00";
        foreach ($race_list as $race) {
            $race_time = $race['race_time_start'];
            if ($race_time < $time) {
                $time = $race_time;
            }
        }
        $sdate = strtotime(str_replace("00:00:00", $time, $date));
        if ($edition_data['edition_info_prizegizing'] != "00:00:00") {
            $edate = strtotime(str_replace("00:00:00", $edition_data['edition_info_prizegizing'], $date));
        } else {
            $edate = $sdate + (5 * 60 * 60);
        }
        $dates = fdateToCal($sdate) . "/" . fdateToCal($edate);
//        echo $dates;
//        wts($edition_data,true);

        $sprop = "website:" . $edition_url;
        $details = "website:" . $edition_url;
        $location = urlencode($address);

        return $base_url . "&text=" . $text . "&dates=" . $dates . "&details=" . $details . "&location=" . $location;
    }

}
