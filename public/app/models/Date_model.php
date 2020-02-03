<?php

class Date_model extends Frontend_model {

    public function __construct() {
        parent::__construct();
    }

    public function record_count() {
        return $this->db->count_all("dates");
    }

    public function exists($linked_to, $linked_id, $datetype_id) {
        $this->db->select("date_id");
        $this->db->from("dates");
        $this->db->where('date_linked_to', $linked_to);
        $this->db->where('linked_id', $linked_id);
        $this->db->where('datetype_id', $datetype_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result[0]['date_id'];
        }
        return false;
    }

    public function get_date_list($linked_to = NULL, $linked_id = 0, $by_date_type_linked_id = false, $by_date__only = false) {
        $this->db->select("dates.*, datetypes.*, venue_name");
        $this->db->join("datetypes", "datetype_id");
        $this->db->join("venues", "venue_id", "left");
        $this->db->from("dates");
        $this->db->order_by("date_start");
        if ($linked_to) {
            $this->db->where('date_linked_to', $linked_to);
        }
        if ($linked_id > 0) {
            $this->db->where('linked_id', $linked_id);
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                if ($by_date_type_linked_id) {
                    $date_list[$row['datetype_id']][$row["linked_id"]] = $row;
                } elseif ($by_date__only) {
                    $date_list[$row['datetype_id']][] = $row;
                } else {
                    $date_list[$row['date_id']] = $row;
                }
            }
            return $date_list;
        }
        return false;
    }

    public function get_date_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("dates.*");
            $this->db->from("dates");
            $this->db->where('date_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }
    public function check_datetype_exists($linked_to, $id, $datetype_id) {
        $this->db->select("date_id");
        $this->db->from("dates");
        $this->db->where('date_linked_to', $linked_to);
        $this->db->where('linked_id', $id);
        $this->db->where('datetype_id', $datetype_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

}
