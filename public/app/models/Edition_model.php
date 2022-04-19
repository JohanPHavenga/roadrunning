<?php

class Edition_model extends Frontend_model {

    public function __construct() {
        parent::__construct();
    }

    public function get_edition_id_from_slug($edition_slug) {
        // CHECK Editions table vir die naame
        $this->db->select("edition_id, edition_name, edition_status, edition_redirect_url");
        $this->db->from("editions");
        $this->db->where("edition_slug", $edition_slug);
        // $this->db->where("edition_status !=", 2); // added this to enable routing for Spar virtual challenge
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

    public function get_edition_sum($edition_id) {
        // CHECK Editions table vir die naame
        $this->db->select("edition_id, edition_name, edition_status, edition_slug");
        $this->db->from("editions");
        $this->db->where("edition_id", $edition_id);
        $editions_query = $this->db->get();

        if ($editions_query->num_rows() > 0) {
            $result = $editions_query->result_array();
            return $result[0];
        } else {
            return false;
        }
    }

    public function get_edition_list($query_params = [], $field_arr = NULL, $show_query = false) {
        if (is_null($field_arr)) {
            $field_arr = [
                "editions.edition_id", "edition_name", "edition_status", "edition_isfeatured", "edition_info_status", "edition_date", "edition_slug", "edition_address", "edition_info_prizegizing",
                "events.event_id", "event_name", "towns.town_name", "regions.region_id", "provinces.province_id", "province_abbr", "club_name", "asa_member_abbr",
                "editions.created_date", "editions.updated_date",
            ];
        }
        $select = implode(",", $field_arr);
        $this->db->select($select);
        $this->db->from("editions");
        $this->db->join('events', 'event_id');
        $this->db->join('towns', 'town_id');
        $this->db->join('regions', 'region_id');
        $this->db->join('organising_club', 'event_id');
        $this->db->join('clubs', 'club_id');
        $this->db->join('provinces', 'regions.province_id=provinces.province_id');
        $this->db->join('edition_asa_member', 'edition_id', 'left');
        $this->db->join('asa_members', 'asa_member_id', 'left');
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
        if ($show_query) {
            die($this->db->get_compiled_select());
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['edition_id']] = $this->add_more_edition_info($row);
            }
            return $data;
        }
        return false;
    }

    // SHOULD NOT USE ANYMORE
    // rather first get edition data from query above and add race info with race_model function
    public function get_edition_list_incl_races($query_params = [], $field_arr = []) {
        if (!$field_arr) {
            $field_arr = [
                "race_id", "race_distance", "race_time_start", "race_date", "racetype_id", "racetype_name", "racetype_abbr",
                "editions.edition_id", "edition_name", "edition_date", "edition_slug", "edition_address", "editions.created_date", "editions.updated_date",
                "events.event_id", "event_name", "towns.town_name", "regions.region_id", "provinces.province_id",
            ];
        }
        $select = implode(",", $field_arr);
        $this->db->select($select);
        $this->db->from("races");
        $this->db->join('racetypes', 'racetype_id');
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
//            $this->db->order_by('race_date', 'DESC');
            $this->db->order_by('race_distance', 'DESC');
            $this->db->order_by('race_time_start', 'ASC');
        }

