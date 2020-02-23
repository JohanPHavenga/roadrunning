<?php

class Club_model extends Admin_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("clubs");
    }

    public function get_club_id($club_name, $create = []) {
        $this->db->select("club_id");
        $this->db->from("clubs");
        $this->db->where('LOWER(club_name)', strtolower($club_name));
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data = $row['club_id'];
            }
            return $data;
        } elseif (!empty($create)) {
            return($this->set_club("add",0,$create));
        }
        return false;
    }

    public function get_club_list() {

        $this->db->select("clubs.*, town_name, province_name, sponsor_name");
        $this->db->from("clubs");
        $this->db->join('towns', 'town_id', 'left');
        $this->db->join('provinces', 'province_id', 'left');
        $this->db->join('club_sponsor', 'club_id', 'left');
        $this->db->join('sponsors', 'sponsor_id', 'left');
        $this->db->order_by('club_name');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['club_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_club_dropdown() {
        $this->db->select("club_id, club_name");
        $this->db->from("clubs");
        $this->db->order_by('club_name');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['club_id']] = $row['club_name'];
            }
//                return array_slice($data, 0, 500, true);
            return $data;
        }
        return false;
    }

    public function get_club_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("clubs.*, town_name, sponsor_id");
            $this->db->from("clubs");
            $this->db->join('towns', 'town_id', 'left');
            $this->db->join('club_sponsor', 'club_id', 'left');
            $this->db->where('club_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_club($action, $club_id, $club_data=[]) {
        if (empty($club_data)) {
            $club_data = array(
                'club_name' => $this->input->post('club_name'),
                'club_status' => $this->input->post('club_status'),
                'town_id' => $this->input->post('town_id'),
            );
            $club_sponsor_data = ["club_id" => $club_id, "sponsor_id" => $this->input->post('sponsor_id')];
        } else {
            $club_sponsor_data = ["club_id" => $club_id, "sponsor_id" => 4];
        }

        switch ($action) {
            case "add":
                $this->db->trans_start();
                $this->db->insert('clubs', $club_data);
                // get edition ID from Insert
                $club_id = $this->db->insert_id();
                // update data array
                $club_sponsor_data["club_id"] = $club_id;
                $this->db->insert('club_sponsor', $club_sponsor_data);
                $this->db->trans_complete();
                break;
            case "edit":
                // add updated date to both data arrays
                $club_data['updated_date'] = date("Y-m-d H:i:s");

                // start SQL transaction
                $this->db->trans_start();
                // chcek if record already exists
                $item_exists = $this->db->get_where('club_sponsor', array('club_id' => $club_id, 'sponsor_id' => $this->input->post('sponsor_id')));
                if ($item_exists->num_rows() == 0) {
                    $club_data['updated_date'] = date("Y-m-d H:i:s");
                    $this->db->delete('club_sponsor', array('club_id' => $club_id));
                    $this->db->insert('club_sponsor', $club_sponsor_data);
                }
                $this->db->update('clubs', $club_data, array('club_id' => $club_id));
                $this->db->trans_complete();
                break;
            default:
                show_404();
                break;
        }
        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return $club_id;
        } else {
            return false;
        }
    }

    public function remove_club($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('clubs', array('club_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
