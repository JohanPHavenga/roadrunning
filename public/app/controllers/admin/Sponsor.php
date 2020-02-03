<?php
class Sponsor extends Admin_Controller {

    private $return_url="/admin/sponsor";
    private $create_url="/admin/sponsor/create";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/sponsor_model');
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
       
        $this->data_to_view["sponsor_data"] = $this->sponsor_model->get_sponsor_list();
        $this->data_to_view['heading']=["ID","Sponsor Name","Status","Actions"];
        
        $this->data_to_view['create_link']=$this->create_url;
        $this->data_to_header['title'] = "List od Sponsors";

        $this->data_to_header['crumbs'] =
                   [
                   "Home"=>"/admin",
                   "Users"=>"/admin/sponsor",
                   "List"=>"",
                   ];
        
        $this->data_to_header['page_action_list']=
                [
                    [
                        "name"=>"Add Sponsor",
                        "icon"=>"wallet",
                        "uri"=>"sponsor/create/add",
                    ],
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
        $this->load->view("/admin/sponsor/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }


    public function create($action, $id=0) {
        // additional models
        $this->load->model('admin/town_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_view['title'] = uri_string();
        $this->data_to_view['action']=$action;
        $this->data_to_view['form_url']=$this->create_url."/".$action;

        $this->data_to_view['js_to_load']=array("select2.js");
        $this->data_to_view['js_script_to_load']='$(".autocomplete").select2({minimumInputLength: 2});';
        $this->data_to_view['css_to_load']=array("select2.css","select2-bootstrap.css");

        $this->data_to_view['status_dropdown']=$this->sponsor_model->get_status_dropdown();
        $this->data_to_view['town_dropdown']=$this->town_model->get_town_dropdown();

        if ($action=="edit")
        {
            $this->data_to_view['sponsor_detail']=$this->sponsor_model->get_sponsor_detail($id);
            $this->data_to_view['form_url']=$this->create_url."/".$action."/".$id;
        } else {
            $this->data_to_view['sponsor_detail']['sponsor_status']=1;
        }

        // set validation rules
        $this->form_validation->set_rules('sponsor_name', 'Sponsor Name', 'required');
        $this->form_validation->set_rules('sponsor_status', 'Sponsor Status', 'required');

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
            $id=$this->sponsor_model->set_sponsor($action, $id);
            if ($id)
            {
                $alert="Sponsor has been updated";
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
                $this->return_url=base_url("admin/club/create/edit/".$id);
            }  
            
            redirect($this->return_url);
        }
    }


    public function delete($sponsor_id=0) {

        if (($sponsor_id==0) AND (!is_int($sponsor_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: '.$sponsor_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get sponsor detail for nice delete message
        $sponsor_detail=$this->sponsor_model->get_sponsor_detail($sponsor_id);
        // delete record
        $db_del=$this->sponsor_model->remove_sponsor($sponsor_id);
        
        if ($db_del)
        {
            $msg="User has successfully been deleted: ".$sponsor_detail['sponsor_name']." ".$sponsor_detail['sponsor_surname'];
            $status="success";
        }
        else
        {
            $msg="Error in deleting the record:'.$sponsor_id";
            $status="danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
        
    }



}
