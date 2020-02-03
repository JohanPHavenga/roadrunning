<?php

class Region_model extends MY_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("regions");
    }

    public function get_region_list() {

        $this->db->select("regions.*,province_name");
        $this->db->join('provinces', 'province_id');
        $this->db->from("regions");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['region_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_region_dropdown() {
        $this->db->select("region_id, region_name");
        $this->db->from("regions");
        $this->db->order_by("region_name");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['region_id']] = $row['region_name'];
            }
            return $data;
        }
        return false;
    }

    public function get_region_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("regions.*,province_name");
            $this->db->join('provinces', 'province_id');
            $this->db->from("regions");
            $this->db->where("region_id", $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_region($action, $id) {
        $data = array(
            'region_name' => $this->input->post('region_name'),
            'region_status' => $this->input->post('region_status'),
            'province_id' => $this->input->post('province_id'),
        );
        
        switch ($action) {
            case "add":
                $data['region_slug'] = url_title($data['region_name']);
                return $this->db->insert('regions', $data);
            case "edit":
                $data['region_slug'] = url_title($data['region_name']);
                $data['updated_date'] = date("Y-m-d H:i:s");
                return $this->db->update('regions', $data, array('region_id' => $id));
            default:
                show_404();
                break;
        }
    }

    public function remove_region($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('regions', array('region_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function get_region_id_from_name($region_name) {
        $this->db->select("region_id");
        $this->db->from("regions");
        $this->db->where("region_name", $region_name);
        $region_query = $this->db->get();

        if ($region_query->num_rows() > 0) {
            $result = $region_query->result_array();
            return $result[0]['region_id'];
        } else {
            return false;
        }
    }
    
    public function update_field($id, $field, $value) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $data[$field]=$value;
            $data['updated_date'] = date("Y-m-d H:i:s");
            $this->db->update('regions', $data, array('region_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
