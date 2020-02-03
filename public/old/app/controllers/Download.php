<?php
class Download extends Frontend_Controller {    
    

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('download');
    }
    
    public function file($id_type,$id,$filename) {        
        $path="./uploads/".$id_type."/".$id."/".$filename;
        force_download($path, NULL);
    }

}

