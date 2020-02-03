<?php

class Delete extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('formulate');

    }

    public function _remap($params)
    {
        $this->delete($params);
    }


    private function delete($params) {
        $decrypt=$this->encryption->decrypt(base64_decode($this->uri->segment(3)));
        $decrypt_arr= explode("|", $decrypt);
        $this->data_to_view['controller']=$decrypt_arr[0];
        $this->data_to_view['id_field']=$decrypt_arr[1];
        $this->data_to_view['id']=$this->encryption->encrypt($decrypt_arr[2]);

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->data_to_view["title"]="Delete Confirmation";

//        wts($this->data_to_view);
//        echo $decrypt_arr[2];


        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view('admin/delete', $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }
}
