<?php
class Racetype extends Admin_Controller {

    private $return_url="/admin/racetype";
    private $create_url="/admin/racetype/create";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/racetype_model');
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
        
        $this->data_to_view["racetype_data"] = $this->racetype_model->get_racetype_list();
        $this->data_to_view['heading']=["ID","RaceType","Abbr","Icon","Status","Actions"];
        
        $this->data_to_view['create_link']=$this->create_url;
        $this->data_to_header['title'] = "List of RaceTypes";
        $this->data_to_header['crumbs'] =
                   [
                   "Home"=>"/admin",
                   "RaceType"=>"/admin/racetype",
                   "List"=>"",
                   ];
        
        $this->data_to_header['page_action_list']=
                [
                    [
                        "name"=>"Add RaceType",
                        "icon"=>"compass",
                        "uri"=>"racetype/create/add",
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
        $this->load->view("/admin/racetype/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }


    public function create($action, $id=0) {
        // additional models
        $this->load->model('admin/town_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "RaceType Input Page";
        $this->data_to_view['action']=$action;
        $this->data_to_view['form_url']=$this->create_url."/".$action;

        $this->data_to_view['js_to_load']=array("select2.js");
        $this->data_to_view['js_script_to_load']='$(".autocomplete").select2({minimumInputLength: 2});';
        $this->data_to_view['css_to_load']=array("select2.css","select2-bootstrap.css");

        $this->data_to_view['status_dropdown']=$this->racetype_model->get_status_dropdown();
        $this->data_to_view['town_dropdown']=$this->town_model->get_town_dropdown();

        if ($action=="edit")
        {
        $this->data_to_view['racetype_detail']=$this->racetype_model->get_racetype_detail($id);
        $this->data_to_view['form_url']=$this->create_url."/".$action."/".$id;
        }

        // set validation rules
        $this->form_validation->set_rules('racetype_name', 'Racetype Name', 'required');
        $this->form_validation->set_rules('racetype_abbr', 'Racetype Abbreviation', 'required');
        $this->form_validation->set_rules('racetype_status', 'Racetype Status', 'required');

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
            $db_write=$this->racetype_model->set_racetype($action, $id);
            if ($db_write)
            {
                $alert="Racetype has been updated";
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
                $this->return_url=base_url("admin/racetype/create/edit/".$id);
            }   
            
            redirect($this->return_url);
        }
    }

    
    public function delete($racetype_id) {

        if (($racetype_id == 0) AND ( !is_int($racetype_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get edition detail for nice delete message
        $racetype_detail = $this->racetype_model->get_racetype_detail($racetype_id);
        // delete record
        $db_del = $this->racetype_model->remove_racetype($racetype_id);


        if ($db_del) {
            $msg = "Race Type <b>".$racetype_detail['racetype_name']."</b> has been deleted";
            $status = "success";
        } else {
            $msg = "Error committing to the database ID:'.$racetype_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

}
