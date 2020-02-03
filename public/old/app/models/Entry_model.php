<?php
class Entry_model extends MY_model {

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }
        
        public function record_count() {
            return $this->db->count_all("entries");
        }
        
        public function get_entry_list($limit, $start)
        {
            $this->db->limit($limit, $start);    
            
            $this->db->select("entries.*, race_name, user_name, user_surname, club_name");
            $this->db->from("entries");
            $this->db->join('races', 'race_id', 'left');
            $this->db->join('users', 'user_id', 'left');
            $this->db->join('clubs', 'clubs.club_id=entries.club_id', 'left');
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $data[$row['entry_id']] = $row;
                }
                return $data;
            }
            return false;

        }        
        
        public function get_entry_detail($id)
        {
            if( ! ($id)) 
            {
                return false;  
            } 
            else 
            {
                $this->db->select("entries.*");
                $this->db->from("entries");
                $this->db->where('entry_id', $id);
                $query = $this->db->get();
                

                if ($query->num_rows() > 0) {
                    return $query->row_array();
                }
                return false;
            }

        }
        
        public function set_entry($action, $id)
        {            
            $entry_data = array(
                        'race_id' => $this->input->post('race_id'),
                        'user_id' => $this->input->post('user_id'),
                        'entry_number' => $this->input->post('entry_number'),
                        'entry_time' => $this->input->post('entry_time'),
                        'club_id' => $this->input->post('club_id'),
                    );        
            
            switch ($action) {                    
                case "add":                     
                    $this->db->trans_start();
                    $this->db->insert('entries', $entry_data);  
                    $this->db->trans_complete();  
                    return $this->db->trans_status();               
                case "edit":
                    // add updated date to both data arrays
                    $entry_data['updated_date']=date("Y-m-d H:i:s");
                    
                    // start SQL transaction
                    $this->db->trans_start();
                    $this->db->update('entries', $entry_data, array('entry_id' => $id));                  
                    $this->db->trans_complete();  
                    return $this->db->trans_status();    
                default:
                    show_404();
                    break;
            }
            
        }
        
        
        public function remove_entry($id) {
            if( ! ($id)) 
            {
                return false;  
            } 
            else 
            {
                $this->db->trans_start();
                $this->db->delete('entries', array('entry_id' => $id));             
                $this->db->trans_complete();  
                return $this->db->trans_status();    
            }
        }
        
}