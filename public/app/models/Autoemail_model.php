<?php

class Autoemail_model extends Frontend_model {

    public function __construct() {
        parent::__construct();
    }

    public function record_count() {
        return $this->db->count_all("autoemails");
    }

    public function exists($emailtemplate_id, $edition_id) {
        $this->db->select("autoemail_id");
        $this->db->from("autoemails");
        $this->db->where('emailtemplate_id', $emailtemplate_id);
        $this->db->where('edition_id', $edition_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result[0]['autoemail_id'];
        }
        return false;
    }
    
    public function set_autoemail($emailtemplate_id, $edition_id) {
        $data=[
            "emailtemplate_id"=>$emailtemplate_id,
            "edition_id"=>$edition_id,
        ];
        $this->db->trans_start();
        $this->db->insert('autoemails', $data);
        // get edition ID from Insert
        $autoemails_id = $this->db->insert_id();
        $this->db->trans_complete();
              
        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return $autoemails_id;
        } else {
            return false;
        }
    }

}
