<?php

class Entrytype_model extends Frontend_model {

    public $table = "entrytypes";
    public $no_info_id = 5;

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all($this->table);
    }

    public function get_entrytype_field_array() {
        $fields = $this->db->list_fields($this->table);
        foreach ($fields as $field) {
            $data[$field] = "";
        }
        return $data;
    }

    public function get_entrytype_list() {
        $this->db->select("entrytypes.*");
        $this->db->from($this->table);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['entrytype_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_entrytype_dropdown() {
        $this->db->select("entrytype_id, entrytype_name");
        $this->db->from($this->table);
        $this->db->where("entrytype_status",1);
        $this->db->order_by("entrytype_id", "ASC");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
//            $data[] = "No Information";
            foreach ($query->result_array() as $row) {
                $data[$row['entrytype_id']] = $row['entrytype_name'];
            }
            move_to_top($data, $this->no_info_id);
            return $data;
        }
        return false;
    }

    public function get_entrytype_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where($this->table, array('entrytype_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_entrytype($action, $id) {
        $data = array(
            'entrytype_name' => $this->input->post('entrytype_name'),
            'entrytype_status' => $this->input->post('entrytype_status'),
        );

        switch ($action) {
            case "add":
                return $this->db->insert($this->table, $data);
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                return $this->db->update($this->table, $data, array('entrytype_id' => $id));

            default:
                show_404();
                break;
        }
    }

    public function remove_entrytype($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete($this->table, array('entrytype_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function set_edition_entrytype($edition_id, $entrytype_id) {
        if (!$edition_id) {
            return false;
        }
        $this->db->trans_start();
        $this->db->delete('edition_entrytype', array('edition_id' => $edition_id));
        $insert_array = [
            "edition_id" => $edition_id,
            "entrytype_id" => $entrytype_id,
        ];
        $id = $this->db->insert('edition_entrytype', $insert_array);
        $this->db->trans_complete();

        return $id;
    }

}
