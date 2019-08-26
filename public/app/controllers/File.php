<?php

class File extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('download');
        $this->load->model('file_model');
        $this->load->model('edition_model');
    }
    
    public function _remap($method, $params = array())
    {
        if (method_exists(__CLASS__, $method)) {
            $this->$method($params);
        } else {
            $this->handler($method,$params);
        }
    }

    public function handler($linked_to,$params = []) {        
        if (($linked_to == "index") || (empty($params))) { redirect("/race-calendar"); }
        $edition_slug=$params[0];
        $filetype_name=$params[1];
        $file_name=$params[2];
        
        if ($linked_to=="edition") {
            $file_id=null;
            $edition_info=$this->edition_model->get_edition_id_from_slug($edition_slug);    
            $file_list = $this->file_model->get_file_list("edition",$edition_info['edition_id'], true);
            $filetype_list=$this->file_model->get_filetype_list();
            $filetype_id=$filetype_list[$filetype_name];
            foreach ($file_list[$filetype_id] as $key=>$file_detail) {
                if ($file_detail['file_name']==$file_name) {
                    $file_id=$file_detail['file_id'];
                }
            }
        } else {
            // decrypt the file ID
            $file_id = my_decrypt($file_id);
        }
        
//        wts($params);
//        wts($file_list);
//        wts($filetype_list);
//        echo $filetype_id;
//        die();
        
        // check for INT
        if (!preg_match('/^\d+$/', $file_id)) {
            $this->show_my_404("File could not be found", "danger");
        }
        // Get details
        $file_detail = $this->file_model->get_file_detail($file_id);
        // If there is no details
        if (!$file_detail) {
            $this->show_my_404("No file found with that file ID", "danger");
        }
        // get ID type
//        if ($file_detail['edition_id']>0) { $id_type="edition"; $id=$file_detail['edition_id']; }
        $id_type = $file_detail['file_linked_to'];
        $id = $file_detail['linked_id'];
        // add race id section here
        // set path
        $path = "./uploads/" . $id_type . "/" . $id . "/" . $file_detail['file_name'];
        
        switch ($file_detail['file_ext']) {
            default:
                $this->display($file_detail['file_type'],$path);
//                header("X-Robots-Tag: noindex, nofollow", true);
//                $this->download($path);
                break;
        }
        
    }

    public function download($path) {
        force_download($path, NULL);
    }

    public function display($content_type, $path) {
//        $path = base_url($path);
        $this->output
                ->set_content_type($content_type)
                ->set_output(file_get_contents($path));
//        die($path);
    }

}
