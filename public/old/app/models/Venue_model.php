<?php

class Venue_model extends MY_model {

    public $table = "venues";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("venues");
    }

    public function get_venue_field_array() {
        $fields = $this->db->list_fields($this->table);
        foreach ($fields as $field) {
            $data[$field] = "";
        }
        return $data;
    }

    public function get_venue_list() {
        $this->db->select("venues.*,province_name");
        $this->db->from("venues");
        $this->db->join("provinces", "province_id");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['venue_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_venue_dropdown() {
        $this->db->select("venue_id, venue_name");
        $this->db->from("venues");
        $this->db->where("venue_status",true);
        $this->db->order_by("venue_name");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['venue_id']] = $row['venue_name'];
            }
            return $data;
        }
        return false;
    }

    public function get_venue_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where($this->table, array('venue_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_venue($action, $id) {
        $data = array(
            'venue_name' => $this->input->post('venue_name'),
            'venue_status' => $this->input->post('venue_status'),
            'province_id' => $this->input->post('province_id'),
        );

        switch ($action) {
            case "add":
                return $this->db->insert($this->table, $data);
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                return $this->db->update($this->table, $data, array('venue_id' => $id));

            default:
                show_404();
                break;
        }
    }

    public function remove_venue($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete($this->table, array('venue_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
