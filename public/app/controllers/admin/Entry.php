<?php
class Entry extends Admin_Controller {

    private $return_url="/admin/entry";
    private $create_url="/admin/entry/create";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/entry_model');
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

        // pagination
        // pagination config
        $per_page=50;
        $uri_segment=4;
        $total_rows=$this->entry_model->record_count();
        $config=fpaginationConfig($this->return_url, $per_page, $total_rows, $uri_segment);

        // pagination init
        $this->load->library("pagination");
        $this->pagination->initialize($config);
        $this->data_to_view["pagination"]=$this->pagination->create_links();


        // set data
        $page = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;

        $this->data_to_view["list"] = $this->entry_model->get_entry_list($per_page, $page);
        $this->data_to_view['create_link']=$this->create_url;
        $this->data_to_view['delete_arr']=["controller"=>"entry","id_field"=>"entry_id"];
        $this->data_to_view['title'] = uri_string();

        // as daar data is
        if ($this->data_to_view["list"]) {
             $this->data_to_view['heading']=ftableHeading(array_keys($this->data_to_view['list'][key($this->data_to_view['list'])]),2);
        }
        // load view
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view($this->view_url, $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }


    public function create($action, $id=0) {
        // additional models
        $this->load->model('admin/race_model');
        $this->load->model('admin/user_model');
        $this->load->model('admin/club_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_view['title'] = uri_string();
        $this->data_to_view['action']=$action;
        $this->data_to_view['form_url']=$this->create_url."/".$action;

        $this->data_to_view['css_to_load']=array(
            "assets/admin/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css",
            );

        $this->data_to_view['js_to_load']=array(
            "assets/admin/plugins/moment.min.js",
            "assets/admin/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js",
            );

        $this->data_to_view['scripts_to_load']=array(
            "assets/admin/scripts/components-date-time-pickers.js",
            );

        $this->data_to_view['race_dropdown']=$this->race_model->get_race_dropdown();
        $this->data_to_view['user_dropdown']=$this->user_model->get_user_dropdown();
        $this->data_to_view['club_dropdown']=$this->club_model->get_club_dropdown();

        if ($action=="edit")
        {
        $this->data_to_view['entry_detail']=$this->entry_model->get_entry_detail($id);
        $this->data_to_view['form_url']=$this->create_url."/".$action."/".$id;
        }

        // set validation rules
        $this->form_validation->set_rules('entry_number', 'Race Number', 'required');
        $this->form_validation->set_rules('race_id', 'Race', 'required|numeric|greater_than[0]',["greater_than"=>"Please select a Race"]);
        $this->form_validation->set_rules('user_id', 'User', 'required|numeric|greater_than[0]',["greater_than"=>"Please select a User"]);
        $this->form_validation->set_rules('club_id', 'Club', 'required|numeric|greater_than[0]',["greater_than"=>"Please select a Club"]);

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
            $db_write=$this->entry_model->set_entry($action, $id);
            if ($db_write)
            {
                $alert="Entry has been updated";
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

        $id=$this->encryption->decrypt($this->input->post('entry_id'));

        if ($id==0) {
            $this->session->set_flashdata('alert', 'Cannot delete record: '.$id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        if ($confirm=='confirm')
        {
            $db_del=$this->entry_model->remove_entry($id);
            if ($db_del)
            {
                $msg="Entry has been deleted";
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
