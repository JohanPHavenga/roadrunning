<?php

class MY_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // new way to pull status list using the status_
    public function get_status_name($status_id) {
        $this->db->select("status_name");
        $this->db->from("status");
        $this->db->where('status_id', $status_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data = $row['status_name'];
            }
            return $data;
        }
        return false;
    }

}
