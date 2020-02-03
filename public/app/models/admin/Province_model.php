<?php

class Province_model extends Admin_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

//    public function get_province_list($id = FALSE) {
//        if ($id === FALSE) {
//            $query = $this->db->get('provinces');
//            return $query->result_array();
//        }
//
//        $query = $this->db->get_where('provinces', array('province_id' => $id));
//        return $query->result_array();
//    }
    
    public function get_province_list($query_params=[]) {
        $this->db->select("*");
        $this->db->from("provinces");
        foreach ($query_params as $operator=>$clause_arr) {
            foreach ($clause_arr as $field=>$value) {
                $this->db->$operator($field, $value);
            }
        }
        $query = $this->db->get();
        
         if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['province_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_province_dropdown() {
        $this->db->select("province_id, province_name");
        $this->db->from("provinces");
        $this->db->order_by("province_name");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
//            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['province_id']] = $row['province_name'];
            }
//                return array_slice($data, 0, 500, true);
            return $data;
        }
        return false;
    }
    
    public function update_field($id, $field, $value) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->update('provinces', [$field => $value], array('province_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
