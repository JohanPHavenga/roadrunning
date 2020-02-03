<?php
class Faq extends Frontend_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->data_to_header['section']="faq";
    }

    public function _remap($method, $params = array())
    {
        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $params);
        }
        else
        {
            $this->view($method, $params);
        }
    }

    public function view()
    {
        $this->data_to_header['title'] = "FAQ";

        // set title bar
        $this->data_to_header["title_bar"]=$this->render_topbar_html(
            [
                "crumbs"=>["FAQ"=>"/faq","Home"=>"/"],
            ]
        );
        
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view('faq/view', $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

}
