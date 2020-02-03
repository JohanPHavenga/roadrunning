<?php

class MY_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // Load any front-end only dependencies
    }

    public function get_status_dropdown() {
        $this->db->select("*");
        $this->db->from("status");
        $this->db->limit(3);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['status_id']] = $row['status_name'];
            }
            return $data;
        }
        return false;
    }

    // new way to pull status list using the status_
    public function get_status_list($use = NULL) {
        $this->db->select("*");
        $this->db->from("status");
        if ($use) {
            $this->db->like('status_use', $use);
        }
        $this->db->order_by("status_order", "ASC");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['status_id']] = $row['status_name'];
            }
            return $data;
        }
        return false;
    }

    public function get_linked_to_dropdown($count = 6, $start = 0) {
        $this->db->select("*");
        $this->db->from("linked_to");
        $this->db->limit($count, $start);
//        echo $this->db->get_compiled_select();   exit();
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['linked_to_name']] = $row['linked_to_name'];
            }
            return $data;
        }
        return false;
    }

    public function get_linked_to_list($count = 6, $start = 0) {
        $this->db->select("*");
        $this->db->from("linked_to");
        $this->db->limit($count, $start);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['linked_to_id']] = $row['linked_to_name'];
            }
            return $data;
        }
        return false;
    }

    public function set_results_flag($linked_to, $linked_id, $flag) {
        $id_field = $linked_to . "_id";
        $table = $linked_to . "s";
//        $field = $linked_to . "_results_isloaded";
//        $this->db->trans_start();
//        $this->db->update($table, [$field => $flag], array($id_field => $linked_id));
//        $this->db->trans_complete();
        // new results status field also needs to be set
        if ($flag) {
            $status = 11;

//        $field = $linked_to . "_results_status";
            $this->db->trans_start();
//        $this->db->update($table, [$field => $status], array($id_field => $linked_id));
            // nuwe edition_info_status field
            if ($linked_to == "edition") {
                $this->db->update($table, ["edition_info_status" => $status], array($id_field => $linked_id));
            }
            $this->db->trans_complete();
        } 
        
        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return true;
        } else {
            return false;
        }
    }

    public function log_runtime($runtime_data) {
        return $this->db->insert('runtimes', $runtime_data);
    }

}
