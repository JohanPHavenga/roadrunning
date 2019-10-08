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

    public function detail($slug, $url_params=[]) {   
        // as daar nie 'n edition_slug deurgestuur word nie
        if ($slug == "index") { redirect("/race-calendar"); }
        if (empty($url_params)) { $url_params[]="summary"; }
        
        $this->load->model('race_model');
        $this->load->model('file_model');
        $this->load->model('url_model');
        
        // gebruik slug om ID te kry
        $edition_data = $this->edition_model->get_edition_id_from_slug($slug);
        $this->data_to_views['edition_data'] = $this->edition_model->get_edition_detail($edition_data['edition_id']);
        $this->data_to_views['race_list'] = $this->race_model->get_race_list($edition_data['edition_id']);
        $this->data_to_views['file_list'] = $this->file_model->get_file_list("edition",$edition_data['edition_id'],true);
        $this->data_to_views['url_list'] = $this->url_model->get_url_list("edition",$edition_data['edition_id']);
        
        $this->data_to_views['page_title']= substr($edition_data['edition_name'],0,-5)." - ". fdateHumanFull($this->data_to_views['edition_data']['edition_date'],true);
        $this->data_to_views['event_menu'] = $this->get_event_menu($slug);

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('event/header', $this->data_to_views);
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
    
    private function get_event_menu($slug) {
        $menu_arr = [
            "summary" => [
                "display" => "Summary",
                "loc" => base_url("event/".$slug),
            ],
            "entries" => [
                "display" => "How to enter",
                "loc" => base_url("event/".$slug."/entries"),
            ],
            "race_day" => [
                "display" => "Race day info",
                "loc" => base_url("event/".$slug."/race-day-information"),
            ],
            "races" => [
                "display" => "The Races",
                "loc" => base_url("event/".$slug."/races"),
            ],
            "route_maps" => [
                "display" => "Route Maps",
                "loc" => base_url("event/".$slug."/route-maps"),
            ],
            "subscribe" => [
                "display" => "Subscribe",
                "loc" => base_url("event/".$slug."/subscribe"),
            ],
            "sponsors" => [
                "display" => "Sponsors",
                "loc" => base_url("event/".$slug."/sponsors"),
            ],
            "club" => [
                "display" => "More events by this Club",
                "loc" => base_url("event/".$slug."/club-other-races"),
            ],
            "previous" => [
                "display" => "Previous edition",
                "loc" => base_url("event/".$slug."/"),
            ],
            "next" => [
                "display" => "Next edition",
                "loc" => base_url("event/".$slug."/"),
            ],
            "print" => [
                "display" => "Print",
                "loc" => base_url("event/".$slug."/print"),
            ],
            "results" => [
                "display" => "Results",
                "loc" => base_url("event/".$slug."/results"),
            ],
            "contact" => [
                "display" => "Contact Organisers",
                "loc" => base_url("event/".$slug."/contact"),
            ],
        ];
        return $menu_arr;
    }
}
