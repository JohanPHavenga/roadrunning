<?php
class Asamember_model extends MY_model {

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }
        
        public function record_count() {
            return $this->db->count_all("asa_members");
        }
        
        public function get_asamember_list($show_only_active=false)
        {            
            $this->db->select("asa_members.*");
            $this->db->from("asa_members");
            if ($show_only_active) {
                $this->db->where("asa_member_status",1);
            }
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $data[$row['asa_member_id']] = $row;
                }
                return $data;
            }
            return false;
        }
        
        public function get_asamember_dropdown($type="abbr") {
            $this->db->select("asa_member_id, asa_member_name, asa_member_abbr");
            $this->db->from("asa_members");
            $this->db->order_by("asa_member_name");
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $data[] = "None";
                foreach ($query->result_array() as $row) {
                    $data[$row['asa_member_id']] = $row['asa_member_'.$type];
                }
//                return array_slice($data, 0, 500, true);
                return $data;
            }
            return false;
        }
        
        public function get_asamember_detail($id)
        {
            if( ! ($id)) 
            {
                return false;  
            } 
            else 
            {
                $query = $this->db->get_where('asa_members', array('asa_member_id' => $id));

                if ($query->num_rows() > 0) {
                    return $query->row_array();
                }
                return false;
            }

        }
        
        public function set_asamember($action, $id)
        {            
            $data = array(
                        'asa_member_name' => $this->input->post('asa_member_name'),
                        'asa_member_abbr' => $this->input->post('asa_member_abbr'),
                        'asa_member_url' => $this->input->post('asa_member_url'),
                        'asa_member_status' => $this->input->post('asa_member_status'),
                    );            
            
            switch ($action) {                    
                case "add": 
                    return $this->db->insert('asa_members', $data);                    
                case "edit":
                    $data['updated_date']=date("Y-m-d H:i:s");
                    return $this->db->update('asa_members', $data, array('asa_member_id' => $id));
                    
                default:
                    show_404();
                    break;
            }
            
        }
        
        
        public function remove_asamember($id) {
            if( ! ($id)) 
            {
                return false;  
            } 
            else 
            {
                $this->db->trans_start();
                $this->db->delete('asa_members', array('asa_member_id' => $id));             
                $this->db->trans_complete();  
                return $this->db->trans_status();    
            }
        }
        
        
        public function set_asamember_edition($asa_member_id, $edition_id)
        {            
            $data_arr=array('asa_member_id' => $asa_member_id,'edition_id' => $edition_id);
            
            $this->db->trans_start();
            $this->db->delete('edition_asa_member',array('edition_id' => $edition_id));
            $this->db->insert('edition_asa_member', $data_arr);
            $this->db->trans_complete();
           
            return $this->db->trans_status();                         
            
        }
        
}