<?php
        // pagination      
        // pagination config
        $per_page=50;
        $uri_segment=3;
        $url=base_url().$this->view_url;
        $total_rows=$this->asanumber_model->record_count();
        $config=fpaginationConfig($url, $per_page, $total_rows, $uri_segment);                
        
        // pagination init
        $this->load->library("pagination");        
        $this->pagination->initialize($config);
        $data["pagination"]=$this->pagination->create_links();  
        
        // set data
        $page = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;

