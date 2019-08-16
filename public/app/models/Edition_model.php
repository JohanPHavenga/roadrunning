<?php
class Edition_model extends MY_model {

    public function __construct() {
        parent::__construct();
    }
    
    public function get_edition_id_from_slug($edition_slug) {
        // CHECK Editions table vir die naame
        $this->db->select("edition_id, edition_name, edition_status");
        $this->db->from("editions");
        $this->db->where("edition_slug", $edition_slug);
        $editions_query = $this->db->get();

        // CHECK Editions_Past vir as die naam van die edition verander
        $this->db->select("edition_id, edition_name");
        $this->db->from("editions_past");
        $this->db->where("edition_slug", $edition_slug);
        $editions_past_query = $this->db->get();

        if ($editions_query->num_rows() > 0) {
            $result = $editions_query->result_array();
            $result[0]['source']="org";
            return $result[0];
        } elseif ($editions_past_query->num_rows() > 0) {
            $result = $editions_past_query->result_array();
            $result[0]['source']="past";
            return $result[0];
        } else {
            return false;
        }
    }
    
    public function get_edition_slug($edition_id) {
        // CHECK Editions table vir die naame
        $this->db->select("edition_slug");
        $this->db->from("editions");
        $this->db->where("edition_id", $edition_id);
        $editions_query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result[0]['edition_slug'];
        } else {
            return false;
        }
    }
    
    public function get_edition_list($query_params=[]) {

        $this->db->select("edition_id, edition_name, edition_date, edition_slug, event_name");
        $this->db->from("editions");
        $this->db->join('events', 'event_id');
        foreach ($query_params as $operator=>$clause_arr) {
            foreach ($clause_arr as $field=>$value) {
                $this->db->$operator($field, $value);
            }
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['edition_id']] = $row;
            }
            return $data;
        }
        return false;
    }
    
}
