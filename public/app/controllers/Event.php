<?php

// public mailer class to get list from mailques table and send it out
class Event extends Frontend_Controller {

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

        // gebruik slug om ID te kry
        $edition_sum = $this->edition_model->get_edition_id_from_slug($slug);
        if ($edition_sum) {
            // AS DIE NAAM WAT INKOM, NIE DIELFDE AS DIE OFFICIAL NAAM IS NIE, DAN DOEN HY 'N 301 REDIRECT.
            if ($edition_sum['source'] == "past") {
                $new_slug = $this->edition_model->get_edition_slug($edition_sum['edition_id']);
                $url = base_url("event/" . $new_slug);
                redirect($url, 'location', 301);
            } else {
                // if all is well
                $edition_id = $edition_sum['edition_id'];
                $edition_status = $edition_sum['edition_status'];
                $this->data_to_views['edition_data'] = $edition_data = $this->edition_model->get_edition_detail($edition_id);
            }
        } else {
            // old school. This is backward compatibility for OLD cached urls
            // decode die edition name uit die URL en kry ID
            $edition_name = get_edition_name_from_url($slug);
            $edition_data = $this->edition_model->get_edition_id_from_name($edition_name);

            if ($edition_data) {
                // AS DIE NAAM WAT INKOM, NIE DIELFDE AS DIE OFFICIAL NAAM IS NIE, DAN DOEN HY 'N 301 REDIRECT. (gebruik die nuwe slug)
                if ($edition_data['edition_name'] != $edition_name) {
//                $url = get_url_from_edition_name(encode_edition_name($edition_data['edition_name']));
                    $new_slug = $this->edition_model->get_edition_slug($edition_data['edition_id']);
                    $url = base_url("event/" . $new_slug);
                    redirect($url, 'location', 301);
                }
            }
        }

        // as die edition nie gevind is nie, of die status is Not Active
        if (!isset($edition_id) || ($edition_status == 2)) {
            // if name cannot be matched to an edition
            $this->session->set_flashdata([
                'alert' => " We had trouble finding the event. Please try selecting an event from the list below.",
                'status' => "danger",
            ]);
            redirect(base_url("race/upcoming"), 'refresh', 404);
            die();
        }


        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->load->model('race_model');
        $this->load->model('file_model');
        $this->load->model('url_model');
        $this->load->model('date_model');
        $this->load->model('entrytype_model');
        $this->load->model('regtype_model');
        $this->load->model('tag_model');
        // set a few vars to use
        $this->data_to_views['slug'] = $slug;
        $this->data_to_views['contact_url'] = base_url("contact/event/" . $slug);
        $this->data_to_views['subscribe_url'] = base_url("user/subscribe/edition/" . $slug);
        $this->data_to_views['scripts_to_load'] = ["https://www.google.com/recaptcha/api.js"];
        $this->data_to_views['is_event_page'] = 1;
        // check vir sub-page
        if ((empty($url_params)) || (!file_exists(APPPATH . "views/event/" . $url_params[0] . ".php"))) {
            $url_params[0] = "summary";
        }
        $this->data_to_views['page_title_small'] = "less_padding"; // make all small banners for now
        $this->data_to_views['url_params'] = $url_params;
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
        $this->data_to_views['edition_data']['sponsor_list'] = $this->edition_model->get_edition_sponsor_list($edition_id);
        if (array_keys_exists([4], $this->data_to_views['edition_data']['sponsor_list'])) {
            unset($this->data_to_views['edition_data']['sponsor_list']);
        }

