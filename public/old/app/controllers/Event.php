<?php

class Event extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
        $this->data_to_header['section'] = "events";
        $this->load->model('event_model');
        $this->load->model('edition_model');
        $this->load->helper('cookie');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    // check if method exists, if not calls "view" method
    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->detail($method, $params = array());
        }
    }

    public function detail($slug) {
        // get race and edition models
        $this->load->model('race_model');
        $this->load->model('file_model');
        $this->load->model('url_model');
        $this->load->model('date_model');
        $this->load->model('entrytype_model');
        $this->load->model('regtype_model');

        // as daar nie 'n edition_name deurgestuur word nie
        if ($slug == "index") {
            die("index");
            redirect("/event/calendar");
        }

        // gebruik slug om ID te kry
        $edition_data = $this->edition_model->get_edition_id_from_slug($slug);

        if ($edition_data) {
            // AS DIE NAAM WAT INKOM, NIE DIELFDE AS DIE OFFICIAL NAAM IS NIE, DAN DOEN HY 'N 301 REDIRECT.
            if ($edition_data['source'] == "past") {
                $new_slug = $this->edition_model->get_edition_slug($edition_data['edition_id']);
                $url = base_url("event/" . $new_slug);
                redirect($url, 'location', 301);
            }
        } else {
            // old school
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

        // SET THE BASICS
        $edition_id = $edition_data['edition_id'];
        $edition_status = $edition_data['edition_status'];
        $edition_name = $edition_data['edition_name'];

        // as die edition nie gevind is nie, of die status is Not Active
        if (!$edition_id || ($edition_status == 2)) {
            // if name cannot be matched to an edition
            $this->session->set_flashdata([
                'alert' => " We had trouble finding the event. Please try selecting the correct event from the list below.",
                'status' => "danger",
            ]);
            $this->session->keep_flashdata('alert');
            $this->session->keep_flashdata('status');
            redirect("calendar");
            die();
        }

        $this->session->set_flashdata(["last_visited_event" => $edition_name]);    // edition in session vir contact form // this is old way, should read from full session data below
        // get basic edition data and add it to session
        $basic_edition_detail = $this->edition_model->get_edition_url_from_id($edition_id);
        $this->session->set_userdata($basic_edition_detail);

        // set data to view
        $this->data_to_header['css_to_load'] = array(
            "plugins/cubeportfolio/css/cubeportfolio.min.css",
            "plugins/owl-carousel/assets/owl.carousel.css",
            "plugins/fancybox/jquery.fancybox.css",
            "plugins/slider-for-bootstrap/css/slider.css",
            "plugins/leaflet/leaflet.css",
        );

        $this->data_to_footer['js_to_load'] = array(
            "plugins/cubeportfolio/js/jquery.cubeportfolio.min.js",
            "plugins/owl-carousel/owl.carousel.min.js",
            "plugins/fancybox/jquery.fancybox.pack.js",
            "plugins/smooth-scroll/jquery.smooth-scroll.js",
            "plugins/slider-for-bootstrap/js/bootstrap-slider.js",
            "plugins/leaflet/leaflet.js",
//            GOOGLE_MAP_URL,
        );

        $this->data_to_footer['scripts_to_load'] = array(
//            "plugins/gmaps/gmaps.js",
            "https://www.google.com/recaptcha/api.js"
        );

        // get event details
        $this->data_to_view['event_detail'] = $this->edition_model->get_edition_detail_full($edition_id);

        // set edition names + title
        $e_names = $this->get_edition_name_from_status($edition_name, $edition_status, $this->data_to_view['event_detail']['edition_date']);
        if ($edition_status == 2) {
            $this->data_to_header['meta_robots'] = "noindex, nofollow";
        }
        $this->data_to_header['title'] = $e_names['edition_name'];

        // set rest of edition detials
        $this->data_to_view['event_detail']['edition_name'] = $e_names['edition_name'];
        $this->data_to_view['event_detail']['edition_name_clean'] = $e_names['edition_name_clean'];
        $this->data_to_view['event_detail']['edition_name_no_date'] = $e_names['edition_name_no_date'];
        $this->data_to_view['event_detail']['race_list'] = $this->race_model->get_race_list($edition_id);
        foreach ($this->data_to_view['event_detail']['race_list'] as $race_id => $race) {
            if ($race['race_status'] == 2) {
                unset($this->data_to_view['event_detail']['race_list'][$race_id]);
                continue;
            }
            $this->data_to_view['event_detail']['race_list'][$race_id]['file_list'] = $this->file_model->get_file_list("race", $race_id, true);
            $this->data_to_view['event_detail']['race_list'][$race_id]['url_list'] = $this->url_model->get_url_list("race", $race_id, true);
            $this->data_to_view['event_detail']['race_list'][$race_id]['race_name'] = $this->get_race_name_from_status($race['race_name'], $race['race_distance'], $race['racetype_name'], $race['race_status']);
        }
        $this->data_to_view['event_detail']['file_list'] = $this->file_model->get_file_list("edition", $edition_id, true);
        $this->data_to_view['event_detail']['summary'] = $this->event_model->get_event_list_summary("id", ["edition_id" => $this->data_to_view['event_detail']['edition_id']]);
        $this->data_to_view['event_detail']['file_list'] = $this->file_model->get_file_list("edition", $edition_id, true);
        $this->data_to_view['event_detail']['url_list'] = $this->url_model->get_url_list("edition", $edition_id, true);
        $this->data_to_view['event_detail']['date_list'] = $this->date_model->get_date_list("edition", $edition_id, false, true);
        $this->data_to_view['event_detail']['entrytype_list'] = $this->entrytype_model->get_edition_entrytype_list($edition_id);
        $this->data_to_view['event_detail']['regtype_list'] = $this->regtype_model->get_edition_regtype_list($edition_id);
        $this->data_to_view['event_detail']['sponsor_url_list'] = $this->url_model->get_url_list("sponsor", $this->data_to_view['event_detail']['sponsor_id'], false);
        $this->data_to_view['event_detail']['club_url_list'] = $this->url_model->get_url_list("club", $this->data_to_view['event_detail']['club_id'], false);
        // get event history / furture
        $this->data_to_view['event_history'] = $this->get_event_history($this->data_to_view['event_detail']['event_id'], $edition_id);
        // get next an previous races
//        if ($this->data_to_view['event_detail']['race_list']) {
//            $this->data_to_view['next_race_list'] = $this->race_model->get_next_prev_race_list($this->data_to_view['event_detail']['race_list'], 'next');
//            $this->data_to_view['prev_race_list'] = $this->race_model->get_next_prev_race_list($this->data_to_view['event_detail']['race_list'], 'prev');
//        }
        // get url for Google Calendar
        $this->data_to_view['event_detail']['google_cal_url'] = $this->google_cal(
                [
                    "edition_name" => $this->data_to_view['event_detail']['edition_name'],
                    "edition_date" => $this->data_to_view['event_detail']['edition_date'],
                    "race_list" => $this->data_to_view['event_detail']['race_list'],
                    "url" => "http://www.roadrunning.co.za" . urlencode($this->data_to_view['event_detail']['summary']['edition_url']),
                    "address" => $this->data_to_view['event_detail']['edition_address_end'] . ", " . $this->data_to_view['event_detail']['town_name'],
                ]
        );

        // get other stuff
//        $this->data_to_footer['scripts_to_display'][]=$this->formulate_gmap_script($this->data_to_view['event_detail']);
        $this->data_to_footer['scripts_to_display'][] = $this->formulate_leaflet_script($this->data_to_view['event_detail']);
        $this->data_to_view['notice'] = $this->formulate_detail_notice($this->data_to_view['event_detail']);
        $this->data_to_header['meta_description'] = $this->formulate_meta_description($this->data_to_view['event_detail']['summary']);
        $this->data_to_header['keywords'] = $this->formulate_keywords($this->data_to_view['event_detail']['summary']);
//        $this->data_to_view['structured_data']=$this->formulate_structured_data($this->data_to_view['event_detail']);

        $this->data_to_header['structured_data'] = $this->load->view('/event/structured_data', $this->data_to_view, TRUE);

        // set buttons
        $this->data_to_view['event_detail']['calc_edition_urls'] = $btn_data['calc_edition_urls'] = $this->calc_urls_to_use($this->data_to_view['event_detail']['file_list'], $this->data_to_view['event_detail']['url_list'], $this->data_to_view['event_detail']['entrytype_list']);

        foreach ($this->data_to_view['event_detail']['race_list'] as $race_id => $race) {
            $race_urls = $this->calc_urls_to_use($race['file_list'], $race['url_list'], [], false);
            if ($race_urls) {
                $this->data_to_view['event_detail']['calc_race_urls'][$race_id] = $race_urls;
            }
        }

        // get cookie
        $this->data_to_view['rr_cookie']['sub_email'] = get_cookie("sub_email");


        // set title bar
        $day = date("d", strtotime($this->data_to_view['event_detail']['edition_date']));
        $month = date("m", strtotime($this->data_to_view['event_detail']['edition_date']));
        $month_name = date("F", strtotime($this->data_to_view['event_detail']['edition_date']));
        $year = date("Y", strtotime($this->data_to_view['event_detail']['edition_date']));
        // set crumb array
        $crumb_arr = [
            "crumbs" => [
                $this->data_to_view['event_detail']['event_name'] => base_url("event/" . $slug),
                $day => base_url("calendar/$year/$month/$day"),
                $month_name => base_url("calendar/$year/$month"),
                $year => base_url("calendar/$year"),
                "Events Calendar" => "/calendar",
                "Home" => "/"
            ],
        ];
        $this->data_to_view["title_bar"] = $this->render_topbar_html($crumb_arr);

        // set box color - this is for the zebra lines
        $box_color_arr[0] = '';
        $box_color_arr[1] = 'c-bg-grey-1';
        $bc = 0;
        $this->data_to_view['box_color'] = $box_color_arr[$bc];

//        wts($this->data_to_view['event_detail']);
//        die();
        // -------------------------------------------------------------------------------------------------
        // LOAD VIEWS         
        // -------------------------------------------------------------------------------------------------
        // HEADER
        $this->load->view($this->header_url, $this->data_to_header);

        // $this->load->view("/event/detail", $this->data_to_view);
        $this->load->view("/event/detail_head", $this->data_to_view);
        $this->load->view("/event/detail_event_heading", $this->data_to_view);

        // Google Add
        $this->load->view("/event/google_ad", $this->data_to_view);
        $bc = !$bc;
        $this->data_to_view['box_color'] = $box_color_arr[$bc];

        // Entry Detail
        if ((strlen($this->data_to_view['event_detail']['edition_entry_detail']) > 15) || // top one TBR in time
                (!in_array(5, $this->data_to_view['event_detail']['entrytype_list']))) {
            $this->load->view("/event/detail_event_info_entry", $this->data_to_view);
            $bc = !$bc;
            $this->data_to_view['box_color'] = $box_color_arr[$bc];
        }

        // Race detail
        $this->load->view("/event/detail_event_info_races", $this->data_to_view);
        // check race_list size. If uneven number, change box_color
        $num_races = sizeof($this->data_to_view['event_detail']['race_list']);
        if ($num_races % 2) {
            $bc = !$bc;
            $this->data_to_view['box_color'] = $box_color_arr[$bc];
        }

        // subscribe to event
        $this->load->view("/event/detail_subscribe", $this->data_to_view);

        // Event description
        $this->load->view("/event/detail_event_info_description", $this->data_to_view);
        $bc = !$bc;
        $this->data_to_view['box_color'] = $box_color_arr[$bc];

        // Accommodation
        $this->load->view("/event/detail_accom", $this->data_to_view);
        $bc = !$bc;
        $this->data_to_view['box_color'] = $box_color_arr[$bc];

        // Google Add
        $this->load->view("/event/google_ad_bottom", $this->data_to_view);
        $bc = !$bc;
        $this->data_to_view['box_color'] = $box_color_arr[$bc];

        // Detail footer
        $this->load->view("/event/detail_footer", $this->data_to_view);

        //FOOTER
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function get_event_history($event_id, $edition_id) {
        $return = [];
        // get list of editions linked to this event
        $edition_list = $this->event_model->get_edition_list($event_id);

        // remove the one you are looking at
        $current_year = date("Y", strtotime($edition_list[$edition_id]['edition_date']));
        unset($edition_list[$edition_id]);

        if ($edition_list) {
            foreach ($edition_list as $edition) {
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
//        wts($return);
//        wts($current_year);
//        wts($edition_list);
//        echo $event_id;
//        echo $edition_id;
//        die();
    }

    public function subscription() {
        $this->data_to_header['title'] = "Event Subsciption";

        $edition_id = $this->session->edition_id;
//        die();
        // set validation rules
        $this->form_validation->set_rules('user_name', 'Name', 'required', 'Please enter your name');
        $this->form_validation->set_rules('user_surname', 'Surame', 'required', 'Please enter your last name');
        $this->form_validation->set_rules('user_email', 'Email', 'required|valid_email');
//        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');

        $this->form_validation->set_message('required', 'Please complete the <b>{field}</b> field');
//        wts($this->input->post());
        // set title bar
        $this->data_to_header["title_bar"] = $this->render_topbar_html(
                ["crumbs" => ["Event Subsciption" => "/event/subscription", "Home" => "/"],
        ]);

        $this->data_to_footer['scripts_to_load'] = array(
            "https://www.google.com/recaptcha/api.js"
        );

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            if (!$this->input->post('button')) {
                $this->data_to_view['form_data']['user_email'] = get_cookie("sub_email");
            } else {
                $this->data_to_view['form_data'] = $this->input->post(NULL);
            }

            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view('event/subscription', $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $user_data = [
                "user_name" => $this->input->post(user_name),
                "user_surname" => $this->input->post(user_surname),
                "user_email" => $this->input->post(user_email),
            ];
            $success = $this->subscribe_user($user_data, "edition", $this->session->edition_id);
            redirect($this->session->edition_url);
        }
    }

    public function subscribe() {
        $this->load->helper('email');
        $this->load->model('user_model');

        // check if edition ID was posted and get return URL
        if (is_numeric($this->input->post("edition_id"))) {
            $edition_name = $this->edition_model->get_edition_name_from_id($this->input->post("edition_id"));
            $return_url = base_url() . "event/" . encode_edition_name($edition_name);
        } else {
            die("No return ID found");
        }

        // check vir valid email
        if (valid_email($this->input->post("sub_email"))) {
            set_cookie("sub_email", $this->input->post("sub_email"), 604800);
            $user_id = $this->user_model->get_user_id($this->input->post("sub_email"));

            if ($user_id) {
                $user_info = $this->user_model->get_user_name($user_id);
                $success = $this->subscribe_user($user_info, "edition", $this->input->post("edition_id"));
            } else {
                $alert = "<b>Almost there!</b>: Seems you are new here. Please enter your name and surname below to be added as a user on the site.";
                $status = "info";
                $this->session->set_flashdata(['alert' => $alert, 'status' => $status]);
                redirect("/event/subscription");
            }
        } else {
            $alert = "<b>Error</b>: You entered an invalid email address when subscribing to the event. Please try again.";
            $status = "danger";
            $this->session->set_flashdata(['alert' => $alert, 'status' => $status]);
        }
        redirect($return_url);
    }

    function calc_urls_to_use($file_list, $url_list, $entrytype_list = [], $debug = false) {
        $calc_url_list = [];
        $this->load->model('filetype_model');
        $this->load->model('urltype_model');
        $filetype_list = $this->filetype_model->get_filetype_list();
        $urltype_list = $this->urltype_model->get_urltype_list();

        if ($debug) {
            wts($file_list);
            wts($url_list);
        }
        // check eers vir flyer
        if (isset($file_list[2])) {
            $file_id = my_encrypt($file_list[2][0]['file_id']);
            $calc_url_list[0]['url'] = $calc_url_list[2]['url'] = base_url("file/handler/" . $file_id);
            $calc_url_list[0]['buttontext'] = $calc_url_list[2]['buttontext'] = $filetype_list[$file_list[2][0]['filetype_id']]['filetype_buttontext'];
            $calc_url_list[0]['helptext'] = $calc_url_list[2]['helptext'] = $filetype_list[$file_list[2][0]['filetype_id']]['filetype_helptext'];
            $calc_url_list[0]['type'] = $calc_url_list[2]['type'] = "file";
            $calc_url_list[0]['type_id'] = $calc_url_list[2]['type_id'] = 2;
        } elseif (isset($url_list[2])) {
            $calc_url_list[0]['url'] = $calc_url_list[2]['url'] = $url_list[2][0]['url_name'];
            $calc_url_list[0]['buttontext'] = $calc_url_list[2]['buttontext'] = $urltype_list[$url_list[2][0]['urltype_id']]['urltype_buttontext'];
            $calc_url_list[0]['helptext'] = $urltype_list[$url_list[2][0]['urltype_id']]['urltype_helptext'];
            $calc_url_list[0]['type'] = $calc_url_list[2]['type'] = "url";
            $calc_url_list[0]['type_id'] = $calc_url_list[2]['type_id'] = 2;
        }

        if (isset($url_list[1])) { // dan website
            $calc_url_list[0]['url'] = $calc_url_list[1]['url'] = $url_list[1][0]['url_name'];
            $calc_url_list[0]['buttontext'] = $calc_url_list[1]['buttontext'] = $urltype_list[$url_list[1][0]['urltype_id']]['urltype_buttontext'];
            $calc_url_list[0]['helptext'] = $calc_url_list[1]['helptext'] = $urltype_list[$url_list[1][0]['urltype_id']]['urltype_helptext'];
            $calc_url_list[0]['type'] = $calc_url_list[1]['type'] = "url";
            $calc_url_list[0]['type_id'] = $calc_url_list[1]['type_id'] = 1;
        }
//        if ((isset($url_list[5])) && (in_array(4,$entrytype_list))) { // sit hierdie terug einde van die jaar 
        if (isset($url_list[5])) { // dan online entry  TBR
            $calc_url_list[0]['url'] = $calc_url_list[5]['url'] = $url_list[5][0]['url_name'];
            $calc_url_list[0]['buttontext'] = $calc_url_list[5]['buttontext'] = $urltype_list[$url_list[5][0]['urltype_id']]['urltype_buttontext'];
            $calc_url_list[0]['helptext'] = $calc_url_list[5]['helptext'] = $urltype_list[$url_list[5][0]['urltype_id']]['urltype_helptext'];
            $calc_url_list[0]['type'] = $calc_url_list[5]['type'] = "url";
            $calc_url_list[0]['type_id'] = $calc_url_list[5]['type_id'] = 5;
        }

        $url_check_list = [2, 3, 4, 6, 7, 8];
        foreach ($url_check_list as $id) {
            if (isset($file_list[$id])) {
                $max_arr_key=max(array_keys($file_list[$id]));
                $file_id = my_encrypt($file_list[$id][$max_arr_key]['file_id']);
                $calc_url_list[$id]['url'] = base_url("file/handler/" . $file_id);
                $calc_url_list[$id]['buttontext'] = $filetype_list[$file_list[$id][$max_arr_key]['filetype_id']]['filetype_buttontext'];
                $calc_url_list[$id]['helptext'] = $filetype_list[$file_list[$id][$max_arr_key]['filetype_id']]['filetype_helptext'];
                $calc_url_list[$id]['type'] = "file";
                $calc_url_list[$id]['type_id'] = $id;
            } elseif (isset($url_list[$id])) {
                $max_arr_key=max(array_keys($url_list[$id]));
                $calc_url_list[$id]['url'] = $url_list[$id][$max_arr_key]['url_name'];
                $calc_url_list[$id]['buttontext'] = $urltype_list[$url_list[$id][$max_arr_key]['urltype_id']]['urltype_buttontext'];
                $calc_url_list[$id]['helptext'] = $urltype_list[$url_list[$id][$max_arr_key]['urltype_id']]['urltype_helptext'];
                $calc_url_list[$id]['type'] = "url";
                $calc_url_list[$id]['type_id'] = $id;
            }
        }
        if ($debug) {
            die();
        }
        ksort($calc_url_list);
        return $calc_url_list;
    }

    function formulate_meta_description($event_detail_summary) {
        $return = "The annual " .
                $event_detail_summary['event_name'] . " in " .
                $event_detail_summary['town_name'] . " on " .
                $event_detail_summary['edition_date'] . ". " .
                $event_detail_summary['race_time_start'] . " race including the follwing distances: " .
                $event_detail_summary['race_distance'];
        return $return;
    }

    function formulate_keywords($event_detail_summary) {
        $return = "";
        $name_secs = explode(" ", $event_detail_summary['event_name']);
        // event name
        foreach ($name_secs as $sec) {
            $return .= "$sec,";
        }
        // distance
        foreach ($event_detail_summary['distance_arr'] as $dis) {
            $return .= "$dis" . "km,";
        }
        foreach ($event_detail_summary['distance_arr'] as $dis) {
            $return .= "$dis" . "k,";
        }
        $return .= "Race,Event,Running,Run";
        return $return;
    }

    function formulate_detail_notice($event_detail) {
        // check if events is in the past
        $return = '';
//        echo $event_detail['edition_status'];
//        die();
        switch ($event_detail['edition_status']) {
            case 2:
                $msg = "<strong>This event is set to DRAFT mode.</strong> All detail has not yet been confirmed";
                $return = "<div class='alert alert-danger' role='alert' style='margin-bottom:0'><div class='container'>$msg</div></div>";
                break;
            case 3:
                $email = $event_detail['user_email'];
                $msg = "<strong>This event has been CANCELLED.</strong> Please contact the event organisers for more detail on: <a href='mailto:$email' class='link' title='Email organisers'>$email</a>";
                $return = "<div class='alert alert-danger' role='alert' style='margin-bottom:0'><div class='container'>$msg</div></div>";
                break;
            case 9:
                $email = $event_detail['user_email'];
                $msg = "<strong>This event has been POSTPONED until further notice.</strong> Please contact the event organisers for more detail on: <a href='mailto:$email' class='link' title='Email organisers'>$email</a><br>"
                        . "Please consider <b><a href='#subscribe'>subscribing</a></b> to the event below to receive an email once a new date is set";
                $return = "<div class='alert alert-warning' role='alert' style='margin-bottom:0'><div class='container'>$msg</div></div>";
                break;
            default:
                switch ($event_detail['edition_info_status']) {
                    case 13:
                        $msg = "<strong>PLEASE NOTE</strong> - The information below, including the date and race times has <u>not yet been confirmed</u> by the race organisers<br>"
                                . "Please consider <b><a href='#subscribe'>subscribing</a></b> to the event below to receive an email once information is loaded and confirmed";
                        $return = "<div class='alert alert-danger' role='alert' style='margin-bottom:0'><div class='container'>$msg</div></div>";
                        break;
                    case 14:
                        $msg = "<b>INFORMATION UNCONFIRMED</b> - Awaiting more information from organisers<br>"
                                . "Please consider <b><a href='#subscribe'>subscribing</a></b> to the event below to receive an email once information is loaded and confirmed";
                        $return = "<div class='alert alert-warning' role='alert' style='margin-bottom:0'><div class='container'>$msg</div></div>";
                        break;
                    case 15:
                        $msg = "<b>INFORMATION CONFIRMED</b> - All information loaded has been confirmed as correct, but listing remains incomplete<br>"
                                . "Please consider <b><a href='#subscribe'>subscribing</a></b> to the event below to receive an email once all information is loaded and verified";
                        $return = "<div class='alert alert-info' role='alert' style='margin-bottom:0'><div class='container'>$msg</div></div>";
                        break;
                    case 16:
                        $msg = "<b>LISTING VERIFIED</b> - All information below has been confirmed";
                        $return = "<div class='alert alert-success' role='alert' style='margin-bottom:0'><div class='container'>$msg</div></div>";
                        break;
                    case 10:
                        $msg = "<b>RESULTS PENDING</b> - Waiting for results to be released by organisers. Note this can take up to a week<br>"
                                . "Please consider <b><a href='#subscribe'>subscribing</a></b> to the event below to receive an email once results are loaded on the site";
                        $return = "<div class='alert alert-info' role='alert' style='margin-bottom:0'><div class='container'>$msg</div></div>";
                        break;
                    case 11:
                        $msg = "<b>RESULTS LOADED</b> - Please see links below to results";
                        $return = "<div class='alert alert-success' role='alert' style='margin-bottom:0'><div class='container'>$msg</div></div>";
                        break;
                    case 12:
                        $msg = "<b>NO RESULTS EXPECTED</b> - No official results will be released for this event";
                        $return = "<div class='alert alert-warning' role='alert' style='margin-bottom:0'><div class='container'>$msg</div></div>";
                        break;
                }
                break;
        }

        return $return;
    }

//    function formulate_gmap_script($event_detail) {
//        $map_long = $event_detail['longitude_num'];
//
//        $return = "var PageContact = function() {
//            
//                var _init = function() {
//                    var mapbg = new GMaps({
//                            div: '#gmapbg',
//                            lat: " . $event_detail['latitude_num'] . ",
//                            lng: " . $map_long . ",
//                            scrollwheel: false
//                    });
//
//                    mapbg.addMarker({
//                            lat: " . $event_detail['latitude_num'] . ",
//                            lng: " . $event_detail['longitude_num'] . ",
//                            title: '" . html_escape($event_detail['edition_address']) . "',
//                            infoWindow: {
//                                    content: '<h3>" . html_escape($event_detail['edition_name']) . "</h3><p>" . html_escape($event_detail['edition_address']) . "</p>'
//                            }
//                    });
//                }
//
//                return {
//                    init: function() {
//                        _init();
//                    }
//
//                };
//            }();
//
//            $(document).ready(function() {
//                PageContact.init();
//            });";
//
//        return $return;
//    }

    function formulate_leaflet_script($event_detail) {
        $gps_parts = explode(",", $event_detail['edition_gps']);
        $lat = $gps_parts[0];
        $long = $gps_parts[1];
        $long_center = $long + 0.005;
        $return = "                
            var mymap = L.map('leaflet_map_bg', {
                center: [$lat, $long_center],
                zoom: 15,
                scrollWheelZoom : false,
                touchZoom : true,
                dragging: !L.Browser.mobile
            });
                
            L.marker([$lat, $long]).addTo(mymap);
                
            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
		maxZoom: 18,
		attribution: 'Map data &copy; <a href=\"https://www.openstreetmap.org/\">OpenStreetMap</a> contributors, ' +
			'<a href=\"https://creativecommons.org/licenses/by-sa/2.0/\">CC-BY-SA</a>, ' +
			'Imagery © <a href=\"https://www.mapbox.com/\">Mapbox</a>',
		id: 'mapbox.outdoors'
            }).addTo(mymap);
           ";

//    streets-v9
//    satellite-streets-v9
//    light-v9
//    dark-v9
//    outdoors-v9

        return $return;
    }

    function ics($edition_id) {

        $this->load->model('edition_model');
        $this->load->model('race_model');

        $edition_info['event_detail'] = $this->edition_model->get_edition_detail_full($edition_id);
        $edition_info['event_detail']['race_list'] = $this->race_model->get_race_list($edition_id);


        $this->data_to_view['summary'] = $edition_info['event_detail']['edition_name'];
        // get time
        $date = $edition_info['event_detail']['edition_date'];
        $time = "23:59:00";
        foreach ($edition_info['event_detail']['race_list'] as $race) {
            $race_time = $race['race_time_start'];
            if ($race_time < $time) {
                $time = $race_time;
            }
        }
        $this->data_to_view['datestart'] = strtotime(str_replace("00:00:00", $time, $date));
        $this->data_to_view['dateend'] = $this->data_to_view['datestart'] + (5 * 60 * 60);
        $this->data_to_view['address'] = $edition_info['event_detail']['edition_address_end'];
        $this->data_to_view['uri'] = get_url_from_edition_name(encode_edition_name($edition_info['event_detail']['edition_name']));
        $this->data_to_view['description'] = '';
        $this->data_to_view['filename'] = 'RoadRunning_Event_' . $edition_id . ".ics";
        $this->data_to_view['uid'] = $edition_id;

        $this->load->view("/scripts/ics", $this->data_to_view);

        // wts($this->data_to_view);
        // wts($edition_info);
        // die($edition_id);
    }

    function google_cal($params) {
        $base_url = "http://www.google.com/calendar/event?action=TEMPLATE&trp=true";

        // text
        $text = $params['edition_name'];

        // dates
        $date = $params['edition_date'];
        $time = "23:59:00";
        foreach ($params['race_list'] as $race) {
            $race_time = $race['race_time_start'];
            if ($race_time < $time) {
                $time = $race_time;
            }
        }
        $sdate = strtotime(str_replace("00:00:00", $time, $date));
        $edate = $sdate + (5 * 60 * 60);

        $dates = fdateToCal($sdate) . "/" . fdateToCal($edate);

//        20170402T053000Z/20170402T103000Z
//        20170326T060000/20170326T100000";        

        $sprop = "website:" . $params['url'];
        $details = "website:" . $params['url'];
        $location = urlencode($params['address']);

        return $base_url . "&text=" . $text . "&dates=" . $dates . "&details=" . $details . "&location=" . $location;
    }

}
