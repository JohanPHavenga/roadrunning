<?php
class File extends Frontend_Controller {    
    

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('download');        
        $this->load->model('file_model');
    }
    
    
    public function handler($file_id) {    
        // decrypt the file ID
        $file_id = my_decrypt($file_id);
        // check for INT
        if (!preg_match('/^\d+$/', $file_id)) { $this->show_my_404("That is not a valid file ID","danger"); }
        // Get details
        $file_detail=$this->file_model->get_file_detail($file_id);
        // If there is no details
        if (!$file_detail) { $this->show_my_404("No file found with that file ID","danger"); }           
        // get ID type
//        if ($file_detail['edition_id']>0) { $id_type="edition"; $id=$file_detail['edition_id']; }
        $id_type=$file_detail['file_linked_to'];
        $id=$file_detail['linked_id'];
        // add race id section here
        
        // set path
        $path="./uploads/".$id_type."/".$id."/".$file_detail['file_name'];        
        switch ($file_detail['file_ext']) {
//            case ".pdf":
//                $this->display('application/pdf',$path);
//                break;
            default:
                header("X-Robots-Tag: noindex, nofollow", true);
                $this->download($path);
                break;
        }
       
    }
    
    public function download($path) {        
        force_download($path, NULL);
    }

    public function display($content_type, $path) {
        $path=base_url($path);
        $this->output
           ->set_content_type($content_type)
           ->set_output(file_get_contents($path));
//        die($path);
    }
}