        // calc values
        if (strtotime($edition_data['edition_date']) < time()) {
            $this->data_to_views['in_past'] = true;
        } else {
            $this->data_to_views['in_past'] = false;
        }
        $this->data_to_views['address'] = $edition_data['edition_address_end'] . ", " . $edition_data['town_name'];
        $this->data_to_views['address_nospaces'] = url_title($this->data_to_views['address'] . ", ZA");
        $this->data_to_views['page_menu'] = $this->get_event_menu($slug, $edition_data['event_id'], $edition_id, $this->data_to_views['in_past']);
        $this->data_to_views['status_notice'] = $this->formulate_status_notice($edition_data);
        $this->data_to_views['race_status_name'] = $this->edition_model->get_status_name($edition_data['edition_info_status']);
        // Online entries open?
        $this->data_to_views['online_entry_status'] = "unknown";
        if (isset($this->data_to_views['date_list'][3])) {
            if ((strtotime($this->data_to_views['date_list'][3][0]['date_start']) < time()) && (strtotime($this->data_to_views['date_list'][3][0]['date_end']) > time())) {
                $this->data_to_views['online_entry_status'] = "open";
            }
            if (strtotime($this->data_to_views['date_list'][3][0]['date_end']) < time()) {
                $this->data_to_views['online_entry_status'] = "closed";
            }
        }

        // if results loaded, get URLS to use
        if ($edition_data['edition_info_status'] == 11) {
            $this->data_to_views['results'] = $this->get_result_arr($slug);
        }

        // GPS
        $gps_parts = explode(",", $edition_data['edition_gps']);
        $this->data_to_views['gps']['lat'] = $gps_parts[0];
        $this->data_to_views['gps']['long'] = $gps_parts[1];
        $this->data_to_views['edition_date_minus_one'] = date("Y-m-d", strtotime($edition_data['edition_date']) - 86400);

        // SET PAGE TITLE AND META DESCRIPTIONS
        $view_to_load = $url_params[0];
        $this->data_to_views['page_title'] = $page_title = substr($edition_data['edition_name'], 0, -5) . " - " . fdateTitle($edition_data['edition_date']);
        if ($url_params[0] == "summary") {
            $this->data_to_views['structured_data'] = $this->load->view('/event/structured_data', $this->data_to_views, TRUE);
            $this->data_to_views['google_cal_url'] = $this->formulate_google_cal($edition_data, $this->data_to_views['race_list']);
            $this->data_to_views['meta_description'] = $this->formulate_meta_description($this->data_to_views['edition_data']);
        } else {
            $this->data_to_views['page_title'] = ucwords(str_replace("-", " ", $url_params[0]));
            switch ($url_params[0]) {
                case "entries":
                    $this->data_to_views['page_title'] = "How to enter";
                    $meta_title = "Information on how to enter the ";
                    break;
                case "contact":
                    $this->data_to_views['page_title'] = "Contact Organisers";
                    $meta_title = "Organiser contact information for the ";
                    break;
                case "accommodation":
                    $meta_title = "Accommodation options for the ";
                    break;
                case "subscribe":
                    $this->data_to_views['page_title'] = "Mailing List";
                    $meta_title = "Add yourself to the mailing list for the ";
                    break;
                case "results":
                    $meta_title = $this->data_to_views['page_title'] . " for the ";
                    foreach ($this->data_to_views['race_list'] as $race_id => $race) {
                        $results = $this->race_model->get_race_detail_with_results(["race_id" => $race_id]);
                        if ($results) {
                            $this->data_to_views['result_list'][$race_id] = $results;
                        }
                    }
                    // as dar iets na /results is
                    if ((isset($url_params[1])) && (isset($this->data_to_views['results']['race']))) {
                        if (is_numeric($url_params[1])) {
                            $dist = $url_params[1];
                            $racetype = $url_params[2];
                            // R/W exception
                            if (isset($url_params[3])) {
                                if (($url_params[3] == "W") && ($url_params[2] == "R")) {
                                    $racetype = "R/W";
                                }
                            }
                            $this->data_to_views['race_data'] = $this->data_to_views['results']['race'][$racetype][$dist];
                            $this->data_to_views['race_info'] = $this->data_to_views['race_list'][$this->data_to_views['race_data']['race_id']];
                            $this->data_to_views['race_id'] = $this->data_to_views['race_data']['race_id'];

                            $this->load->library('table');
                            $this->data_to_views['css_to_load'] = [base_url("assets/js/plugins/components/datatables/datatables.min.css")];
                            $this->data_to_views['scripts_to_load'] = [
                                base_url("assets/js/plugins/components/datatables/datatables.min.js"),
                                base_url("assets/js/data-tables_20200706.js"),
                            ];
                            // set view
                            if (!empty($this->data_to_views['race_info'])) {
                                $view_to_load = "result-race";
                            }

                            $meta_title = $this->data_to_views['race_data']['text'] . " for the ";
                            $page_title = $this->data_to_views['race_data']['distance'] . "km - " . $page_title;
                        }
                    }

                    break;
                default:
                    $meta_title = $this->data_to_views['page_title'] . " for the ";
                    break;
            }
            $this->data_to_views['page_title'] = $this->data_to_views['page_title'] . " - " . $page_title;
            $this->data_to_views['meta_description'] = $meta_title . fDateYear($edition_data['edition_date']) . " edition of the " . substr($edition_data['edition_name'], 0, -5);
            $this->data_to_views['meta_description'] = str_replace("the The", "The", $this->data_to_views['meta_description']);
        }



