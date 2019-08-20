<?php

class Province_model extends MY_model {

    public function __construct() {
        parent::__construct();
    }

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

    public function get_province_dropdown_data() {
        $this->db->select("province_id, province_name");
        $this->db->from("provinces");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['province_id']] = $row['province_name'];
            }
            return $data;
        }
        return false;
    }

}
