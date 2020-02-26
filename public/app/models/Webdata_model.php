<?php

class Webdata_model extends Frontend_model {

    public $table = "webdatas";

    public function __construct() {
        parent::__construct();
    }

    public function record_count() {
        return $this->db->count_all($this->table);
    }

    public function get_webdata($webdata_group = null) {
        // allow for user_id or session_id
        $this->db->select("*");
        $this->db->from($this->table);
        if ($webdata_group) {
            $this->db->where("webdata_group",$webdata_group);
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['webdata_group']][$row['webdata_name']] = $row['webdata_value'];
            }
            return $data;
        }
        return false;
    }

}
