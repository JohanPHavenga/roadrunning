<?php
class Role extends Admin_Controller {

    private $return_url="/admin/role";
    private $create_url="/admin/role/create";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_model');
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
        
        $this->data_to_view["role_data"] = $this->role_model->get_role_list();
        $this->data_to_view['heading']=["ID","Role Name","Status","Actions"];
        
        $this->data_to_header['title'] = "List of Roles";
        $this->data_to_view['create_link']=$this->create_url;

        $this->data_to_header['crumbs'] =
                   [
                   "Home"=>"/admin",
                   "Users"=>"/admin/role",
                   "List"=>"",
                   ];
        
        $this->data_to_header['page_action_list']=
                [
                    [
                        "name"=>"Add Role",
                        "icon"=>"user",
                        "uri"=>"role/create/add",
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
        $this->load->view("/admin/role/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }
    

    public function create($action, $id=0) {
        // additional models
        $this->load->model('town_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "Role Input Page";
        $this->data_to_view['action']=$action;
        $this->data_to_view['form_url']=$this->create_url."/".$action;

        $this->data_to_view['js_to_load']=array("select2.js");
        $this->data_to_view['js_script_to_load']='$(".autocomplete").select2({minimumInputLength: 2});';
        $this->data_to_view['css_to_load']=array("select2.css","select2-bootstrap.css");

        $this->data_to_view['status_dropdown']=$this->role_model->get_status_dropdown();
        $this->data_to_view['town_dropdown']=$this->town_model->get_town_dropdown();

        if ($action=="edit")
        {
        $this->data_to_view['role_detail']=$this->role_model->get_role_detail($id);
        $this->data_to_view['form_url']=$this->create_url."/".$action."/".$id;
        }

        // set validation rules
        $this->form_validation->set_rules('role_name', 'Role Name', 'required');
        $this->form_validation->set_rules('role_status', 'Role Status', 'required');

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
            $db_write=$this->role_model->set_role($action, $id);
            if ($db_write)
            {
                $alert="Role has been updated";
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

            redirect($this->return_url);
        }
    }


    public function delete($confirm=false) {

        $id=$this->encryption->decrypt($this->input->post('role_id'));

        if ($id==0) {
            $this->session->set_flashdata('alert', 'Cannot delete record: '.$id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        if ($confirm=='confirm')
        {
            $db_del=$this->role_model->remove_role($id);
            if ($db_del)
            {
                $msg="Role has been deleted";
                $status="success";
            }
            else
            {
                $msg="Error committing to the database ID:'.$id";
                $status="danger";
            }

            $this->session->set_flashdata('alert', $msg);
            $this->session->set_flashdata('status', $status);
            redirect($this->return_url);
        }
        else
        {
            $this->session->set_flashdata('alert', 'Cannot delete record');
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }
    }



}
