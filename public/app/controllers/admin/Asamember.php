<?php
class Asamember extends Admin_Controller {

    private $return_url="/admin/asamember";
    private $create_url="/admin/asamember/create";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/asamember_model');
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
        
        $this->data_to_view["asamember_data"] = $this->asamember_model->get_asamember_list();
        $this->data_to_view['heading']=["ID","ASA Member Name","Abbreviation","URL","Status","Actions"];
        
//        $this->data_to_view['delete_arr']=["controller"=>"asamember","id_field"=>"asa_member_id"];
        $this->data_to_header['title'] = "List of ASA Members";
        $this->data_to_view['create_link']=$this->create_url;

        $this->data_to_header['crumbs'] =
                   [
                   "Home"=>"/admin",
                   "Users"=>"/admin/asamember",
                   "List"=>"",
                   ];
        
        $this->data_to_header['page_action_list']=
                [
                    [
                        "name"=>"Add ASA Member",
                        "icon"=>"umbrella",
                        "uri"=>"asamember/create/add",
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
        $this->load->view("/admin/asamember/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }


    public function create($action, $id=0) {
        // additional models

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "ASA Members Input Page";
        $this->data_to_view['action']=$action;
        $this->data_to_view['form_url']=$this->create_url."/".$action;

        $this->data_to_view['js_to_load']=array("select2.js");
        $this->data_to_view['js_script_to_load']='$(".autocomplete").select2({minimumInputLength: 2});';
        $this->data_to_view['css_to_load']=array("select2.css","select2-bootstrap.css");

        $this->data_to_view['status_dropdown']=$this->asamember_model->get_status_dropdown();

        if ($action=="edit")
        {
        $this->data_to_view['asamember_detail']=$this->asamember_model->get_asamember_detail($id);
        $this->data_to_view['form_url']=$this->create_url."/".$action."/".$id;
        } else {
            $this->data_to_view['asamember_detail']['asa_member_status']=1;
        }

        // set validation rules
        $this->form_validation->set_rules('asa_member_name', 'ASA Member Name', 'required');
        $this->form_validation->set_rules('asa_member_abbr', 'ASA Member Abbreviation', 'required');
//        $this->form_validation->set_rules('asa_member_url', 'ASA Member URL', 'required');
        $this->form_validation->set_rules('asa_member_status', 'ASA Member Status', 'required');

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
            $db_write=$this->asamember_model->set_asamember($action, $id);
            if ($db_write)
            {
                $alert="ASA Member has been updated";
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
                $this->return_url=base_url("admin/asamember/create/edit/".$id);
            }   
            
            redirect($this->return_url);
        }
    }    
    
    public function delete($asa_member_id = 0) {

        if (($asa_member_id == 0) AND ( !is_int($asa_member_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $asa_member_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get detail for nice delete message
        $member_detail = $this->asamember_model->get_asamember_detail($asa_member_id);
        // delete record
        $db_del = $this->asamember_model->remove_asamember($asa_member_id);

        if ($db_del) {
            $msg = "ASA Memeber has successfully been removed: " . $member_detail['asa_member_name'];
            $status = "success";
        } else {
            $msg = "Error in deleting the record:'.$asa_member_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

}
