<?php
class Quote extends Admin_Controller {

    private $return_url="/admin/quote";
    private $create_url="/admin/quote/create";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('quote_model');
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
        
        $this->data_to_view["quote_data"] = $this->quote_model->get_quote_list();
        $this->data_to_view['heading']=["ID","Quote Name","Actions"];
        
        $this->data_to_header['title'] = "List of Quotes";
        $this->data_to_view['create_link']=$this->create_url;

        $this->data_to_header['crumbs'] =
                   [
                   "Home"=>"/admin",
                   "Users"=>"/admin/quote",
                   "List"=>"",
                   ];
        
        $this->data_to_header['page_action_list']=
                [
                    [
                        "name"=>"Add Quote",
                        "icon"=>"speech",
                        "uri"=>"quote/create/add",
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
        $this->load->view("/admin/quote/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }
    

    public function create($action, $id=0) {
        // additional models
        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "Quote Input Page";
        $this->data_to_view['action']=$action;
        $this->data_to_view['form_url']=$this->create_url."/".$action;

        if ($action=="edit")
        {
        $this->data_to_view['quote_detail']=$this->quote_model->get_quote_detail($id);
        $this->data_to_view['form_url']=$this->create_url."/".$action."/".$id;
        }

        // set validation rules
        $this->form_validation->set_rules('quote_quote', 'Quote', 'required');
        
        $this->data_to_header['css_to_load']=array(    
            "plugins/bootstrap-summernote/summernote.css",        
            );

        $this->data_to_footer['js_to_load']=array(       
            "plugins/moment.min.js",
            "plugins/bootstrap-summernote/summernote.min.js",     
            );

        $this->data_to_footer['scripts_to_load']=array(
            "scripts/admin/components-editors.js",
            );

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
            $db_write=$this->quote_model->set_quote($action, $id);
            if ($db_write)
            {
                $alert="Quote has been updated";
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
                $this->return_url=base_url("admin/quote/create/edit/".$id);
            }  

            redirect($this->return_url);
        }
    }


    public function delete($confirm=false) {

        $id=$this->encryption->decrypt($this->input->post('quote_id'));

        if ($id==0) {
            $this->session->set_flashdata('alert', 'Cannot delete record: '.$id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        if ($confirm=='confirm')
        {
            $db_del=$this->quote_model->remove_quote($id);
            if ($db_del)
            {
                $msg="Quote has been deleted";
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
