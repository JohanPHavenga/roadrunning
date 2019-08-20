<?php

class Event_model extends MY_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("events");
    }
    
    public function get_region_list() {
        $this->db->select("regions.region_id, region_name, region_slug, regions.province_id");
        $this->db->from("events");
        $this->db->join('towns', 'town_id');
        $this->db->join('regions', 'region_id');
        $this->db->order_by('region_name');
        $this->db->distinct();
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['region_id']] = $row;
            }
            return $data;
        }
        return false;
    }
    
    public function get_province_list() {
        $this->db->select("provinces.province_id, province_name, province_slug");
        $this->db->from("events");
        $this->db->join('towns', 'town_id');
        $this->db->join('regions', 'region_id');
        $this->db->join('provinces', 'regions.province_id=provinces.province_id');
        $this->db->order_by('province_name');
        $this->db->distinct();
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['province_id']] = $row;
            }
            return $data;
        }
        return false;
    }

}