        $this->data_to_views['route_maps'] = $this->get_routemap_arr($slug);
        $this->data_to_views['tshirt'] = $this->get_tshirt_arr($slug);

        if ((isset($this->data_to_views['url_list'][2])) || (isset($this->data_to_views['file_list'][2]))) {
            $this->data_to_views['flyer'] = $this->get_flyer_arr($slug);
        }
        if ((isset($this->data_to_views['url_list'][3])) || (isset($this->data_to_views['file_list'][3]))) {
            $this->data_to_views['entry_form'] = $this->get_entry_form_arr($slug);
        }

//        wts($this->data_to_views['route_maps'],true);

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('templates/banner_event', $this->data_to_views);
        $this->load->view('templates/page_menu', $this->data_to_views);
        if ($this->data_to_views['edition_data']['edition_status'] == 17) {
            $this->load->view('widgets/virtual_race_notice');            
        }
        $this->load->view('widgets/race_status', $this->data_to_views['status_notice']);
        $this->load->view('event/' . $view_to_load, $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    function formulate_meta_description($edition_data) {
//        wts($edition_data['race_summary']);
//        wts($edition_data,1);
        $return = "Listing for the annual " .
                $edition_data['event_name'] . " in " .
                $edition_data['town_name'] . ", " .
                $edition_data['province_name'] . " on " .
                fdateHumanFull($edition_data['edition_date']) . " starting from " .
                ftimeSort($edition_data['race_summary']['times']['start']);
        return $return;
    }

    private function get_result_arr($slug) {
        $results = [];
        $n = 0;
        if (isset($this->data_to_views['file_list'][4])) {
            $results['edition'][$n]['url'] = base_url("file/edition/" . $slug . "/results/" . $this->data_to_views['file_list'][4][0]['file_name']);
            $results['edition'][$n]['text'] = "Download results summary";
            $results['edition'][$n]['icon'] = "file-excel";
            $n++;
        }
        if (isset($this->data_to_views['url_list'][4])) {
            $results['edition'][$n]['url'] = $this->data_to_views['url_list'][4][0]['url_name'];
            $results['edition'][$n]['text'] = "View results";
            $results['edition'][$n]['icon'] = "external-link-alt";
            $n++;
        }

// get race file and url lists
        foreach ($this->data_to_views['race_list'] as $race_id => $race) {
            $race_file_list = $this->file_model->get_file_list("race", $race_id, true);
            $race_url_list = $this->url_model->get_url_list("race", $race_id, true);
            $round_dist = floor($race['race_distance']);
            if (isset($race_file_list[4])) {
                $results['race'][$race['racetype_abbr']][$round_dist]['url'] = base_url("file/race/" . $slug . "/results/" . url_title($race['race_name']) . "/" . $race_file_list[4][0]['file_name']);
                $results['race'][$race['racetype_abbr']][$round_dist]['text'] = $race['race_name'] . " " . $race_file_list[4][0]['filetype_buttontext'];
                $results['race'][$race['racetype_abbr']][$round_dist]['icon'] = "file-excel";
                $results['race'][$race['racetype_abbr']][$round_dist]['race_id'] = $race_id;
                $results['race'][$race['racetype_abbr']][$round_dist]['distance'] = $round_dist;
                $n++;
            }
            if (isset($race_url_list[4])) {
                $results['race'][$race['racetype_abbr']][$round_dist]['url'] = $race_url_list[4][0]['url_name'];
                $results['race'][$race['racetype_abbr']][$round_dist]['text'] = $race['race_name'] . " Results";
                $results['race'][$race['racetype_abbr']][$round_dist]['icon'] = "external-link-alt";
                $results['race'][$race['racetype_abbr']][$round_dist]['race_id'] = $race_id;
                $results['race'][$race['racetype_abbr']][$round_dist]['distance'] = $round_dist;
                $n++;
            }
        }

        return $results;
    }

    private function get_tshirt_arr($slug) {
        $tshirt_list = [];
        if (isset($this->data_to_views['file_list'][8])) {
            $tshirt_list['edition']['url'] = base_url("file/edition/" . $slug . "/t-shirt/" . $this->data_to_views['file_list'][8][0]['file_name']);
            $tshirt_list['edition']['text'] = "View T-Shirt Design";
            $tshirt_list['edition']['icon'] = "file-image";
        }

        return $tshirt_list;
    }

    private function get_routemap_arr($slug) {
        $route_maps = [];
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

    private function get_flyer_arr($slug) {
        $flyer_list = [];
        if (isset($this->data_to_views['file_list'][2])) {
            $flyer_list['edition']['url'] = base_url("file/edition/" . $slug . "/flyer/" . $this->data_to_views['file_list'][2][0]['file_name']);
            $flyer_list['edition']['text'] = "Download Race Flyer";
            $flyer_list['edition']['icon'] = "file-pdf";
        } elseif (isset($this->data_to_views['url_list'][2])) {
            $flyer_list['edition']['url'] = $this->data_to_views['url_list'][2][0]['url_name'];
            $flyer_list['edition']['text'] = "View Race Flyer";
            $flyer_list['edition']['icon'] = "external-link-alt";
        }

        return $flyer_list;
    }

    private function get_entry_form_arr($slug) {
        $flyer_list = [];
        if (isset($this->data_to_views['file_list'][3])) {
            $flyer_list['edition']['url'] = base_url("file/edition/" . $slug . "/entry form/" . $this->data_to_views['file_list'][3][0]['file_name']);
            $flyer_list['edition']['text'] = "Download Entry Form";
            $flyer_list['edition']['icon'] = "file-pdf";
        } elseif (isset($this->data_to_views['url_list'][3])) {
            $flyer_list['edition']['url'] = $this->data_to_views['url_list'][3][0]['url_name'];
            $flyer_list['edition']['text'] = "View Entry Form";
            $flyer_list['edition']['icon'] = "external-link-alt";
        }

        return $flyer_list;
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

    function ics($edition_slug) {

        $this->load->model('edition_model');
        $this->load->model('race_model');

        $edition_info = $this->edition_model->get_edition_id_from_slug($edition_slug);
        $edition_id = $edition_info['edition_id'];

//        $edition_data = $this->edition_model->get_edition_detail($edition_id);
//        $race_list = $this->race_model->get_race_list(["where" => ["races.edition_id" => $edition_id]]);
// new way to pull data
        $query_params = [
            "where" => ["edition_id" => $edition_id],
        ];
        $edition_list = $this->race_model->add_race_info($this->edition_model->get_edition_list($query_params));
        $edition_data = $edition_list[$edition_id];

//        wts($edition_data,1);

        $edition_url = $edition_data['edition_url'];
        $address = $edition_data['edition_address'] . ", " . $edition_data['town_name'];

// dates
        $date = $edition_data['edition_date'];
        $sdate = $edition_data['race_time_start'];
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

    public function add() {

        $this->data_to_views['scripts_to_load'] = ["https://www.google.com/recaptcha/api.js"];

// validation rules
        $this->form_validation->set_rules('event_name', 'Event name', 'trim|required');
        $this->form_validation->set_rules('event_date', 'Event date', 'trim|required');
        $this->form_validation->set_rules('event_time', 'Event time', 'trim|required');
        $this->form_validation->set_rules('event_address', 'Event address', 'trim|required');
        $this->form_validation->set_rules('town_name', 'Town Name', 'trim|required');
        $this->form_validation->set_rules('user_name', 'Contact name', 'trim|required');
        $this->form_validation->set_rules('user_surname', 'Contact surname', 'trim|required');
        $this->form_validation->set_rules('user_email', 'Contact email address', 'trim|required|valid_email');
        $this->form_validation->set_rules('event_url', 'Entry URL needs to be valid', 'trim|valid_url');
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');

        if ($this->form_validation->run() === FALSE) {
            $this->data_to_views['scripts_to_load'] = ["https://www.google.com/recaptcha/api.js"];
            $this->data_to_views['banner_img'] = "run_04";
            $this->data_to_views['banner_pos'] = "40%";
            $this->data_to_views['page_title'] = "Add race listing";
            $this->data_to_views['meta_description'] = "Are you a race organiser? Please send us the details of your event and we will get it listed on the site";
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view($this->banner_url, $this->data_to_views);
            $this->load->view($this->notice_url, $this->data_to_views);
            $this->load->view('event/add-listing', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
// set user_data from post
            foreach ($this->input->post() as $field => $value) {
                $email_data[$field] = $value;
            }
            $mail_id = $this->send_event_add_email($email_data);

            $this->session->set_flashdata([
                'alert' => "Listing information send",
                'status' => "success",
                'icon' => "check-circle",
                'confirm_msg' => 'Thank you sending through your event information. I will be in touch as soon as I can.',
                'confirm_btn_txt' => 'Return',
                'confirm_btn_url' => base_url(),
            ]);

            redirect(base_url("contact/confirm"));
        }
    }

// SEND EVENT EMAIL
    private function send_event_add_email($email_data) {
        $data = [
            "to" => "info@roadrunning.co.za",
            "subject" => "New listing from site: " . $email_data['event_name'],
            "body" => "<p>Hello,</p>"
            . "<p>Please see below information entered on the "
            . "<a href = 'https://www.roadrunning.co.za/' style = 'color:#222222 !important;text-decoration:underline !important;'>RoadRunning.co.za</a> website "
            . "by a user wanting to add an event called <b>" . $email_data['event_name'] . "</b> to the site with the following detail:"
            . "<p><b>Event Name:</b> " . $email_data['event_name'] . "<br>"
            . "<b>Event Date:</b> " . $email_data['event_date'] . "<br>"
            . "<b>Start Time:</b> " . $email_data['event_time'] . "<br>"
            . "<b>Address:</b> " . $email_data['event_address'] . "<br>"
            . "<b>Town:</b> " . $email_data['town_name'] . "<br>"
            . "<b>Contact:</b> " . $email_data['user_name'] . " " . $email_data['user_surname'] . "<br>"
            . "<b>Email:</b> " . $email_data['user_email'] . "<br>"
            . "<b>Entry URL:</b> " . $email_data['event_url'] . "</p>"
            . "<p style='padding-left: 15px; border-left: 4px solid #ccc;'><b>Comment:</b><br> " . nl2br(trim($email_data['user_comment'])) . "</p>",
            "from" => $email_data['user_email'],
            "from_name" => $email_data['user_name'] . " " . $email_data['user_surname'],
        ];
// send mail to organiser
//        wts($data, 1);
// send mail to user
        $this->set_email($data);

        return true;
    }

}
