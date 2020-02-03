<?php

class Asareg_model extends Admin_model {

    public $table = "asa_regs";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all($this->table);
    }

    public function get_asareg_field_array() {
        $fields = $this->db->list_fields($this->table);
        foreach ($fields as $field) {
            $data[$field] = "";
        }
        return $data;
    }

    public function get_asareg_list() {
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['asa_reg_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_asareg_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where('asa_regs', array('asa_reg_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_asareg($action, $id, $data) {
        switch ($action) {
            case "add":
                return $this->db->insert('asa_regs', $data);
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                return $this->db->update('asa_regs', $data, array('asa_reg_id' => $id));
            default:
                show_404();
                break;
        }
    }

    public function remove_asareg($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('asa_regs', array('asa_reg_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function get_asareg_id_from_distance($distance) {
        $this->db->select("asa_reg_id");
        $this->db->from("asa_regs");
        $this->db->where("asa_reg_distance_to >= ", $distance);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data[0]['asa_reg_id'];
        }
        return false;
    }

}
