<?php

/**
 * Description of Import Model
 *
 * @author TechArise Team
 *
 * @email  info@techarise.com
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Import_model_phpexcel extends Admin_model {

    private $_batchImport;

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function setBatchImport($batchImport) {
        $this->_batchImport = $batchImport;
    }

    // save data
    public function importData() {
        $data = $this->_batchImport;
        $this->db->insert_batch('import', $data);
    }

    // get employee list
    public function employeeList() {
        $this->db->select(array('e.id', 'e.first_name', 'e.last_name', 'e.email', 'e.dob', 'e.contact_no'));
        $this->db->from('import as e');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert_into_temp_table($table, $data) {
        $this->db->truncate($table);
        foreach ($data as $row) {
            $this->db->insert($table, $row);
        }

        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function get_temp_table_data() {
        $query = $this->db->get("temp_import_event");
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['temp_id']]=$row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function check_ids($field) {
        $this->db->select($field);
        $this->db->from("temp_import_event");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                if (!$row[$field]) {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }
    
     public function update_field($temp_id, $field, $value) {
        if (!($temp_id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->update('temp_import_event', [$field => $value, "timestamp" => date("Y-m-d H:i:s")], array('temp_id' => $temp_id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
