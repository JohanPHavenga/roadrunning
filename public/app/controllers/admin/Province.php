<?php
class Province extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/province_model');
    }

    public function _remap($method, $params = array())
    {
        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $params);
        }
        else
        {
            $this->view();
        }
    }

//    public function view($id = FALSE) {
//
//        $this->data_to_header['title'] = "List of Provinces";
//        $this->data_to_view['list'] = $this->province_model->get_province_list($id);
//        // as daar data is
//        if ($this->data_to_view["list"]) {
//            $this->data_to_view['heading']=ftableHeading(array_keys($this->data_to_view['list'][key($this->data_to_view['list'])]));
//        }
//
//        $this->load->library('table');
//
//        $this->load->view($this->header_url, $this->data_to_header);
//        $this->load->view($this->view_url, $this->data_to_view);
//        $this->load->view($this->footer_url, $this->data_to_footer);
//    }
    
    public function view() {
        // load helpers / libraries
        $this->load->library('table');
        
        $this->data_to_view["province_data"] = $this->province_model->get_province_list();
        $this->data_to_view['heading']=["ID","Province Name","Slug"];
        
        $this->data_to_header['title'] = "List of Provinces";

        $this->data_to_header['crumbs'] =
                   [
                   "Home"=>"/admin",
                   "Users"=>"/admin/province",
                   "List"=>"",
                   ];
        
        $this->data_to_view['url']=$this->url_disect();
        
        $this->data_to_header['css_to_load']=array(
            "assets/admin/plugins/datatables/datatables.min.css",
            "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css",
            );

        $this->data_to_footer['js_to_load']=array(
            "assets/admin/scripts/datatable.js",
            "assets/admin/plugins/datatables/datatables.min.js",
            "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js",
            "assets/admin/plugins/bootstrap-confirmation/bootstrap-confirmation.js",
            );

        $this->data_to_footer['scripts_to_load']=array(
            "assets/admin/scripts/table-datatables-managed.js",
            );

        // load view
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/admin/province/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }
    
    
     // create slugs for all the provinces
    function generate_slugs() {
        $this->load->model('admin/province_model');
        $province_list = $this->province_model->get_province_list();
        $n = 0;
        foreach ($province_list as $id => $province) {
            $this->province_model->update_field($id, "province_slug", url_title($province['province_name']));
            $n++;
        }

        echo "Done<br>";
        echo "<b>". $n . "</b> slugs were updated<br>";
    }

}
