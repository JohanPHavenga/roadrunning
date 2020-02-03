<?php
class Parkrun extends Admin_Controller {

    private $return_url="/admin/parkrun";
    private $create_url="/admin/parkrun/create";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('parkrun_model');
    }

    public function _remap($method, $params = array())
    {
        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $params);
        }
        else
        {
            $this->view($params);
        }
    }

    public function view() {
        // load helpers / libraries
        $this->load->library('table');

        
        $this->data_to_view["parkrun_data"] = $this->parkrun_model->get_parkrun_list();
        $this->data_to_view['heading']=["ID","Name","Status","Town","Area","Contact","Actions"];
        
        $this->data_to_view['create_link']=$this->create_url;
        $this->data_to_header['title'] = "List of Parkruns";
        
        $this->data_to_header['crumbs'] =
                   [
                   "Home"=>"/admin",
                   "Users"=>"/admin/parkrun",
                   "List"=>"",
                   ];
        
        $this->data_to_header['page_action_list']=
                [
                    [
                        "name"=>"Add Parkrun",
                        "icon"=>"badge",
                        "uri"=>"parkrun/create/add",
                    ],
                ];

        $this->data_to_view['url']=$this->url_disect();
        
        $this->data_to_header['css_to_load']=array(
            "plugins/datatables/datatables.min.css",
            "plugins/datatables/plugins/bootstrap/datatables.bootstrap.css",
            );

        $this->data_to_footer['js_to_load']=array(
            "scripts/admin/datatable.js",
            "plugins/datatables/datatables.min.js",
            "plugins/datatables/plugins/bootstrap/datatables.bootstrap.js",
            "plugins/bootstrap-confirmation/bootstrap-confirmation.js",
            );

        $this->data_to_footer['scripts_to_load']=array(
            "scripts/admin/table-datatables-managed.js",
            );
        
        // load view
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/admin/parkrun/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }


    public function create($action, $id=0) {
        // additional models
        $this->load->model('town_model');
        $this->load->model('user_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "Parkrun Input Page";
        $this->data_to_view['action']=$action;
        $this->data_to_view['form_url']=$this->create_url."/".$action;

        $this->data_to_header['css_to_load']=array(
            "plugins/typeahead/typeahead.css",
            "plugins/bootstrap-summernote/summernote.css",
            );

        $this->data_to_footer['js_to_load']=array(
            "plugins/typeahead/handlebars.min.js",
            "plugins/typeahead/typeahead.bundle.min.js",
            "plugins/moment.min.js",
            "plugins/bootstrap-summernote/summernote.min.js",
            );

        $this->data_to_footer['scripts_to_load']=array(
            "scripts/admin/autocomplete.js",
            "scripts/admin/components-editors.js",
            );


        $this->data_to_view['status_dropdown']=$this->parkrun_model->get_status_dropdown();
        $this->data_to_view['user_dropdown']=$this->user_model->get_user_dropdown();
        $this->data_to_view['town_dropdown']=$this->town_model->get_town_dropdown();

        if ($action=="edit")
        {
            $this->data_to_view['parkrun_detail']=$this->parkrun_model->get_parkrun_detail($id);
            $this->data_to_view['form_url']=$this->create_url."/".$action."/".$id;
        } else {
            $this->data_to_view['parkrun_detail']['parkrun_status']=1;
        }

        // set validation rules
        $this->form_validation->set_rules('parkrun_name', 'Parkrun Name', 'required');
        $this->form_validation->set_rules('town_id', 'Town', 'required|numeric|greater_than[0]',["greater_than"=>"Please select a Town"]);
        $this->form_validation->set_rules('user_id', 'Contact Person', 'required|numeric|greater_than[0]',["greater_than"=>"Please select a Contact Person"]);
        $this->form_validation->set_rules('parkrun_url', 'URL', 'valid_url');


        // load correct view
        if ($this->form_validation->run() === FALSE)
        {
            $this->data_to_view['return_url']=$this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        }
        else
        {
//            wts($_REQUEST);
//            die();

            $id=$this->parkrun_model->set_parkrun($action, $id);
            if ($id)
            {
                $alert="Parkrun details has been ".$action."ed";
                $status="success";
            }
            else
            {
                $alert="Error committing to the database";
                $status="danger";
            }

            $this->session->set_flashdata([
                'alert'=>$alert,
                'status'=>$status,
                ]);

            // save_only takes you back to the edit page.
            if (array_key_exists("save_only", $_POST)) {
                $this->return_url=base_url("admin/parkrun/create/edit/".$id);
            }  
            
            redirect($this->return_url);
        }
    }


    public function delete($parkrun_id=0) {

        if (($parkrun_id==0) AND (!is_int($parkrun_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: '.$parkrun_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get parkrun detail for nice delete message
        $parkrun_detail=$this->parkrun_model->get_parkrun_detail($parkrun_id);
        // delete record
        $db_del=$this->parkrun_model->remove_parkrun($parkrun_id);
        
        if ($db_del)
        {
            $msg="Parkrun has successfully been deleted: ".$parkrun_detail['parkrun_name'];
            $status="success";
        }
        else
        {
            $msg="Error in deleting the record:'.$parkrun_id";
            $status="danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
        
    }



}
