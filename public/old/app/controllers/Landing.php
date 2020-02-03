<?php
class Landing extends Frontend_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('event_model');
        $this->load->model('parkrun_model');
        $this->load->model('area_model');
        $this->data_to_header['section']="events";
    }

    // check if method exists, if not calls "page" method
    public function _remap($method, $params = array())
    {
        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $params);
        }
        else
        {
            $this->page($method, $params = array());
        }
    }


    public function page($area) {
        // as daar nie 'n edition_name deurgestuur word nie
        if ($area=="index") { redirect("/calendar");  }
        $area_name=urldecode($area);

        $race_list=[];
        $race_list=$this->event_model->get_event_list_summary($from="date_range",$params=["date_from"=>date("Y-m-d"),"area"=>$area_name]);

        // wts($race_list);
        // die();
        
        // get parkrun info
        $area_id=$this->area_model->get_area_id_from_name($area_name);
        $this->data_to_accordion['parkrun_list'] = $this->parkrun_model->get_parkrun_list(false,$area_id);
        //load accordion view
        $this->data_to_view['parkrun_accordion'] = $this->load->view('parkrun/accordion', $this->data_to_accordion, TRUE);

        $this->data_to_header['title']=ucwords($area_name);        
        $this->data_to_header['meta_description']="List of upcoming road running race events in and around the ".ucwords($area_name)." area";
        $this->data_to_header['keywords']="Running, Listing, Calendar, Races, Events, Race, Marathon, Half-Marathon, 10k, Fun Run";

        // set data to view
        $this->data_to_header['css_to_load']=array();
        $this->data_to_footer['js_to_load']=array();
        $this->data_to_footer['scripts_to_load']=array();

        // set data to the view
        $this->data_to_view["area"]=$area_name;
        $this->data_to_view["race_list_html"]=$this->render_races_accordian_html($race_list);
        // set title bar
        $crumbs=[
            "Races in ".ucwords($area_name)=>"",
            "Home"=>"/",
        ];
        $this->data_to_view["title_bar"]=$this->render_topbar_html(
            [
                "title"=>$this->data_to_header['title'],
                "sub_title" => $this->data_to_header['meta_description'],
                "crumbs"=>$crumbs,
            ]);


        // load view
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/landing/page", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);

    }


}
