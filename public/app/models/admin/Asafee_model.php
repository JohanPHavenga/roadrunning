<?php

class Asafee_model extends Admin_model {

    public $table = "asa_fees";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all($this->table);
    }

    public function get_asafee_field_array() {
        $fields = $this->db->list_fields($this->table);
        foreach ($fields as $field) {
            $data[$field] = "";
        }
        return $data;
    }

    public function get_asafee_list() {
        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->join("asa_members", "asa_member_id");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['asa_fee_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_asafee_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where('asa_fees', array('asa_fee_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_asafee($action, $id, $data) {
        switch ($action) {
            case "add":
                return $this->db->insert('asa_fees', $data);
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                return $this->db->update('asa_fees', $data, array('asa_fee_id' => $id));
            default:
                show_404();
                break;
        }
    }

    public function remove_asafee($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('asa_fees', array('asa_fee_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function get_asafee_from_distance($asa_member_id, $year, $distance, $field="asa_fee_snr") {
        
        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->where('asa_member_id',  $asa_member_id);
        $this->db->where("asa_fee_year", $year);
        $this->db->where("asa_fee_distance_to > ",$distance);
        $this->db->where("asa_fee_distance_from <= ",$distance);
//        echo $this->db->get_compiled_select(); exit();
        $query = $this->db->get();   
                    
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data[0][$field];
        }
        return 0;
    }

}
