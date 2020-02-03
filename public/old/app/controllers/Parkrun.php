<?php
class Parkrun extends Frontend_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('parkrun_model');
    }

    // check if method exists, if not calls "view" method
    public function _remap($method, $params = array())
    {
        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $params);
        }
        else
        {
            $this->detail($method, $params = array());
        }
    }


    public function calendar()
    {
        // load helpers / libraries
        $this->load->library('table');
        $this->data_to_header['section']="parkrun";

        $this->data_to_header['title']="List of Parkrun events";
        $this->data_to_header['meta_description']="List of Parkrun events in the Western Cape";
        $this->data_to_header['keywords']="Parkrun, Park, Races, Events, Listing, Race, Run, Fun Run";

        // get race info
        $this->data_to_accordion['parkrun_list'] = $this->parkrun_model->get_parkrun_list(false);
        //load accordion view
        $this->data_to_view['accordion'] = $this->load->view('parkrun/accordion', $this->data_to_accordion, TRUE);

        // set title bar
        $this->data_to_view["title_bar"]=$this->render_topbar_html(
            [
                "crumbs"=>$this->crumbs_arr,
            ]);

        // load view
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("parkrun/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }
    
    
    public function detail() {
        
    }
    


}
