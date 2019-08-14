<?php

class History_model extends MY_model {

    public function __construct() {
        parent::__construct();
    }

    public function record_count() {
        return $this->db->count_all("history");
    }

    public function get_history_list($id_fieldname = NULL, $id = 0) {
        // allow for user_id or session_id
        $this->db->select("*");
        $this->db->from("history");
        $this->db->order_by('history_datevisited');
        if ($id_fieldname) {
            $this->db->where($id_fieldname, $id);
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['user_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_history_detail($history_id) {
        if (!($history_id)) {
            return false;
        } else {
            $this->db->select("*");
            $this->db->from("history");
            $this->db->where('history_id', $history_id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function check_history($session_id, $url) {
        $this->db->select("*");
        $this->db->from("history");
        $this->db->where('history_session_id', $session_id);
        $this->db->where('history_url', $url);
        $this->db->where('history_datevisited < ', date("Y-m-d 23:59:59"));
        $this->db->where('history_datevisited > ', date("Y-m-d 00:00:00"));
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return true;
        }
        return false;
    }

    public function set_history($history_data) {
        $this->db->trans_start();
        $this->db->insert('history', $history_data);
        // get event ID from Insert
        $history_id = $this->db->insert_id();
        $this->db->trans_complete();
        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return $history_id;
        } else {
            return false;
        }
    }

    public function update_history_field($history_data, $session_id) {
        $this->db->trans_start();
        $this->db->update('history', $history_data, ['history_session_id' => $session_id]);
        $this->db->trans_complete();

        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return true;
        } else {
            return false;
        }
    }

}
