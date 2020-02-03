<?php

class Sponsor_model extends Admin_model {

    public $table = "sponsors";
    public $no_info_id = 4;

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all($this->table);
    }

    public function get_sponsor_list() {
        $this->db->select("sponsors.*");
        $this->db->from($this->table);
        $this->db->where("sponsor_status",1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['sponsor_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_sponsor_dropdown() {
        $this->db->select("sponsor_id, sponsor_name");
        $this->db->from($this->table);
        $this->db->order_by("sponsor_name");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
//            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['sponsor_id']] = $row['sponsor_name'];
            }
            move_to_top($data, $this->no_info_id);
            return $data;
        }
        return false;
    }

    public function get_sponsor_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where($this->table, array('sponsor_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_sponsor($action, $sponsor_id) {
        $data = array(
            'sponsor_name' => $this->input->post('sponsor_name'),
            'sponsor_status' => $this->input->post('sponsor_status'),
        );

        switch ($action) {
            case "add":
                $this->db->trans_start();
                $this->db->insert($this->table, $data);
                $sponsor_id = $this->db->insert_id();
                $this->db->trans_complete();
                break;
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");

                // start SQL transaction
                $this->db->trans_start();
                $this->db->update($this->table, $data, array('sponsor_id' => $sponsor_id));
                $this->db->trans_complete();
                break;
            default:
                show_404();
                break;
        }

        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return $sponsor_id;
        } else {
            return false;
        }
    }

    public function remove_sponsor($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete($this->table, array('sponsor_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function get_edition_sponsor_list($edition_id = null) {
        if (!$edition_id) {
            return false;
        }
        $query = $this->db->get_where('edition_sponsor', array('edition_id' => $edition_id));
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['sponsor_id']] = $row['sponsor_id'];
            }
        } else {
            $data = [$this->no_info_id];
        }
        return $data;
    }

    public function set_edition_sponsor($edition_id, $sponsor_id) {
        if (!$edition_id) {
            return false;
        }
        $this->db->trans_start();
        $this->db->delete('edition_sponsor', array('edition_id' => $edition_id));
        $insert_array = [
            "edition_id" => $edition_id,
            "sponsor_id" => $sponsor_id,
        ];
        $id = $this->db->insert('edition_sponsor', $insert_array);
        $this->db->trans_complete();

        return $id;
    }

}
