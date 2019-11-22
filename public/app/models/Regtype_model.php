<?php

class Regtype_model extends MY_model {

    public $table = "regtypes";
    public $no_info_id = 3;

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all($this->table);
    }

    public function get_regtype_field_array() {
        $fields = $this->db->list_fields($this->table);
        foreach ($fields as $field) {
            $data[$field] = "";
        }
        return $data;
    }

    public function get_regtype_list() {
        $this->db->select("regtypes.*");
        $this->db->from($this->table);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['regtype_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_regtype_dropdown() {
        $this->db->select("regtype_id, regtype_name");
        $this->db->from($this->table);
        $this->db->where("regtype_status",1);
        $this->db->order_by("regtype_id", "ASC");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
//            $data[] = "No Information";
            foreach ($query->result_array() as $row) {
                $data[$row['regtype_id']] = $row['regtype_name'];
            }
            move_to_top($data, $this->no_info_id);
            return $data;
        }
        return false;
    }

    public function get_regtype_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where($this->table, array('regtype_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_regtype($action, $id) {
        $data = array(
            'regtype_name' => $this->input->post('regtype_name'),
            'regtype_status' => $this->input->post('regtype_status'),
        );

        switch ($action) {
            case "add":
                return $this->db->insert($this->table, $data);
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                return $this->db->update($this->table, $data, array('regtype_id' => $id));

            default:
                show_404();
                break;
        }
    }

    public function remove_regtype($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete($this->table, array('regtype_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function get_edition_regtype_list($edition_id = null) {
        if (!$edition_id) {
            return false;
        }
        $query = $this->db->get_where('edition_regtype', array('edition_id' => $edition_id));
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['regtype_id']] = $row['regtype_id'];
            }
        } else {
            $data = [$this->no_info_id];
        }
        return $data;
    }

    public function set_edition_regtype($edition_id, $regtype_id) {
        if (!$edition_id) {
            return false;
        }
        $this->db->trans_start();
        $this->db->delete('edition_regtype', array('edition_id' => $edition_id));
        $insert_array = [
            "edition_id" => $edition_id,
            "regtype_id" => $regtype_id,
        ];
        $id = $this->db->insert('edition_regtype', $insert_array);
        $this->db->trans_complete();

        return $id;
    }

}
