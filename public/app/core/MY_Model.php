<?php

class MY_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // new way to pull status list using the status_
    public function get_status_name($status_id) {
        $this->db->select("status_name");
        $this->db->from("status");
        $this->db->where('status_id', $status_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data = $row['status_name'];
            }
            return $data;
        }
        return false;
    }

    public function get_edition_img_url($edition_id, $slug) {
        $file_detail = $this->file_exists("edition", $edition_id, 1);
        if ($file_detail) {
            return base_url("file/edition/" . $slug . "/logo/" . $file_detail['file_name']);
        } else {
            return false;
        }
    }

    public function file_exists($linked_to, $linked_id, $filetype_id) {

        $this->db->select("*");
        $this->db->from("files");
        $this->db->where('file_linked_to', $linked_to);
        $this->db->where('linked_id', $linked_id);
        $this->db->where('filetype_id', $filetype_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result[0];
        }
        return false;
    }
    
    public function get_edition_entrytype_list($edition_id = null) {
        if (!$edition_id) {
            return false;
        }
        $query = $this->db->get_where('edition_entrytype', array('edition_id' => $edition_id));
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['entrytype_id']] = $row['entrytype_id'];
            }
        } else {
            $data = [$this->no_info_id];
        }
        return $data;
    }

}
