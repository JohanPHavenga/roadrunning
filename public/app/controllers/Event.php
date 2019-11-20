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
        }
        if (empty($url_params)) {
            $url_params[] = "summary";
        }

        $this->load->model('race_model');
        $this->load->model('file_model');
        $this->load->model('url_model');
        $this->load->model('date_model');

        // gebruik slug om ID te kry
        $edition_data = $this->edition_model->get_edition_id_from_slug($slug);
        $this->data_to_views['edition_data'] = $this->edition_model->get_edition_detail($edition_data['edition_id']);
        $this->data_to_views['race_list'] = $this->race_model->get_race_list($edition_data['edition_id']);
        $this->data_to_views['file_list'] = $this->file_model->get_file_list("edition", $edition_data['edition_id'], true);
        $this->data_to_views['url_list'] = $this->url_model->get_url_list("edition", $edition_data['edition_id']);
        $this->data_to_views['date_list'] = $this->date_model->get_date_list("edition", $edition_data['edition_id'], false, true);

        $this->data_to_views['edition_data']['race_summary'] = $this->get_set_race_suammry($this->data_to_views['race_list'],$this->data_to_views['edition_data']['edition_date'],$this->data_to_views['edition_data']['edition_info_prizegizing']);
        
//        $this->data_to_views['event_times'] = $this->get_event_start_end_times($this->data_to_views['edition_data']['edition_date'], $this->data_to_views['edition_data']['edition_info_prizegizing'], $this->data_to_views['race_list']);
//        $this->data_to_views['edition_data']['fee_from_to'] = $this->get_fee_from_to($this->data_to_views['race_list']);       

        $this->data_to_views['page_title'] = substr($edition_data['edition_name'], 0, -5) . " - " . fdateTitle($this->data_to_views['edition_data']['edition_date']);
        $this->data_to_views['page_menu'] = $this->get_event_menu($slug);

        $this->load->view($this->header_url, $this->data_to_views);
//        $this->load->view("event/header", $this->data_to_views);
        switch ($url_params[0]) {
            case "results":
                $this->load->view('event/results', $this->data_to_views);
                break;
            case "route-maps":
                $this->load->view('event/route_maps', $this->data_to_views);
                break;
            case "races":
                $this->load->view('event/races', $this->data_to_views);
                break;
            default:
                $this->load->view('event/summary', $this->data_to_views);
                break;
        }
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    private function get_set_race_suammry($race_list, $edition_date, $prize_giving_time) {
        if (!$race_list) { return false; } 
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

        foreach ($race_list as $race) {
            // START TIME
            $start_datetime = strtotime($edition_date) + 86400;
            if (strtotime($race['race_date']) > 0) {
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
            $return_arr['list'][] = 
                    [
                        'distance' => $race['race_distance'],
                        'type' => $race['racetype_name'],
                        'abbr' => $race['racetype_abbr'],
                        'color' => $race['race_color'],
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

    private function get_event_menu($slug) {
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
            "races" => [
                "display" => "Distances",
                "loc" => base_url("event/" . $slug . "/races"),
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
        return $menu_arr;
    }

}
