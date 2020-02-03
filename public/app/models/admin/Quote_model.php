<?php
class Quote_model extends Admin_model {

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }
        
        public function record_count() {
            return $this->db->count_all("quotes");
        }
        
        public function get_quote_list()
        {            
            $this->db->select("quotes.*");
            $this->db->from("quotes");
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $data[$row['quote_id']] = $row;
                }
                return $data;
            }
            
//            if ($query->num_rows() > 0) {
//                foreach ($query->result_array() as $row) {
//                    $data[] = $row['quote_quote'];
//                }
//                return $data;
//            }
            return false;

        }      
        
        public function get_quote_detail($id)
        {
            if( ! ($id)) 
            {
                return false;  
            } 
            else 
            {
                $query = $this->db->get_where('quotes', array('quote_id' => $id));

                if ($query->num_rows() > 0) {
                    return $query->row_array();
                }
                return false;
            }

        }
        
        public function set_quote($action, $id)
        {            
            $data = array(
                        'quote_quote' => strip_tags($this->input->post('quote_quote')),
                    );            
            
            switch ($action) {                    
                case "add": 
                    return $this->db->insert('quotes', $data);                    
                case "edit":
                    $data['updated_date']=date("Y-m-d H:i:s");
                    return $this->db->update('quotes', $data, array('quote_id' => $id));
                    
                default:
                    show_404();
                    break;
            }
            
        }
        
        
        public function remove_quote($id) {
            if( ! ($id)) 
            {
                return false;  
            } 
            else 
            {
                $this->db->trans_start();
                $this->db->delete('quotes', array('quote_id' => $id));             
                $this->db->trans_complete();  
                return $this->db->trans_status();    
            }
        }
      
        
}