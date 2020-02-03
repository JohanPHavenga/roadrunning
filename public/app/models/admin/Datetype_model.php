<?php

class Datetype_model extends Admin_model {

    public $table = "datetypes";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("datetypes");
    }

    public function get_datetype_field_array() {
        $fields = $this->db->list_fields($this->table);
        foreach ($fields as $field) {
            $data[$field] = "";
        }
        return $data;
    }

    public function get_datetype_list() {
        $this->db->select("datetypes.*");
        $this->db->from("datetypes");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['datetype_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_datetype_dropdown() {
        $this->db->select("datetype_id, datetype_name");
        $this->db->from("datetypes");
        $this->db->order_by("datetype_name");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['datetype_id']] = $row['datetype_name'];
            }
            return $data;
        }
        return false;
    }

    public function get_datetype_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where($this->table, array('datetype_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_datetype($action, $id) {
        $data = array(
            'datetype_name' => $this->input->post('datetype_name'),
            'datetype_status' => $this->input->post('datetype_status'),
            'datetype_display' => $this->input->post('datetype_display'),
        );

        switch ($action) {
            case "add":
                return $this->db->insert($this->table, $data);
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                return $this->db->update($this->table, $data, array('datetype_id' => $id));

            default:
                show_404();
                break;
        }
    }

    public function remove_datetype($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete($this->table, array('datetype_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
