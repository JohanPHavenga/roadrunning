<?php

class Url_model extends Frontend_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("urls");
    }

    public function exists($linked_to, $linked_id, $urltype_id) {
        
        $this->db->select("url_id");
        $this->db->from("urls");
        $this->db->where('url_linked_to', $linked_to);
        $this->db->where('linked_id', $linked_id);
        $this->db->where('urltype_id', $urltype_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result[0]['url_id'];
        }
        return false;
    }

    public function get_url_list($linked_to = NULL, $linked_id = 0, $by_urltype = false) {

        $this->db->select("urls.*, urltype_name");
        $this->db->join("urltypes", "urltype_id");
        $this->db->from("urls");
        if ($linked_id > 0) {
            $this->db->where('url_linked_to', $linked_to);
            $this->db->where('linked_id', $linked_id);
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                if ($by_urltype) {
                    $url_list[$row['urltype_id']][] = $row;
                } else {
                    $url_list[] = $row;
                }
            }
            return $url_list;
        }
        return false;
    }

    public function get_url_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("urls.*");
            $this->db->from("urls");
            $this->db->where('url_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

}
