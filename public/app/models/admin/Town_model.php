<?php

class Town_model extends Admin_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("towns");
    }

//    public function get_town_list($limit, $start) {
//        $this->db->limit($limit, $start);
//
//        $this->db->select("*");
//        $this->db->from("towns");
//        $this->db->join('provinces', 'provinces.province_id = towns.province_id', 'left');
//        $this->db->join('regions', 'region_id','left');
//        $query = $this->db->get();
//
//        if ($query->num_rows() > 0) {
//            foreach ($query->result_array() as $row) {
//                $data[$row['town_id']] = $row;
//            }
//            return $data;
//        }
//        return false;
//    }

    public function get_town_list($query_params = []) {
        $this->db->select("*");
        $this->db->from("towns");
        $this->db->join('provinces', 'provinces.province_id = towns.province_id', 'left');
        $this->db->join('regions', 'region_id', 'left');
        foreach ($query_params as $operator => $clause_arr) {
            foreach ($clause_arr as $field => $value) {
                $this->db->$operator($field, $value);
            }
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['town_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_town_dropdown() {
        $this->db->select("town_id, town_name, region_id");
        $this->db->from("towns");
        $this->db->join('regions', 'region_id', 'left');
        $this->db->order_by('town_name');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                if ($row['region_id'] != 61) {
                    $data[$row['town_id']] = $row['town_name'];
                }
            }
            return $data;
        }
        return false;
    }

    // AFTER AREA IS NO LONGER IN USE, USE THIS TO GET THE TOWN_DROPDOWN
//    public function get_town_dropdown() {
//        $this->db->select("town_id, town_name, region_id");
//        $this->db->from("towns");
//        $this->db->join('regions', 'region_id');
//        $this->db->order_by('town_name');
//        $query = $this->db->get();
//
//        if ($query->num_rows() > 0) {
//            $data[] = "Please Select";
//            foreach ($query->result_array() as $row) {
//               $data[$row['town_id']] = $row['town_name'];
//            }
//            return $data;
//        }
//        return false;
//    }

    public function get_town_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("towns.*,area_id");
            $this->db->from("towns");
            $this->db->join('town_area', 'town_id', 'left');
            $this->db->join('regions', 'region_id', 'left');
            $this->db->where("town_id", $id);
            $query = $this->db->get();

//            $query = $this->db->get_where('towns', array('town_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function town_search($ss) {
        $this->db->select("town_id, town_name, province_name, region_name");
        $this->db->from("towns");
        $this->db->join('provinces', 'provinces.province_id = towns.province_id', 'left');
        $this->db->join('regions', 'region_id', 'left');
        $this->db->where("town_name LIKE '$ss%'");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = [
                    'id' => $row['town_id'],
                    'value' => $row['town_name'] . " (" . $row['province_name'] . ")",
                ];
            }
            return $data;
        }
        return false;
    }

    public function town_full_search($ss) {
        $this->db->select("*");
        $this->db->from("towns");
        $this->db->join('provinces', 'provinces.province_id = towns.province_id', 'left');
        $this->db->join('regions', 'region_id', 'left');
        $this->db->join('town_area', 'town_id', 'left');
        $this->db->join('areas', 'area_id', 'left');
        $this->db->or_where("town_name LIKE '%" . addslashes($ss) . "%'");
        $this->db->or_where("town_name_alt LIKE '%" . addslashes($ss) . "%'");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['town_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_town_id($town_name) {
        $this->db->select("town_id, region_id");
        $this->db->from("towns");
        $this->db->where("LOWER(town_name)", strtolower($town_name));
//        echo $this->db->get_compiled_select();
//        exit();
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                if ($row['region_id'] == 61) {
                    $town_id = -1;
                } else {
                    $town_id = $row['town_id'];
                    break;
                }
            }
            return $town_id;
        }
        return false;
    }

    public function set_town($action, $town_id) {
        $town_data = array(
            'town_name' => $this->input->post('town_name'),
            'town_name_alt' => $this->input->post('town_name_alt'),
            'latitude_num' => $this->input->post('latitude_num'),
            'longitude_num' => $this->input->post('longitude_num'),
            'province_id' => $this->input->post('province_id'),
            'region_id' => $this->input->post('region_id'),
        );
        $town_area_data = ["town_id" => $town_id, "area_id" => $this->input->post('area_id')];
        
        switch ($action) {
            case "add":
                $this->db->trans_start();
                $this->db->insert('towns', $town_data);
                // get edition ID from Insert
                $town_id = $this->db->insert_id();
                // update data array);
                $this->db->trans_complete();
                break;
            case "edit":
                // add updated date to both data arrays
                $town_data['updated_date'] = date("Y-m-d H:i:s");

                // start SQL transaction
                $this->db->trans_start();
                $this->db->update('towns', $town_data, array('town_id' => $town_id));
                $this->db->trans_complete();
                break;
            default:
                show_404();
                break;
        }
        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return $town_id;
        } else {
            return false;
        }
    }

    function remove_town($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('towns', array('town_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
