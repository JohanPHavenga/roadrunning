<?php

class Tagtype_model extends Admin_model {

    public $table = "tagtypes";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("tagtypes");
    }

    public function get_tagtype_field_array() {
        $fields = $this->db->list_fields($this->table);
        foreach ($fields as $field) {
            $data[$field] = "";
        }
        return $data;
    }

    public function get_tagtype_list() {
        $this->db->select("tagtypes.*");
        $this->db->from("tagtypes");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['tagtype_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_tagtype_dropdown() {
        $this->db->select("tagtype_id, tagtype_name");
        $this->db->from("tagtypes");
        $this->db->order_by("tagtype_name");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['tagtype_id']] = $row['tagtype_name'];
            }
            return $data;
        }
        return false;
    }

    public function get_tagtype_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where($this->table, array('tagtype_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_tagtype($action, $id) {
        $data = array(
            'tagtype_name' => $this->input->post('tagtype_name'),
            'tagtype_status' => $this->input->post('tagtype_status'),
        );

        switch ($action) {
            case "add":
                return $this->db->insert($this->table, $data);
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                return $this->db->update($this->table, $data, array('tagtype_id' => $id));

            default:
                show_404();
                break;
        }
    }

    public function remove_tagtype($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete($this->table, array('tagtype_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
