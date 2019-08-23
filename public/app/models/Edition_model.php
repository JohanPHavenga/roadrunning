<?php

class Edition_model extends MY_model {

    public function __construct() {
        parent::__construct();
    }

    public function get_edition_id_from_slug($edition_slug) {
        // CHECK Editions table vir die naame
        $this->db->select("edition_id, edition_name, edition_status");
        $this->db->from("editions");
        $this->db->where("edition_slug", $edition_slug);
        $editions_query = $this->db->get();

        // CHECK Editions_Past vir as die naam van die edition verander
        $this->db->select("edition_id, edition_name");
        $this->db->from("editions_past");
        $this->db->where("edition_slug", $edition_slug);
        $editions_past_query = $this->db->get();

        if ($editions_query->num_rows() > 0) {
            $result = $editions_query->result_array();
            $result[0]['source'] = "org";
            return $result[0];
        } elseif ($editions_past_query->num_rows() > 0) {
            $result = $editions_past_query->result_array();
            $result[0]['source'] = "past";
            return $result[0];
        } else {
            return false;
        }
    }

    public function get_edition_slug($edition_id) {
        // CHECK Editions table vir die naame
        $this->db->select("edition_slug");
        $this->db->from("editions");
        $this->db->where("edition_id", $edition_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result[0]['edition_slug'];
        } else {
            return false;
        }
    }

    public function get_edition_list($query_params = [], $field_arr = NULL) {
        if (is_null($field_arr)) {
            $field_arr = [
                "edition_id", "edition_name", "edition_date", "edition_slug", "editions.created_date", "editions.updated_date",
                "events.event_id", "event_name", "regions.region_id", "provinces.province_id",
            ];
        }
        $select = implode(",", $field_arr);
        $this->db->select($select);
        $this->db->from("editions");
        $this->db->join('events', 'event_id');
        $this->db->join('towns', 'town_id');
        $this->db->join('regions', 'region_id');
        $this->db->join('provinces', 'regions.province_id=provinces.province_id');
        foreach ($query_params as $operator => $clause_arr) {
            if (is_array($clause_arr)) {
                foreach ($clause_arr as $field => $value) {
                    $this->db->$operator($field, $value);
                }
            } else {
                $this->db->$operator($clause_arr);
            }
        }
        if (!isset($query_params['order_by'])) {
            $this->db->order_by('edition_date', 'ASC');
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

    public function get_edition_list_incl_races($query_params = [], $field_arr = []) {
        if (!$field_arr) {
            $field_arr = [
                "race_id", "race_distance", "race_time_start", "racetype_id", "race_date",
                "editions.edition_id", "edition_name", "edition_date", "edition_slug", "editions.created_date", "editions.updated_date",
                "events.event_id", "event_name", "regions.region_id", "provinces.province_id",
            ];
        }
        $select = implode(",", $field_arr);
        $this->db->select($select);
        $this->db->from("races");
        $this->db->join('editions', 'edition_id');
        $this->db->join('events', 'event_id');
        $this->db->join('towns', 'town_id');
        $this->db->join('regions', 'region_id');
        $this->db->join('provinces', 'regions.province_id=provinces.province_id');
        foreach ($query_params as $operator => $clause_arr) {
            if (is_array($clause_arr)) {
                foreach ($clause_arr as $field => $value) {
                    $this->db->$operator($field, $value);
                }
            } else {
                $this->db->$operator($clause_arr);
            }
        }
        if (!isset($query_params['order_by'])) {
            $this->db->order_by('edition_date', 'ASC');
            $this->db->order_by('race_date', 'DESC');
            $this->db->order_by('race_time_start', 'DESC');
            $this->db->order_by('race_distance', 'ASC');
        }

//        die($this->db->get_compiled_select());
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                foreach ($field_arr as $field) {
                    // remove any field with the word "race" in it to simplify the array
//                    if (strpos($field,'race')!==FALSE) { continue; } 
                    // remove table from from field
                    if (strpos($field, '.') !== FALSE) {
                        $field = substr($field, strpos($field, '.') + 1, strlen($field));
                    }
                    $data[$row['edition_id']][$field] = $row[$field];
                }
                // add races as an array
                $data[$row['edition_id']]['races'][$row['race_id']] = $row;
            }
            return $data;
        }
        return false;
    }

}
