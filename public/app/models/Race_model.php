<?php

class Race_model extends Frontend_model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function record_count()
    {
        return $this->db->count_all("races");
    }

    public function get_race_list($query_params = [], $field_arr = NULL, $show_query = false)
    {

        if (is_null($field_arr)) {
            $field_arr = [
                "races.*, edition_name, edition_date, racetype_name, racetype_abbr, racetype_icon"
            ];
        }
        $select = implode(",", $field_arr);
        $this->db->from("races");
        $this->db->join('editions', 'editions.edition_id=races.edition_id', 'left');
        $this->db->join('racetypes', 'racetypes.racetype_id=races.racetype_id', 'left');
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
            $this->db->order_by('races.race_distance', 'DESC');
            $this->db->order_by('racetype_name', 'ASC');
        }
        if ($show_query) {
            die($this->db->get_compiled_select());
        }
        $query = $this->db->get();


        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['race_id']] = $row;
                $data[$row['race_id']]['race_color'] = $this->get_race_color($row['race_distance']);
                if (strtotime($row['race_date']) <= 0) {
                    $data[$row['race_id']]['race_date'] = $row['edition_date'];
                }
            }
            return $data;
        }
        return false;
    }

    public function get_race_dropdown()
    {
        $this->db->select("race_id, race_name, race_distance, edition_name, racetype_abbr");
        $this->db->from("races");
        $this->db->join('editions', 'editions.edition_id=races.edition_id', 'left');
        $this->db->join('racetypes', 'racetypes.racetype_id=races.racetype_id', 'left');
        // limit the list a little
        $this->db->where("edition_date > ", date("Y-m-d", strtotime("3 months ago")));
        $this->db->where("edition_date < ", date("Y-m-d", strtotime("+9 month")));
        $this->db->order_by('edition_name');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $distance = str_pad(round($row['race_distance'], 0), 2, '0', STR_PAD_LEFT);
                $data[$row['race_id']] = $row['edition_name'] . " | <b>" . $distance . "</b> | " . $row['racetype_abbr'];
            }
            return $data;
        }
        return false;
    }

    public function get_race_detail($id)
    {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("races.*, edition_name");
            $this->db->from("races");
            $this->db->join('editions', 'editions.edition_id=races.edition_id', 'left');
            $this->db->where('race_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function remove_race($id)
    {
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

    public function get_edition_id($race_id)
    {
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

    public function add_race_info($edition_arr, $race_search_params = [])
    {
        if ($edition_arr) {
            // ADD RACE INFORMATION TO THE EDITION
            $return_arr = [];
            foreach ($edition_arr as $edition_id => $edition) {
                $race_search_params['where']['races.edition_id'] = $edition_id;
                $race_search_params['where']['races.race_status'] = 1;
                $race_list = $this->get_race_list($race_search_params);
                if ($race_list) {
                    $return_arr[$edition_id] = $edition;
                    $race_time_start = "31 December 2999";

                    foreach ($race_list as $race) {
                        if (strtotime($race['race_time_start']) < strtotime($race_time_start)) {
                            $race_time_start = $race['race_time_start'];
                        }
                        if (($race['racetype_abbr'] == "R") || ($race['racetype_abbr'] == "R/W")) {
                            $return_arr[$edition_id]['race_distance_arr'][] = fraceDistance($race['race_distance']);
                        } else {
                            $return_arr[$edition_id]['race_distance_arr'][] = fraceDistance($race['race_distance']) . " " . $race['racetype_name'];
                        }
                    }

                    $return_arr[$edition_id]['race_time_start'] = $race_time_start;
                    $return_arr[$edition_id]['race_list'] = $race_list;
                }
            }
            return $return_arr;
        } else {
            return false;
        }
    }

    public function get_race_list_with_results($query = NULL, $limit_results = false)
    {
        $this->db->distinct();
        $this->db->select("races.race_id, race_name, race_distance, edition_name, edition_date, event_name, racetype_abbr, racetype_name");
        $this->db->from("races");
        $this->db->join('results', 'results.race_id = races.race_id', 'inner');
        $this->db->join('editions', 'editions.edition_id=races.edition_id', 'left');
        $this->db->join('events', 'editions.event_id=events.event_id', 'left');
        $this->db->join('racetypes', 'racetypes.racetype_id=races.racetype_id', 'left');
        if ($query) {
            $this->db->group_start();
            $this->db->like('edition_name', $query);
            $this->db->or_like('event_name', $query);
            $this->db->group_end();
        }
        // limit the list a little
        if ($limit_results) {
            $this->db->where("edition_date > ", date("Y-m-d", strtotime("3 months ago")));
            $this->db->where("edition_date < ", date("Y-m-d", strtotime("+9 month")));
        }
        $this->db->order_by('edition_date', "DESC");
        $this->db->order_by('race_distance', "DESC");
        // $this->db->limit("50");
        //        echo $this->db->get_compiled_select();
        //        die();
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $distance = round($row['race_distance'], 0);
                $year = date('Y', strtotime($row['edition_date']));
                $data[$row['race_id']] = $row;
                $data[$row['race_id']]['race_color'] = $this->get_race_color($row['race_distance']);
                $data[$row['race_id']]['race_sum'] = $row['event_name'] . " | " . $year . " | " . $distance . " km | " . $row['racetype_abbr'];
            }
            return $data;
        }
        return false;
    }

    public function get_race_detail_with_results($params = [])
    {
        $this->db->select("results.*,race_name, race_distance, edition_name, edition_date, edition_slug, event_name, town_name, file_name");
        $this->db->from("races");
        $this->db->join('results', 'results.race_id = races.race_id', 'inner');
        $this->db->join('files', 'results.file_id = files.file_id', 'inner');
        $this->db->join('editions', 'editions.edition_id=races.edition_id', 'left');
        $this->db->join('events', 'editions.event_id=events.event_id', 'left');
        $this->db->join('towns', 'town_id', 'left');
        if (isset($params['race_id'])) {
            $this->db->where("races.race_id", $params['race_id']);
        }
        if (isset($params['result_id'])) {
            $this->db->where("results.result_id", $params['result_id']);
        }
        if (isset($params['name'])) {
            $this->db->group_start();
            $this->db->like('result_name', $params['name']);
            $this->db->or_like('result_surname', $params['surname']);
            $this->db->group_end();
        }
        //        echo $this->db->get_compiled_select();
        //        die();
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['result_id']] = $row;
                $data[$row['result_id']]['race_color'] = $this->get_race_color($row['race_distance']);
            }
            return $data;
        }
        return false;
    }


    public function get_race_list_search()
    {

        $field_arr = [
            "race_id", "edition_id", "race_name", "race_distance", "race_time_start", "racetype_abbr", "racetype_icon"
        ];
        $select = implode(",", $field_arr);
        $this->db->select($select);
        $this->db->from("races");
        $this->db->join("racetypes", "racetype_id");
        // $this->db->order_by("race_time_start", "ASC");  
        $query = $this->db->get();
        // return $query->result_array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['race_id']] = $row;
                $data[$row['race_id']]['race_color'] = $this->get_race_color($row['race_distance']);
            }
            return $data;
        }
        return false;
    }
}