//        die($this->db->get_compiled_select());
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                foreach ($field_arr as $field) {
                    // remove table from from field
                    if (strpos($field, '.') !== FALSE) {
                        $field = substr($field, strpos($field, '.') + 1, strlen($field));
                    }
                    $data[$row['edition_id']][$field] = $row[$field];
                }
                // add races as an array
                $data[$row['edition_id']]['races'][$row['race_id']] = $row;
                if (($row['racetype_abbr'] == "R") || ($row['racetype_abbr'] == "R/W")) {
                    $data[$row['edition_id']]['race_distance_arr'][] = fraceDistance($row['race_distance']);
                } else {
                    $data[$row['edition_id']]['race_distance_arr'][] = fraceDistance($row['race_distance']) . " " . $row['racetype_name'];
                }

                // add edition_url
                $data[$row['edition_id']]['edition_url'] = base_url("event/" . $row['edition_slug']);
            }
            // set start time of event
            foreach ($data as $edition_id => $edition) {
                foreach ($edition['races'] as $race) {
                    if (strtotime($race['race_time_start']) < strtotime($edition['race_time_start'])) {
                        $data[$edition_id]['race_time_start'] = $race['race_time_start'];
                    }
                }
                unset($data[$edition_id]['race_id']);
                unset($data[$edition_id]['race_distance']);
                unset($data[$edition_id]['race_date']);
                unset($data[$edition_id]['racetype_id']);
                unset($data[$edition_id]['racetype_name']);
                unset($data[$edition_id]['racetype_abbr']);
            }
            return $data;
        }
        return false;
    }

    public function get_edition_detail($id) {
        $this->db->select("editions.*,events.event_id, event_name, "
                . "clubs.club_id, club_name, users.user_id, user_name, user_surname, user_email, user_contact, "
                . "asa_members.asa_member_id, asa_member_name, asa_member_abbr, asa_member_url, timingprovider_name, timingprovider_url, "
                . "towns.town_id, town_name, regions.region_id, region_name, provinces.province_id, province_name");
        $this->db->from("editions");
        $this->db->join('events', 'event_id');
        $this->db->join('towns', 'town_id');
        $this->db->join('regions', 'region_id');
        $this->db->join('provinces', 'regions.province_id=provinces.province_id');
        $this->db->join('organising_club', 'event_id', 'left');
        $this->db->join('clubs', 'club_id', 'left');
        $this->db->join('edition_user', 'edition_id', 'left');
        $this->db->join('users', 'user_id', 'left');
        $this->db->join('edition_asa_member', 'edition_id', 'left');
        $this->db->join('asa_members', 'asa_member_id', 'left');
        $this->db->join('timingproviders', 'timingprovider_id', 'left');
        $this->db->where('edition_id', $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $return_arr = $query->row_array();
            // add annual name
            $return_arr['annual_name'] = $return_arr['event_name'] . " " . date("Y", strtotime($return_arr['edition_date']));
            return $return_arr;
        } else {
            return false;
        }
    }

    private function add_more_edition_info($edition) {
        // ADD MORE INFO TO EDITION FOR LISTS
        // FUNCTIONS moved to the core controller
        $edition['edition_url'] = base_url("event/" . $edition['edition_slug']);
        // add img url
        $edition['img_url'] = $this->get_edition_img_url($edition['edition_id'], $edition['edition_slug']);
        // add entrytype list
        $edition['entrytype_list'] = $this->get_edition_entrytype_list($edition['edition_id']);

        return $edition;
    }

    public function update_field($e_id, $field, $value) {
        if (!($e_id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->update('editions', [$field => $value, "updated_date" => date("Y-m-d H:i:s")], array('edition_id' => $e_id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function get_edition_id_from_name($edition_name) {
        // CHECK Editions table vir die naame
        $this->db->select("edition_id, edition_name, edition_status");
        $this->db->from("editions");
        $this->db->where("edition_name", $edition_name);
//            $this->db->where("REPLACE(edition_name, '\'', '')='$edition_name'"); // fix vir as daar 'n ' in die naam is
//            $this->db->or_where("REPLACE(edition_name, '/', ' ')=$edition_name"); // fix vir as daar 'n / in die naam is
//            echo $this->db->get_compiled_select(); exit();
        $editions_query = $this->db->get();


        // CHECK Editions_Past vir as die naam van die edition verander
        $this->db->select("edition_id");
        $this->db->from("editions_past");
        $this->db->where("edition_name", $edition_name);
//            $this->db->where("REPLACE(edition_name, '\'', '')=$edition_name"); // fix vir as daar 'n ' in die naam is
//            $this->db->or_where("REPLACE(edition_name, '/', ' ')='$edition_name'"); // fix vir as daar 'n / in die naam is
//            echo $this->db->get_compiled_select();   exit();

        $editions_past_query = $this->db->get();


        if ($editions_query->num_rows() > 0) {
            $result = $editions_query->result_array();
            return $result[0];
        } elseif ($editions_past_query->num_rows() > 0) {
            $result = $editions_past_query->result_array();
            $result[0]['edition_name'] = $this->get_edition_name_from_id($result[0]['edition_id']);
            return $result[0];
        } else {
            return false;
        }
    }

    public function get_edition_name_from_id($edition_id) {
        // CHECK Editions table vir die naame
        $this->db->select("edition_name");
        $this->db->from("editions");
        $this->db->where("edition_id", $edition_id);
        $editions_query = $this->db->get();
        if ($editions_query->num_rows() > 0) {
            $result = $editions_query->result_array();
            return $result[0]['edition_name'];
        } else {
            return false;
        }
    }

    public function get_edition_sponsor_list($edition_id = null) {
        if (!$edition_id) {
            return false;
        }
        // had to go manual for advanced querys
        $query = $this->db->query("SELECT `sponsor_id`, `sponsor_name`, `url_name` FROM `edition_sponsor` JOIN `sponsors` USING (`sponsor_id`) LEFT JOIN `urls` ON `sponsors`.`sponsor_id`=`linked_id` AND `urltype_id` = 1 AND `url_linked_to` = 'sponsor' WHERE `edition_id` = '$edition_id'");

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['sponsor_id']] = $row;
            }
            return $data;
        } else {
            return [];
        }
    }

    public function edition_count($province_id, $region_id = false, $show_query = false) {

        $this->db->from('editions');
        $this->db->join('events', 'event_id');
        $this->db->join('towns', 'town_id');
        $this->db->join('regions', 'region_id');
        $this->db->join('provinces', 'regions.province_id=provinces.province_id');
        $this->db->where("edition_date >= ", date("Y-m-d H:i:s"));
//        $this->db->where("edition_status", 1);
        $this->db->where("provinces.province_id", $province_id);
        if ($region_id) {
            $this->db->where("regions.region_id", $region_id);
        }

        if ($show_query) {
            die($this->db->get_compiled_select());
        }
        return $this->db->count_all_results(); 
    }

    public function get_edition_list_search($edition_id=NULL) {
       
        $field_arr = [
            "edition_id", "edition_name", "edition_slug", "edition_date", "edition_isfeatured","edition_status", "edition_info_status",
            "event_name", "town_name", "town_name_alt", "region_id","region_name", "regions.province_id", "province_name", "province_abbr"
        ];
        $select = implode(",", $field_arr);
        $this->db->select($select);
        $this->db->from("editions");  
        $this->db->join('events', 'event_id');
        $this->db->join('towns', 'town_id');
        $this->db->join('regions', 'region_id');
        $this->db->join('provinces', 'regions.province_id=provinces.province_id');
        if ($edition_id) {
            $this->db->where('edition_id', $edition_id);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['edition_id']]=$row;                
            }
            return $data;
        }
        return false;
    }

}
