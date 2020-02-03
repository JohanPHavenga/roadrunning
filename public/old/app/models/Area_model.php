<?php
class Area_model extends MY_model {

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }
        
        public function record_count() {
            return $this->db->count_all("areas");
        }
        
        public function get_area_list()        {
            
            $this->db->select("areas.*");
            $this->db->from("areas");
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $data[$row['area_id']] = $row;
                }
                return $data;
            }
            return false;

        }
        
        public function get_area_dropdown() {
            $this->db->select("area_id, area_name");
            $this->db->from("areas");
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $data[] = "Please Select";
                foreach ($query->result_array() as $row) {
                    $data[$row['area_id']] = $row['area_name'];
                }
//                return array_slice($data, 0, 500, true);
                return $data;
            }
            return false;
        }
        
        public function get_area_detail($id)
        {
            if( ! ($id)) 
            {
                return false;  
            } 
            else 
            {
                $query = $this->db->get_where('areas', array('area_id' => $id));

                if ($query->num_rows() > 0) {
                    return $query->row_array();
                }
                return false;
            }

        }
        
        public function set_area($action, $id)
        {            
            $data = array(
                        'area_name' => $this->input->post('area_name'),
                        'area_status' => $this->input->post('area_status'),
                    );            
            
            switch ($action) {                    
                case "add": 
                    return $this->db->insert('areas', $data);                    
                case "edit":
                    $data['updated_date']=date("Y-m-d H:i:s");
                    return $this->db->update('areas', $data, array('area_id' => $id));
                    
                default:
                    show_404();
                    break;
            }
            
        }
        
        
        public function remove_area($id) {
            if( ! ($id)) 
            {
                return false;  
            } 
            else 
            {
                $this->db->trans_start();
                $this->db->delete('areas', array('area_id' => $id));             
                $this->db->trans_complete();  
                return $this->db->trans_status();    
            }
        }
        
        public function get_area_id_from_name($area_name)
        {
            $this->db->select("area_id");
            $this->db->from("areas");
            $this->db->where("area_name",$area_name);
            $area_query = $this->db->get();
            
            if ($area_query->num_rows() > 0) 
            {
                $result=$area_query->result_array();
                return $result[0]['area_id'];
            } else  {
                return false;
            }
        }
        
}