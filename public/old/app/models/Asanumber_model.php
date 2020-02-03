<?php

class Asanumber_model extends MY_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("asa_numbers");
    }

    public function get_asa_number_list($limit, $start) {
        $this->db->limit($limit, $start);

        $this->db->select("asa_numbers.*, user_name, user_surname");
        $this->db->from("asa_numbers");
        $this->db->join('users', 'user_id', 'left');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['asa_number_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_asa_number_dropdown() {
        $this->db->select("asa_number_id, asa_number_num, asa_number_year");
        $this->db->from("asa_numbers");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['asa_number_id']] = $row['asa_number_year'] . "-" . $row['asa_number_num'];
            }
//                return array_slice($data, 0, 500, true);
            return $data;
        }
        return false;
    }

    public function get_asa_number_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("asa_numbers.*, user_name, user_surname");
            $this->db->from("asa_numbers");
            $this->db->join('users', 'user_id', 'left');
            $this->db->where('asa_number_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_asa_number($action, $id) {
        $asa_number_data = array(
            'asa_number_num' => $this->input->post('asa_number_num'),
            'asa_number_year' => $this->input->post('asa_number_year'),
            'user_id' => $this->input->post('user_id'),
        );

        switch ($action) {
            case "add":
                $this->db->trans_start();
                $this->db->insert('asa_numbers', $asa_number_data);
                $this->db->trans_complete();
                return $this->db->trans_status();
            case "edit":
                // add updated date to both data arrays
                $asa_number_data['updated_date'] = date("Y-m-d H:i:s");

                // start SQL transaction
                $this->db->trans_start();
                $this->db->update('asa_numbers', $asa_number_data, array('asa_number_id' => $id));
                $this->db->trans_complete();
                return $this->db->trans_status();
            default:
                show_404();
                break;
        }
    }

    public function remove_asa_number($id) {
        if (!($id)) {
            return false;
        } else {
            // only asa_number needed, SQL key constraints used to remove records from organizing_club
            $this->db->trans_start();
            $this->db->delete('asa_numbers', array('asa_number_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
