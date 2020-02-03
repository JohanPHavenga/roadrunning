<?php
class Parkrun_model extends Admin_model {

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }
        
        public function record_count() {
            return $this->db->count_all("parkruns");
        }
        
        public function get_parkrun_list($incl_not_active=true, $area_id=0)
        {  
            
            $this->db->select("parkruns.*, town_name, area_name, user_email");
            $this->db->from("parkruns");
            $this->db->join('towns', 'town_id', 'left');
            $this->db->join('town_area', 'town_id', 'left');
            $this->db->join('areas', 'area_id', 'left');
            $this->db->join('parkrun_user', 'parkrun_id', 'left');
            $this->db->join('users', 'user_id', 'left');
            if (!$incl_not_active) {                
                $this->db->where('parkrun_status', '1');
            }
            if ($area_id>0) {                
                $this->db->where('area_id', $area_id);
            }
            $this->db->order_by('parkrun_name');
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $data[$row['parkrun_id']] = $row;
                }
                return $data;
            }
            return false;

        }
        
        public function get_parkrun_dropdown() {
            $this->db->select("parkrun_id, parkrun_name");
            $this->db->from("parkruns");
            $this->db->order_by('parkrun_name');
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $data[] = "Please Select";
                foreach ($query->result_array() as $row) {
                    $data[$row['parkrun_id']] = $row['parkrun_name'];
                }
                return $data;
            }
            return false;
        }
        
        public function get_parkrun_detail($id)
        {
            if( ! ($id)) 
            {
                return false;  
            } 
            else 
            {
                $this->db->select("parkruns.*, town_name, user_id");
                $this->db->from("parkruns");
                $this->db->join('towns', 'town_id', 'left');
                $this->db->join('parkrun_user', 'parkrun_id', 'left');
                $this->db->where('parkrun_id', $id);
                $query = $this->db->get();

                if ($query->num_rows() > 0) {
                    return $query->row_array();
                }
                return false;
            }

        }
        
        public function set_parkrun($action, $parkrun_id)
        {            
            $parkrun_data = array(
                        'parkrun_name' => $this->input->post('parkrun_name'),
                        'parkrun_status' => $this->input->post('parkrun_status'),
                        'town_id' => $this->input->post('town_id'),
                        'parkrun_url' => $this->input->post('parkrun_url'),
                        'parkrun_address' => $this->input->post('parkrun_address'),
                        'latitude_num' => $this->input->post('latitude_num'),
                        'longitude_num' => $this->input->post('longitude_num'),
                        'parkrun_comment' => $this->input->post('parkrun_comment'),
                    );        
            $parkrun_user_data = ["parkrun_id"=>$parkrun_id,"user_id"=>$this->input->post('user_id')];   
            
            switch ($action) {                    
                case "add":                     
                    $this->db->trans_start();
                    $this->db->insert('parkruns', $parkrun_data);  
                    // get edition ID from Insert
                    $parkrun_id=$this->db->insert_id();          
                    // update data array
                    $parkrun_user_data["parkrun_id"]=$parkrun_id;
                    $this->db->insert('parkrun_user', $parkrun_user_data);
                    $this->db->trans_complete();  
                    break;
                case "edit":
                    // add updated date to both data arrays
                    $parkrun_data['updated_date']=date("Y-m-d H:i:s");
                    
                    // start SQL transaction
                    $this->db->trans_start();
                    // chcek if record already exists
                    $item_exists = $this->db->get_where('parkrun_user', array('parkrun_id' => $parkrun_id, 'user_id' => $this->input->post('user_id')));
                    if ($item_exists->num_rows() == 0)  
                    {
                        $parkrun_data['updated_date']=date("Y-m-d H:i:s");
                        $this->db->delete('parkrun_user', array('parkrun_id' => $parkrun_id));
                        $this->db->insert('parkrun_user', $parkrun_user_data);                        
                    } 
                    $this->db->update('parkruns', $parkrun_data, array('parkrun_id' => $parkrun_id));                  
                    $this->db->trans_complete();  
                    break;   
                default:
                    show_404();
                    break;
            }
            // return ID if transaction successfull
            if ($this->db->trans_status())
            {
                return $parkrun_id;
            } else {
                return false;
            }
            
        }
        
        
        public function remove_parkrun($id) {
            if( ! ($id)) 
            {
                return false;  
            } 
            else 
            {
                $this->db->trans_start();
                $this->db->delete('parkruns', array('parkrun_id' => $id));             
                $this->db->trans_complete();  
                return $this->db->trans_status();    
            }
        }
        
}