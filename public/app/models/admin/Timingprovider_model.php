<?php

class Timingprovider_model extends Admin_model {

    public $table = "timingproviders";
    public $no_info_id = 1;

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all($this->table);
    }

    public function get_timingprovider_field_array() {
        $fields = $this->db->list_fields($this->table);
        foreach ($fields as $field) {
            $data[$field] = "";
        }
        return $data;
    }

    public function get_timingprovider_list() {
        $this->db->select("timingproviders.*");
        $this->db->from($this->table);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['timingprovider_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_timingprovider_dropdown() {
        $this->db->select("timingprovider_id, timingprovider_name");
        $this->db->from($this->table);
        $this->db->where("timingprovider_status",1);
        $this->db->order_by("timingprovider_id", "ASC");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
//            $data[] = "No Information";
            foreach ($query->result_array() as $row) {
                $data[$row['timingprovider_id']] = $row['timingprovider_name'];
            }
            move_to_top($data, $this->no_info_id);
            return $data;
        }
        return false;
    }

    public function get_timingprovider_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where($this->table, array('timingprovider_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_timingprovider($action, $id) {
        $data = array(
            'timingprovider_name' => $this->input->post('timingprovider_name'),
            'timingprovider_abbr' => $this->input->post('timingprovider_abbr'),
            'timingprovider_status' => $this->input->post('timingprovider_status'),
        );

        switch ($action) {
            case "add":
                return $this->db->insert($this->table, $data);
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                return $this->db->update($this->table, $data, array('timingprovider_id' => $id));

            default:
                show_404();
                break;
        }
    }

    public function remove_timingprovider($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete($this->table, array('timingprovider_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function get_edition_timingprovider_list($edition_id = null) {
        if (!$edition_id) {
            return false;
        }
        $query = $this->db->get_where('edition_timingprovider', array('edition_id' => $edition_id));
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['timingprovider_id']] = $row['timingprovider_id'];
            }
        } else {
            $data = [$this->no_info_id];
        }
        return $data;
    }

    public function set_edition_timingprovider($edition_id, $timingprovider_id) {
        if (!$edition_id) {
            return false;
        }
        $this->db->trans_start();
        $this->db->delete('edition_timingprovider', array('edition_id' => $edition_id));
        $insert_array = [
            "edition_id" => $edition_id,
            "timingprovider_id" => $timingprovider_id,
        ];
        $id = $this->db->insert('edition_timingprovider', $insert_array);
        $this->db->trans_complete();

        return $id;
    }

}
