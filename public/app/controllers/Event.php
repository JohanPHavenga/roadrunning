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
        $this->data_to_views['url_params']=$url_params;

        $this->load->model('race_model');
        $this->load->model('file_model');
        $this->load->model('url_model');
        $this->load->model('date_model');
        $this->load->model('entrytype_model');
        $this->load->model('regtype_model');

        // gebruik slug om ID te kry
        $edition_data = $this->edition_model->get_edition_id_from_slug($slug);
        $this->data_to_views['edition_data'] = $this->edition_model->get_edition_detail($edition_data['edition_id']);
        $this->data_to_views['race_list'] = $this->race_model->get_race_list($edition_data['edition_id']);
        $this->data_to_views['file_list'] = $this->file_model->get_file_list("edition", $edition_data['edition_id'], true);
        $this->data_to_views['url_list'] = $this->url_model->get_url_list("edition", $edition_data['edition_id'], true);
        $this->data_to_views['date_list'] = $this->date_model->get_date_list("edition", $edition_data['edition_id'], false, true);

        $this->data_to_views['edition_data']['race_summary'] = $this->get_set_race_suammry($this->data_to_views['race_list'], $this->data_to_views['edition_data']['edition_date'], $this->data_to_views['edition_data']['edition_info_prizegizing']);
        $this->data_to_views['edition_data']['entrytype_list'] = $this->entrytype_model->get_edition_entrytype_list($edition_data['edition_id']);
        $this->data_to_views['edition_data']['regtype_list'] = $this->regtype_model->get_edition_regtype_list($edition_data['edition_id']);

        $this->data_to_views['address'] = $this->data_to_views['edition_data']['edition_address_end'] . ", " . $this->data_to_views['edition_data']['town_name'];
        $this->data_to_views['address_nospaces'] = url_title($this->data_to_views['address'] . ", ZA");

        $this->data_to_views['page_title'] = substr($edition_data['edition_name'], 0, -5) . " - " . fdateTitle($this->data_to_views['edition_data']['edition_date']);
        $this->data_to_views['page_menu'] = $this->get_event_menu($slug, $this->data_to_views['edition_data']['event_id'], $edition_data['edition_id']);
        $this->data_to_views['status_notice'] = $this->formulate_status_notice();
            
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('templates/banner_event', $this->data_to_views);
        $this->load->view('templates/page_menu', $this->data_to_views);
        $this->load->view('event/' . $url_params[0], $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
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
            if (strtotime($race['race_date']) != strtotime($edition_date)) {
                continue;
            }

            if ((strtotime($edition_date) + time_to_sec($race['race_time_start'])) < $start_datetime) {
                $start_datetime = strtotime($edition_date) + time_to_sec($race['race_time_start']);
            }
            $return_arr['times']['start'] = date("Y-m-d H:i:s", $start_datetime);

            // END TIME
            if (time_to_sec($prize_giving_time) > 0) {
                $return_arr['times']['end'] = date("Y-m-d H:i:s", strtotime($edition_date) + time_to_sec($prize_giving_time));
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

        // check for route maps
//        if ((!isset($this->data_to_views['url_list'][8])) && (!isset($this->data_to_views['file_list'][7]))) {
        unset($menu_arr['route_maps']);
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

    private function formulate_status_notice() {
        $return = [];
        ;
//        echo $event_detail['edition_status'];
//        die();
        switch ($this->data_to_views['edition_data']['edition_status']) {
            case 2:
                $msg = "<b>This event is set to DRAFT mode.</b> All detail has not yet been confirmed";
                $short_msg = "DRAFT";
                $state = "danger";
                $icon = "minus-circle";
                break;
            case 3:
                $email = $this->data_to_views['edition_data']['user_email'];
                $msg = "<strong>This event has been CANCELLED.</strong> Please contact the event organisers for more detail on: <a href='mailto:$email' class='link' title='Email organisers'>$email</a>";
                $short_msg = "CANCELLED";
                $state = "danger";
                $icon = "times-circle";
                break;
            case 9:
                $email = $this->data_to_views['edition_data']['user_email'];
                $msg = "<strong>This event has been POSTPONED until further notice.</strong> Please contact the event organisers for more detail on: <a href='mailto:$email' class='link' title='Email organisers'>$email</a><br>"
                        . "Please consider <b><a href='#subscribe'>subscribing</a></b> to the event below to receive an email once a new date is set";
                $short_msg = "POSTPONED";
                $state = "warning";
                $icon = "minus-circle";
                break;
            default:
                switch ($this->data_to_views['edition_data']['edition_info_status']) {
                    case 13:
                        $msg = "<strong>PLEASE NOTE</strong><br>Dates and race times has <u>not yet been confirmed</u> by the race organisers";
                        $short_msg = "Dates not confirmed yet";
                        $state = "danger";
                        $icon = "minus-circle";
                        break;
                    case 14:
                        $msg = "<b>INFO UNCONFIRMED</b><br>Waiting for race information from the organisers";
                        $short_msg = "Dates not confirmed yet";
                        $state = "warning";
                        $icon = "info-circle";
                        break;
                    case 15:
                        $msg = "<b>INFO INCOMPLETE</b><br>All information loaded has been confirmed as correct, but listing is not complete";
                        $short_msg = "Outstanding information";
                        $state = "info";
                        $icon = "info-circle";
                        break;
                    case 16:
                        $msg = "<b>LISTING VERIFIED</b><br>All information below has been confirmed";
                        $short_msg = "Listing complete";
                        $state = "success";
                        $icon = "check-circle";
                        break;
                    case 10:
                        $msg = "<b>RESULTS PENDING</b><br>Waiting for results to be released by organisers. Note this can take up to a week";
                        $short_msg = "Waiting for results";
                        $state = "info";
                        $icon = "info-circle";
                        break;
                    case 11:
                        $slug = $this->data_to_views['edition_data']['edition_slug'];
                        $msg = "<b>RESULTS LOADED</b><br>Click to <a href='" . base_url("event/$slug/results") . "'>view results</a>";
                        $short_msg = "Results loaded";
                        $state = "success";
                        $icon = "check-circle";
                        break;
                    case 12:
                        $msg = "<b>NO RESULTS EXPECTED</b><br>No official results will be released for this event";
                        $short_msg = "No results expexted";
                        $state = "warning";
                        $icon = "minus-circle";
                        break;
                }
                break;
        }

        $return['msg'] = $msg;
        $return['short_msg'] = $short_msg;
        $return['state'] = $state;
        $return['icon'] = $icon;
        return $return;
    }

}
