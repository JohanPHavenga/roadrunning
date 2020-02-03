<?php
Class Seo extends Frontend_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('area_model');
    }

    function sitemap()
    {
        $this->load->model('event_model');
        $confirmed_races_url_list=$this->event_model->get_event_list_sitemap(['confirmed'=>1]);        
        $upcoming_close_url_list=$this->event_model->get_event_list_sitemap(['upcoming_close'=>1]);
        $upcoming_further_url_list=$this->event_model->get_event_list_sitemap(['upcoming_further'=>1]);
        
        
        $past_close_url_list=$this->event_model->get_event_list_sitemap(['past_close'=>1]);
        $has_results_url_list=$this->event_model->get_event_list_sitemap(['has_results_year'=>1]);
        $no_results_url_list=$this->event_model->get_event_list_sitemap(['no_results_year'=>1]);
        $old_url_list=$this->event_model->get_event_list_sitemap(['old'=>1]);
        
        $area_list=$this->area_model->get_area_list();
        $date_list=$this->get_date_list();
        
//        wts($area_list);
//        die();
        
        $data["pages"]=[];
        
        // high level pages
        $data["pages_high"]=[
            ["url"=>"","change_freq"=>"weekly","priority"=>"1"],
            ["url"=>"calendar","change_freq"=>"weekly","priority"=>"1"],
            ["url"=>"calendar/results","change_freq"=>"weekly","priority"=>"1"],
            ["url"=>"parkrun/calendar","change_freq"=>"monthly","priority"=>"1"],
            ["url"=>"faq","change_freq"=>"monthly","priority"=>"1"],
            ["url"=>"contact","change_freq"=>"monthly","priority"=>"1"],
            ];
        $data["pages"]=array_merge_recursive($data['pages'],$data['pages_high']);
        
        // area pages
        foreach ($area_list as $area_id=>$area) {
            $url= strtolower(str_replace(" ", "", $area['area_name']));
            $data['pages_area'][$area_id]['url']=$url;
            $data['pages_area'][$area_id]['change_freq']="weekly";
            $data['pages_area'][$area_id]['priority']="0.8";
        }       
        $data["pages"]=array_merge_recursive($data['pages'],$data['pages_area']);
        
        // date ranges
        foreach ($date_list as $year => $month_list) {
            foreach ($month_list as $month_number => $month_name) {                
                $url= "calendar/".$year."/".$month_number;
                $data['date_list'][$year.$month_number]['url']=$url;
                $data['date_list'][$year.$month_number]['change_freq']="weekly";
                $data['date_list'][$year.$month_number]['priority']="0.8";
            }
        }
        $data["pages"]=array_merge_recursive($data['pages'],$data['date_list']);
        
        // confirmed races
        foreach ($confirmed_races_url_list as $key=>$url) {
            $data['pages_confirmed'][$key]['url']=$url;
            $data['pages_confirmed'][$key]['change_freq']="weekly";
            $data['pages_confirmed'][$key]['priority']="0.8";
        }        
        $data["pages"]=array_merge_recursive($data['pages'], $data['pages_confirmed']);
        
        // upcoming next 5 month races
        foreach ($upcoming_close_url_list as $key=>$url) {
            $data['pages_upcoming_close'][$key]['url']=$url;
            $data['pages_upcoming_close'][$key]['change_freq']="weekly";
            $data['pages_upcoming_close'][$key]['priority']="0.6";
        }        
        $data["pages"]=array_merge_recursive($data['pages'], $data['pages_upcoming_close']);
        
        // upcoming races
        foreach ($upcoming_further_url_list as $key=>$url) {
            $data['pages_upcoming_further'][$key]['url']=$url;
            $data['pages_upcoming_further'][$key]['change_freq']="monthly";
            $data['pages_upcoming_further'][$key]['priority']="0.5";
        }        
        $data["pages"]=array_merge_recursive($data['pages'], $data['pages_upcoming_further']);
        
        // past close races
        foreach ($past_close_url_list as $key=>$url) {
            $data['pages_past_close'][$key]['url']=$url;
            $data['pages_past_close'][$key]['change_freq']="monthly";
            $data['pages_past_close'][$key]['priority']="0.5";
        }        
        $data["pages"]=array_merge_recursive($data['pages'], $data['pages_past_close']);
        
        
        // races with results
        foreach ($has_results_url_list as $key=>$url) {
            $data['pages_results'][$key]['url']=$url;
            $data['pages_results'][$key]['change_freq']="yearly";
            $data['pages_results'][$key]['priority']="0.4";
        }        
        $data["pages"]=array_merge_recursive($data['pages'], $data['pages_results']);
        
        // races with no results
        foreach ($no_results_url_list as $key=>$url) {
            $data['pages_no_results'][$key]['url']=$url;
            $data['pages_no_results'][$key]['change_freq']="yearly";
            $data['pages_no_results'][$key]['priority']="0.2";
        }        
        $data["pages"]=array_merge_recursive($data['pages'], $data['pages_no_results']);
        
        // old races
        foreach ($old_url_list as $key=>$url) {
            $data['pages_old'][$key]['url']=$url;
            $data['pages_old'][$key]['change_freq']="never";
            $data['pages_old'][$key]['priority']="0.1";
        }        
        $data["pages"]=array_merge_recursive($data['pages'], $data['pages_old']);
        
        
//        wts($data["pages"]);
//        die();
        
        $data['events_upcoming']=[];   
        $data['events_results']=[];   
        $data['events_past']=[];
        
        header("Content-Type: text/xml;charset=iso-8859-1");
        $this->load->view("sitemap",$data);
    }
}

