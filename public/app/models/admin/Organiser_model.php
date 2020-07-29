<?php

// this is used by the email merge funcitonality

class Organiser_model extends Admin_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_organiser_dropdown() {

        $this->db->select("newsletter_id, newsletter_name");
        $this->db->from("newsletters");
        $this->db->order_by("newsletter_name");
        $query = $this->db->get();


        if ($query->num_rows() > 0) {
            $data["all"] = "All";
            $data["date_range"] = "Date Range";
            return $data;
        }
        return false;
    }

}
