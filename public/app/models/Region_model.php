<?php

class Region_model extends Frontend_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("regions");
    }

    public function get_region_list($by_province=false) {

        $this->db->select("regions.*,provinces.*");
        $this->db->join('provinces', 'province_id');
        $this->db->from("regions");
        $this->db->where('province_id !=', 12);
        if ($by_province) {
            $this->db->order_by('province_name');
        }
        $this->db->order_by('region_name');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                if ($by_province) {
                    $data[$row['province_id']]['province_id']=$row['province_id'];
                    $data[$row['province_id']]['province_name']=$row['province_name'];
                    $data[$row['province_id']]['province_slug']=$row['province_slug'];
                    $data[$row['province_id']]['province_abbr']=$row['province_abbr'];
                    $data[$row['province_id']]['region_list'][$row['region_id']] = $row;
                } else {
                    $data[$row['region_id']] = $row;
                }
            }
            return $data;
        }
        return false;
    }
    
    public function get_all_region_ids() {

        $this->db->select("region_id");
        $this->db->from("regions");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[]=$row['region_id'];
            }
            return $data;
        }
        return false;
    }

    public function get_region_dropdown() {
        $this->db->select("region_id, region_name, provinces.province_id, provinces.province_name");
        $this->db->from("regions");
        $this->db->join('provinces', 'province_id');
        $this->db->order_by("province_name, region_name");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[0] = "Select All";
            foreach ($query->result_array() as $row) {
                if ($row['province_id'] != 12) {
                    $data[$row['province_name']][$row['region_id']] = $row['region_name'];
                }
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
                return $this->db->insert('regions', $data);
            case "edit":
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

    public function get_region_id_from_slug($slug) {
        $this->db->select("region_id");
        $this->db->from("regions");
        $this->db->where("region_slug", $slug);
        $region_query = $this->db->get();

        if ($region_query->num_rows() > 0) {
            $result = $region_query->result_array();
            return $result[0]['region_id'];
        } else {
            return false;
        }
    }

    // USER_REGION functions
    public function get_user_region($user_id)
    {
        $this->db->select("region_id");
        $this->db->from("user_region");
        $this->db->where(["user_id" => $user_id]);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row['region_id'];
            }
            return $data;
        } else {
            $this->db->select("region_id");
            $this->db->from("region");
            $query = $this->db->get();
            foreach ($query->result_array() as $row) {
                $data[] = $row['region_id'];
            }
            return $data;
        }
    }

    public function set_user_region($user_id, $region_arr) {
        $this->db->delete('user_region', array('user_id' => $user_id));

        foreach ($region_arr as $region_id) {
            $data = array(
                'user_id' => $user_id,
                'region_id' => $region_id,
            );
            $this->db->insert('user_region', $data);
        }
    }

}
