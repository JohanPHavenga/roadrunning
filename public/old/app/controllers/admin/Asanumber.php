<?php
class Asanumber extends Admin_Controller {

    private $return_url="/admin/asa_number";
    private $create_url="/admin/asa_number/create";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('asanumber_model');
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
        $total_rows=$this->asa_number_model->record_count();
        $config=fpaginationConfig($this->return_url, $per_page, $total_rows, $uri_segment);

        // pagination init
        $this->load->library("pagination");
        $this->pagination->initialize($config);
        $this->data_to_view["pagination"]=$this->pagination->create_links();

        // set data
        $page = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;

        $this->data_to_view["list"] = $this->asa_number_model->get_asa_number_list($per_page, $page);
        $this->data_to_view['create_link']=$this->create_url;
        $this->data_to_view['delete_arr']=["controller"=>"asa_number","id_field"=>"asa_number_id"];
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
        $this->load->model('user_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_view['title'] = uri_string();
        $this->data_to_view['action']=$action;
        $this->data_to_view['form_url']=$this->create_url."/".$action;

        $this->data_to_view['js_to_load']=array("moment.js", "bootstrap-datetimepicker.min.js");
        $this->data_to_view['js_script_to_load']="$('.asa_number_year').datetimepicker({format: 'YYYY', viewMode: 'years'});";
        $this->data_to_view['css_to_load']=array("bootstrap-datetimepicker.min.css");

        $this->data_to_view['user_dropdown']=$this->user_model->get_user_dropdown();

        if ($action=="edit")
        {
        $this->data_to_view['asa_number_detail']=$this->asa_number_model->get_asa_number_detail($id);
        $this->data_to_view['form_url']=$this->create_url."/".$action."/".$id;
        }

        // set validation rules
        $this->form_validation->set_rules('asa_number_num', 'ASA Number', 'required');
        $this->form_validation->set_rules('asa_number_year', 'ASA Number Year', 'required|numeric');
        $this->form_validation->set_rules('user_id', 'User', 'required|numeric|greater_than[0]',["greater_than"=>"Please select an User"]);

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
            $db_write=$this->asa_number_model->set_asa_number($action, $id);
            if ($db_write)
            {
                $alert="ASA Number has been updated";
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

        $id=$this->encryption->decrypt($this->input->post('asa_number_id'));

        if ($id==0) {
            $this->session->set_flashdata('alert', 'Cannot delete record: '. $id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        if ($confirm=='confirm')
        {
            $db_del=$this->asa_number_model->remove_asa_number($id);
            if ($db_del)
            {
                $msg="ASA number has been deleted";
                $status="success";
            }
            else
            {
                $msg="Error committing to the database ID:".$id;
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
