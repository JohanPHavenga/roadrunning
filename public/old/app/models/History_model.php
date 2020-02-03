<?php

class History_model extends MY_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
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

    public function get_most_visited_url_list($from_date = NULL) {
        // ONLY PULLS EDITIONS
        if (is_null($from_date)) {
            $from_date = date("Y-m-d H:i:s", strtotime("-1 month"));
        }
        $this->db->select("count(history_url) AS url_count, history_url, MAX(history_datevisited) as lastvisited");
        $this->db->from("history");
        $this->db->like('history_url', '/event/');
        $this->db->where('history_datevisited > ', $from_date);
        $this->db->group_by("history_url");
        $this->db->order_by("url_count", "DESC");
        $this->db->order_by("history_datevisited", "DESC");

//        die($this->db->get_compiled_select());
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function set_history_summary($edition_data, $url_count_data) {
        $this->db->trans_start();
        
        $this->db->empty_table('historysum');
        foreach ($edition_data as $edition_id => $edition) {
            $historysum_data = [
                "edition_id" => $edition_id,
                "edition_name" => $edition['edition_name'],
                "edition_url" => $url_count_data[$edition_id]['url'],
                "edition_date" => $edition['edition_date'],
                "history_lastvisited" => $url_count_data[$edition_id]['lastvisited'],
                "historysum_countyear" => $url_count_data[$edition_id]['count'],
                "region_id" => $edition['region_id'],
                "province_id" => $edition['province_id'],
            ];
            $this->db->insert('historysum', $historysum_data);
        }
        $this->db->trans_complete();

        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function update_history_counts($url_count_data,$field) {
        $this->db->trans_start();
        foreach ($url_count_data as $edition_id => $count) {
            $history_data=[$field => $count];
            $this->db->update('historysum', $history_data, ['edition_id' => $edition_id]);
        }        
        $this->db->trans_complete();

        // return true if transaction successfull
        if ($this->db->trans_status()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function get_history_summary($query_params=[]) {

        $this->db->select("*");
        $this->db->from("historysum");
        foreach ($query_params as $operator=>$clause_arr) {
            if (is_array($clause_arr)) {
                foreach ($clause_arr as $field=>$value) {
                    $this->db->$operator($field, $value);
                }
            } else {
                $this->db->$operator($clause_arr);
            }
        }
//        die($this->db->get_compiled_select());
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['edition_id']] = $row;
            }
            return $data;
        }
        return false;
    }
    

}
