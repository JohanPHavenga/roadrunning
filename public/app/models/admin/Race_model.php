<?php

class Race_model extends Admin_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("races");
    }

    public function get_race_field_array() {
        $fields = $this->db->list_fields('races');
        foreach ($fields as $field) {
            $data[$field] = "";
        }
        return $data;
    }

    public function get_race_list($edition_id = 0, $status = null) {

        $this->db->select("races.*, edition_name, edition_date, racetype_name, racetype_abbr");
        $this->db->from("races");
        $this->db->join('editions', 'editions.edition_id=races.edition_id', 'left');
        $this->db->join('racetypes', 'racetypes.racetype_id=races.racetype_id', 'left');
        if ($edition_id > 0) {
            $this->db->where('races.edition_id', $edition_id);
        }
        if ($status) {
            $this->db->where('races.race_status', $status);
        }
//            $this->db->where('races.race_status', 1);
        $this->db->order_by('races.race_distance', 'DESC');
        $this->db->order_by('racetype_name', 'ASC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['race_id']] = $row;
                $data[$row['race_id']]['race_color'] = $this->get_race_color($row['race_distance']);
            }
            return $data;
        }
        return false;
    }

    public function get_race_dropdown($limit_results = true) {
        $this->db->select("race_id, race_name, race_distance, edition_name, event_name, racetype_abbr, edition_date");
        $this->db->from("races");
        $this->db->join('editions', 'editions.edition_id=races.edition_id', 'left');
        $this->db->join('events', 'editions.event_id=events.event_id', 'left');
        $this->db->join('racetypes', 'racetypes.racetype_id=races.racetype_id', 'left');
        // limit the list a little
        if ($limit_results) {
            $this->db->where("edition_date > ", date("Y-m-d", strtotime("3 months ago")));
            $this->db->where("edition_date < ", date("Y-m-d", strtotime("+9 month")));
        }
        $this->db->order_by('event_name');
        $this->db->order_by('edition_date', "DESC");
        $this->db->order_by('race_distance', "DESC");
//        echo $this->db->get_compiled_select();
//        die();
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $distance = str_pad(round($row['race_distance'], 0), 2, '0', STR_PAD_LEFT);
                $year = date('Y', strtotime($row['edition_date']));
                $data[$row['race_id']] = $row['event_name'] . " | " . $year . " | " . $distance . " km | " . $row['racetype_abbr'];
            }
            return $data;
        }
        return false;
    }

    public function get_race_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("races.*, racetype_abbr, edition_name, edition_date, event_name, asa_member_id");
            $this->db->from("races");
            $this->db->join('racetypes', 'racetype_id');
            $this->db->join('editions', 'edition_id');
            $this->db->join('events', 'event_id');
            $this->db->join('edition_asa_member', 'edition_id', 'left');
            $this->db->where('race_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_race($action, $race_id, $race_data = [], $debug = false) {


        if (!isset($race_data['race_status'])) {
            $race_data['race_status'] = 1;
        }

        if ($debug) {
            echo "<b>Race Transaction</b>";
            wts($action);
            // wts($race_id);
            wts($race_data);
            exit();
        } else {
            switch ($action) {
                case "add":
                    $this->db->trans_start();
                    $this->db->insert('races', $race_data);
                    // get edition ID from Insert
                    $race_id = $this->db->insert_id();
                    $this->db->trans_complete();
                    break;
                case "edit":
                    // add updated date to both data arrays
                    $race_data['updated_date'] = date("Y-m-d H:i:s");

                    // start SQL transaction
                    $this->db->trans_start();
                    $this->db->update('races', $race_data, array('race_id' => $race_id));
                    $this->db->trans_complete();
                    break;
                default:
                    show_404();
                    break;
            }

            // return ID if transaction successfull
            if ($this->db->trans_status()) {
                return $race_id;
            } else {
                return false;
            }
        }
    }

    public function remove_race($id) {
        if (!($id)) {
            return false;
        } else {
            // only race needed, SQL key constraints used to remove records from organizing_club
            $this->db->trans_start();
            $this->db->delete('races', array('race_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

//    function get_race_color($distance) {
//
//        switch (true) {
//            case $distance <= 9:
//                $color = 'yellow';
//                break;
//
//            case $distance == 10:
//                $color = 'yellow-1';
//                break;
//
//            case $distance < 21:
//                $color = 'green-2';
//                break;
//
//            case $distance == 21:
//                $color = 'blue';
//                break;
//
//            case $distance < 42:
//                $color = 'purple';
//                break;
//
//            case $distance == 42:
//                $color = 'red-2';
//                break;
//
//            default:
//                $color = 'red-3';
//                break;
//        }
//
//        return $color;
//    }

    public function get_next_prev_race_list($race_list, $direction) {

        foreach ($race_list as $race_id => $race) {
            $dist = $race['race_distance'];
            $date = $race['edition_date'];
            $type = $race['racetype_id'];

            if ($direction == "next") {
                $this->db->where('edition_date >= ', $date);
                $order = "ASC";
            } elseif ($direction == "prev") {
                $this->db->where('edition_date <=', $date);
                $order = "DESC";
            }

            $this->db->select("race_id, race_distance, edition_name");
            $this->db->from("races");
            $this->db->join('editions', 'editions.edition_id=races.edition_id', 'left');
            $this->db->where('race_distance', $dist, false);
            $this->db->where('edition_status', true);
            $this->db->where('race_id !=', $race_id, false);
            $this->db->where('racetype_id', $type, false);
            $this->db->order_by('edition_date', $order);
            $this->db->limit(1);
//                echo $this->db->get_compiled_select();
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $return[$race_id] = $row;
                    $return[$race_id]['url'] = get_url_from_edition_name(encode_edition_name($row['edition_name']));
                }
            } else {
                $return[$race_id] = false;
            }
        }
        return $return;
    }

    public function get_previous_race_list($race_list) {

        foreach ($race_list as $race_id => $race) {
            $dist = $race['race_distance'];
            $date = $race['edition_date'];

            $this->db->select("race_id, race_distance, edition_name");
            $this->db->from("races");
            $this->db->join('editions', 'editions.edition_id=races.edition_id', 'left');
            $this->db->where('edition_date <= ', $date);
            $this->db->where('race_distance', $dist, false);
            $this->db->where('edition_status', true);
            $this->db->where('race_id !=', $race_id, false);
            $this->db->order_by('edition_date');
            $this->db->limit(1);
//                echo $this->db->get_compiled_select();
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $return[$race_id] = $row;
                    $return[$race_id]['url'] = get_url_from_edition_name(encode_edition_name($row['edition_name']));
                }
            } else {
                $return[$race_id] = false;
            }
        }
        return $return;
    }

    public function get_edition_id($race_id) {
        if (!($race_id)) {
            return false;
        } else {
            $this->db->select("edition_id");
            $this->db->from("races");
            $this->db->where('race_id', $race_id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $return = $row['edition_id'];
                }
                return $return;
            } else {
                return false;
            }
        }
    }

    public function update_race_status($race_id_arr, $status_id) {
        if (!empty($race_id_arr)) {
            foreach ($race_id_arr as $race_id) {
                $race_data = array(
                    'race_status' => $status_id,
                    'updated_date' => date("Y-m-d H:i:s"),
                );
                // start SQL transaction
                $this->db->trans_start();
                $this->db->update('races', $race_data, array('race_id' => $race_id));
                $this->db->trans_complete();
            }
            return $this->db->trans_status();
        } else {
            return false;
        }
    }

    public function update_field($r_id, $field, $value) {
        if (!($r_id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->update('races', [$field => $value], array('race_id' => $r_id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function get_race_list_with_results($limit_results = true) {
        $this->db->distinct();
        $this->db->select("races.race_id, race_name, race_distance, edition_name, edition_date, event_name, racetype_abbr");
        $this->db->from("races");
        $this->db->join('results', 'results.race_id = races.race_id', 'inner');
        $this->db->join('editions', 'editions.edition_id=races.edition_id', 'left');
        $this->db->join('events', 'editions.event_id=events.event_id', 'left');
        $this->db->join('racetypes', 'racetypes.racetype_id=races.racetype_id', 'left');
        // limit the list a little
        if ($limit_results) {
            $this->db->where("edition_date > ", date("Y-m-d", strtotime("3 months ago")));
            $this->db->where("edition_date < ", date("Y-m-d", strtotime("+9 month")));
        }
        $this->db->order_by('edition_date', "DESC");
        $this->db->order_by('race_distance', "DESC");
//        echo $this->db->get_compiled_select();
//        die();
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $distance = round($row['race_distance'], 0);
                $year = date('Y', strtotime($row['edition_date']));
                $data[$row['race_id']] = $row['event_name'] . " | " . $year . " | " . $distance . " km | " . $row['racetype_abbr'];
            }
            return $data;
        }
        return false;
    }

    public function get_race_list_with_no_results($how_far_back) {
        $this->db->distinct();
        $this->db->select("races.race_id, race_name, race_distance, results.file_id, editions.edition_id, edition_name, edition_date, edition_slug, event_name, racetype_name, racetype_abbr, asa_member_abbr, timingprovider_abbr");
        $this->db->from("races");
        $this->db->join('results', 'results.race_id = races.race_id', 'left');
        $this->db->join('editions', 'editions.edition_id=races.edition_id', 'left');
        $this->db->join('events', 'editions.event_id=events.event_id', 'left');
        $this->db->join('edition_asa_member', 'editions.edition_id=edition_asa_member.edition_id', 'left');
        $this->db->join('asa_members', 'asa_member_id', 'left');
        $this->db->join('timingproviders', 'timingprovider_id');
        $this->db->join('racetypes', 'racetypes.racetype_id=races.racetype_id', 'left');
        // limit the list a little        
        $this->db->where("edition_date > ", date("Y-m-d", strtotime("-".$how_far_back)));
        // from today
        $this->db->where("edition_date < ", date("Y-m-d 23:59:59"));
        $this->db->where("editions.edition_status", 1);
        $this->db->where("races.race_distance >= ", 10);
        $this->db->group_start();
            $this->db->where("editions.edition_info_status", 10);
            $this->db->or_group_start();
                $this->db->where("editions.edition_info_status", 11);
                $this->db->where("results.file_id", NULL);
            $this->db->group_end();
        $this->db->group_end();
        $this->db->order_by('edition_date', "DESC");
        $this->db->order_by('race_distance', "DESC");
//        echo $this->db->get_compiled_select();
//        die();
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $distance = round($row['race_distance'], 0);
                $year = date('Y', strtotime($row['edition_date']));
                $data[$row['race_id']] = $row;
                $data[$row['race_id']]['color'] = $this->get_race_color($row['race_distance']);
                $data[$row['race_id']]['summary'] = $row['event_name'] . " | " . $year . " | " . $distance . " km | " . $row['racetype_abbr'];
            }
            return $data;
        }
        return false;
    }

}
