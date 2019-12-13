<?php

class File_model extends MY_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("files");
    }

    public function exists($linked_to, $linked_id, $filetype_id) {

        $this->db->select("file_id");
        $this->db->from("files");
        $this->db->where('file_linked_to', $linked_to);
        $this->db->where('linked_id', $linked_id);
        $this->db->where('filetype_id', $filetype_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result[0]['file_id'];
        }
        return false;
    }

    public function get_file_list($file_linked_to = NULL, $linked_id = 0, $by_filetype = false) {

        $this->db->select("files.*,filetypes.*");
        $this->db->from("files");
        $this->db->join('filetypes', 'filetype_id');
        if ($file_linked_to) {
            $this->db->where("file_linked_to", $file_linked_to);
            $this->db->where("linked_id", $linked_id);
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                if ($by_filetype) {
                    $file_list[$row['filetype_id']][] = $row;
                } else {
                    $file_list[] = $row;
                }
            }
            return $file_list;
        }
        return false;
    }

    public function get_file_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("*");
            $this->db->from("files");
            $this->db->join('filetypes', 'filetype_id');
            $this->db->where('file_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function get_filetype_list() {
        $this->db->select("filetypes.*");
        $this->db->from("filetypes");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[strtolower(str_replace(" ", "_", $row['filetype_name']))] = $row['filetype_id'];
            }
            return $data;
        }
        return false;
    }

}
