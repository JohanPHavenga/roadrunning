<?php

class Event_model extends Admin_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("events");
    }

    public function get_event_id($event_name) {
        $this->db->select("event_id");
        $this->db->from("events");
        $this->db->where('LOWER(event_name)', strtolower($event_name));
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data = $row['event_id'];
            }
            return $data;
        }
        return false;
    }

    public function get_event_list($limit = NULL, $start = NULL) {
        if (isset($limit) && isset($start)) {
            $this->db->limit($limit, $start);
        }

        $this->db->select("events.*, town_name, club_name, area_name");
        $this->db->from("events");
        $this->db->join('towns', 'events.town_id=towns.town_id', 'left');
        $this->db->join('organising_club', 'events.event_id=organising_club.event_id', 'left');
        $this->db->join('clubs', 'clubs.club_id=organising_club.club_id', 'left');
        $this->db->join('town_area', 'towns.town_id=town_area.town_id', 'left');
        $this->db->join('areas', 'areas.area_id=town_area.area_id', 'left');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['event_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_event_dropdown() {
        $this->db->select("event_id, event_name");
        $this->db->from("events");
        $this->db->order_by("event_name");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['event_id']] = $row['event_name'] . " [#" . $row['event_id'] . "]";
            }
            return $data;
        }
        return false;
    }

    public function get_event_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("events.*, club_id, town_name");
            $this->db->from("events");
            $this->db->join('towns', 'town_id', 'left');
            $this->db->join('organising_club', 'event_id', 'left');
            $this->db->where('event_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_event($action, $event_id, $event_data = [], $debug = false) {
        // POSTED DATA
        if (empty($event_data)) {
            $event_data = array(
                'event_name' => $this->input->post('event_name'),
                'event_status' => $this->input->post('event_status'),
                'town_id' => $this->input->post('town_id'),
            );
            $organise_club_data = ["club_id" => $this->input->post('club_id'), "event_id" => $event_id];
        } else {
            if ($event_data['club_id']) {
                $club_id = $event_data['club_id'];
                unset($event_data['club_id']);
            } else {
                $club_id = 8;
            }
            $organise_club_data = ["club_id" => $club_id, "event_id" => $event_id];
            if (!isset($event_data['event_status'])) {
                $event_data['event_status'] = 1;
            }
            if (!isset($event_data['town_id'])) {
                $event_data['town_id'] = 1;
            }
        }

        if ($debug) {
            echo "<b style='color:red;'>Event Transaction</b>";
            wts($action);
            // wts($event_id);
            wts($event_data);
        } else {
            switch ($action) {
                case "add":
                    $this->db->trans_start();
                    $this->db->insert('events', $event_data);
                    // get event ID from Insert
                    $event_id = $this->db->insert_id();
                    // update data array
                    $organise_club_data["event_id"] = $event_id;
                    $this->db->insert('organising_club', $organise_club_data);
                    $this->db->trans_complete();
                    break;
                case "edit":
                    // add updated date to both data arrays
                    $event_data['updated_date'] = date("Y-m-d H:i:s");

                    // start SQL transaction
                    $this->db->trans_start();
                    // chcek if record already exists
                    $item_exists = $this->db->get_where('organising_club', array('event_id' => $event_id, 'club_id' => $this->input->post('club_id')));
                    if ($item_exists->num_rows() == 0) {
                        $organise_club_data['updated_date'] = date("Y-m-d H:i:s");
                        $this->db->delete('organising_club', array('event_id' => $event_id));
                        $this->db->insert('organising_club', $organise_club_data);
                    }
                    $this->db->update('events', $event_data, array('event_id' => $event_id));
                    $this->db->trans_complete();
                    break;
                default:
                    show_404();
                    break;
            }
            // return ID if transaction successfull
            if ($this->db->trans_status()) {
                return $event_id;
            } else {
                return false;
            }
        }
    }

    public function remove_event($id) {
        if (!($id)) {
            return false;
        } else {
            // only event needed, SQL key constraints used to remove records from organizing_club
            $this->db->trans_start();
            $this->db->delete('events', array('event_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function get_area_list() {
        $area_list = [];
        // set query
        $this->db->select("areas.area_id, area_name");
        $this->db->from("events");
        $this->db->join('editions', 'editions.event_id = events.event_id');
        $this->db->join('towns', 'towns.town_id = events.town_id');
        $this->db->join('town_area', 'towns.town_id = town_area.town_id');
        $this->db->join('areas', 'areas.area_id = town_area.area_id');
        $this->db->where("area_name !=", '');
        $this->db->where("edition_date >=", date("Y-m-d"));
        $this->db->order_by("area_name", "asc");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $area_list[$row['area_id']]['id'] = $row['area_id'];
                $area_list[$row['area_id']]['name'] = $row['area_name'];
            }
        }
        return $area_list;
    }

    public function get_event_list_data($params) {

        // field_arr is compulsary
        foreach ($params['field_arr'] as $field) {
            if ($field == "results_file") {
                $field = "file_name as results_file";
            }
            $field_arr[] = $field;
        }
//            wts($field_arr);
//            die();
        // set default sort
        if (!isset($params['sort'])) {
            $params['sort'] = "ASC";
        }
        $sort = $params['sort'];

//            $this->db->select($field_arr);
        $this->db->from("events");
        $this->db->join('editions', 'editions.event_id = events.event_id');
        $this->db->join('races', 'races.edition_id = editions.edition_id');
        $this->db->join('racetypes', 'races.racetype_id = racetypes.racetype_id', 'left outer');
        $this->db->join('towns', 'towns.town_id = events.town_id');
        $this->db->join('edition_user', 'editions.edition_id = edition_user.edition_id', 'left outer');
        $this->db->join('users', 'users.user_id = edition_user.user_id', 'left outer');

        if (isset($params['area'])) {
            $this->db->join('town_area', 'towns.town_id = town_area.town_id');
            $this->db->join('areas', 'areas.area_id = town_area.area_id');
            $this->db->where("area_name", $params['area']);
        }

        if (isset($params['date_from'])) {
            if (!isset($params['date_to'])) {
                $this->db->where("edition_date >=", $params['date_from']);
            } else {
                $this->db->where("(edition_date BETWEEN '" . $params['date_from'] . "' AND '" . $params['date_to'] . "')");
            }
        } else {
            $this->db->where("editions.edition_id", $params['edition_id']);
        }

        if (isset($params['info_status'])) {
            $this->db->where_in("edition_info_status", $params['info_status']);
        }

        if (isset($params['entry_date'])) {
            $this->db->join('dates', "editions.edition_id = dates.linked_id AND dates.date_linked_to='edition' AND datetype_id = 3", 'left');
            $this->db->where("(date_end BETWEEN '" . $params['date_from'] . "' AND '" . $params['entry_date'] . "')");
        }

        // ONLY ACTIVE 
        if (@$params['only_active']) {
            $this->db->where("events.event_status", 1);
            $this->db->where("editions.edition_status", 1);
            $this->db->where("races.race_status", 1);
        } else {
            $this->db->where('events.event_status !=', 2);
            $this->db->where("editions.edition_status !=", 2);
            $this->db->where("races.race_status !=", 2);
        }

        // TBR
//        if (isset($params['confirmed'])) {
//            $this->db->where("edition_info_isconfirmed", $params['confirmed']);
//            $this->db->where("edition_status", 1);
//        }
//        if (isset($params['results'])) {
//            $this->db->where("edition_results_isloaded", $params['results']);
//            $this->db->where("edition_status", 1);
//        }
//        if (isset($params['results_status'])) {
//            $this->db->where("edition_results_status", $params['results_status']);
//            $this->db->where("edition_status", 1);
//        }
        // TBR END

        $this->db->order_by("edition_date", $sort);
        $this->db->order_by('race_distance', 'DESC');
        $this->db->order_by('racetype_name', 'ASC');

        $this->db->select($field_arr);

//        wts($params);
//        echo $this->db->get_compiled_select();
//        exit();


        return $this->db->get();
    }

    // ======================================================================================
    // MAIN QUERY TO GET EVENT DATA 
    // for backend and frontend
    // ======================================================================================
    public function get_event_list_summary($from, $params) {
        // set fields to be fetched
        $field_arr = ["event_name", "editions.edition_id", "edition_name", "edition_slug", "edition_status", "edition_info_status", "edition_date", "edition_isfeatured", "edition_info_email_sent",
//            "edition_results_status", "edition_info_isconfirmed", "edition_results_isloaded", "edition_logo", "edition_entries_date_close", // TBR this line            
            "racetype_abbr", "town_name", "race_distance", "race_time_start",
            "user_name", "user_surname", "user_email"];

        // setup fields needed for summary call
        // go get the data
        if ($from == "date_range") {
            if (!isset($params['date_to'])) {
                $params['date_to'] = NULL;
            }
            if (!isset($params['area'])) {
                $params['area'] = NULL;
            }
            if (!isset($params['sort'])) {
                $params['sort'] = "ASC";
            }
            $query = $this->get_event_list_data(
                    [
                        "field_arr" => $field_arr,
                        "date_from" => @$params['date_from'],
                        "date_to" => @$params['date_to'],
                        "area" => @$params['area'],
                        "sort" => @$params['sort'],
                        "info_status" => @$params['info_status'],
                        "only_active" => @$params['only_active'],
                        "entry_date" => @$params['entry_date'],
                    // below to be removed
//                        "confirmed" => @$params['confirmed'],
//                        "results" => @$params['results'],
//                        "results_status" => @$params['results_status'],
                    ]
            );
        } elseif ($from == "search") {
            $query = $this->search_events($field_arr, $params['ss'], $params['inc_all'], @$params['inc_non_active']);
        } elseif ($from == "id") {
            $query = $this->get_event_list_data(
                    [
                        "field_arr" => $field_arr,
                        "edition_id" => $params['edition_id'],
                    ]
            );
        } else {
            die("'get_event_summary_list: no from provided");
        }

        // as daar nie enige resultate was nie, stuur terun
        if (!$query->num_rows()) {
            return false;
        }

        // formulate the return            
        foreach ($query->result_array() as $row) {
//            wts($row);
            $year = date("Y", strtotime($row['edition_date']));
            $month = date("F", strtotime($row['edition_date']));
            $day = date("d", strtotime($row['edition_date']));
            $id = $row['edition_id'];

            foreach ($field_arr as $field) {

                // vir as daar 'n veld is met 'n table naam vooraan
                if (strpos($field, ".") !== false) {
                    $pieces = explode(".", $field);
                    $field = $pieces[1];
                }

                switch ($field) {
                    case "datetype_id":
                        $data[$year][$month][$day][$id]['date_arr'][$row[$field]] = $row['date_start'];
                        break;
                    case "race_distance":
                        $value = floatval($row[$field]) . "km";
                        $value_arr = intval($row[$field]);
                        if ($row['racetype_abbr'] == "W") {
                            $value .= " W";
                            $value_arr .= "W";
                        }
                        if ($row['racetype_abbr'] == "T") {
                            $value .= " T";
                            $value_arr .= "T";
                        }

                        // also add an array of race distances
                        $data[$year][$month][$day][$id]['distance_arr'][$value_arr] = $value_arr;

                        // make string
                        if (isset($data[$year][$month][$day][$id][$field])) {
                            $value = $data[$year][$month][$day][$id][$field] . ", " . $value;
                        };
                        break;
                    case "race_time_start":
                        if (date("H", strtotime($row[$field])) > 0) {
                            $value = "Morning";
                        }
                        if (date("H", strtotime($row[$field])) > 12) {
                            $value = "Afternoon";
                        }
                        if (date("H", strtotime($row[$field])) > 17) {
                            $value = "Evening";
                        }
                        if (date("H", strtotime($row[$field])) > 21) {
                            $value = "Night";
                        }

                        if (!isset($data[$year][$month][$day][$id]["start_time"])) {
                            $data[$year][$month][$day][$id]["start_time"] = date("H:i", strtotime($row[$field]));
                        } else {
                            $data[$year][$month][$day][$id]["start_time"] .= ", " . date("H:i", strtotime($row[$field]));
                        }
                        break;
                    case "edition_date":
                        $value = fdateHumanFull($row[$field], true);
                        $data[$year][$month][$day][$id]["edition_timestamp"] = $row[$field];
                        break;
//                    case "edition_name":
//                        $value = $row[$field];
//                        $edition_url_name = encode_edition_name($row[$field]);
//                        $data[$year][$month][$day][$id]["edition_url"] = "/event/" . $edition_url_name;
//                        break;
                    case "edition_slug":
                        $value = $row[$field];
                        $data[$year][$month][$day][$id]["edition_url"] = "/event/" . $row[$field];
                        break;
                    default:
                        $value = $row[$field];
                        break;
                }

                // haal racetype_abbr uit die lys
                if ($field != "racetype_abbr") {
                    $data[$year][$month][$day][$id][$field] = $value;
                }
            }
        }
        if ($from == "id") {
            return $data[$year][$month][$day][$id];
        } else {
            return $data;
        }
    }

    public function get_event_list_sitemap($params) {

        $field_arr = ['edition_slug'];
        $this->db->select($field_arr);
        $this->db->from("editions");
        $this->db->where("edition_status", 1);

        $date_to = date("Y-m-d", strtotime("+3 months"));
        $date_from = date("Y-m-d", strtotime("-3 months"));
        $year_ago = date("Y-m-d", strtotime("-1 year"));
        $today = date("Y-m-d");

        //c onfirmed races
        if (isset($params['confirmed'])) {
            $this->db->where("edition_info_status", 16);
            $this->db->where("edition_date >=", $today);
        }

        // next 3 months races
        if (isset($params['upcoming_close'])) {
            $this->db->where_in("edition_info_status", [14, 15]);
//            $this->db->where("edition_info_isconfirmed !=", 1);
            $this->db->where("(edition_date BETWEEN '" . $today . "' AND '" . $date_to . "')");
        }

        // rest of upcoming races
        if (isset($params['upcoming_further'])) {
//            $this->db->where("edition_info_isconfirmed !=", 1);
            $this->db->where_in("edition_info_status", [13, 14, 15]);
            $this->db->where("edition_date >= ", $date_to);
        }

        // races past 3 months
        if (isset($params['past_close'])) {
            $this->db->where("(edition_date BETWEEN '" . $date_from . "' AND '" . $today . "')");
        }

        // has results, more than 3 months, less than a year
        if (isset($params['has_results_year'])) {
            $this->db->where_in("edition_info_status", [11]);
            $this->db->where("(edition_date BETWEEN '" . $year_ago . "' AND '" . $date_from . "')");
        }

        // no results, more than 3 months, less than a year
        if (isset($params['no_results_year'])) {
            $this->db->where_in("edition_info_status", [10, 12]);
            $this->db->where("(edition_date BETWEEN '" . $year_ago . "' AND '" . $date_from . "')");
        }

        // more than a year old
        if (isset($params['old'])) {
            $this->db->where("edition_date < ", $year_ago);
        }

        $this->db->order_by("edition_date", "DESC");



        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
//                $edition_url_name = urlencode(str_replace(" ", "-", (str_replace("'", "", str_replace("/", " ", $row["edition_name"])))));
//                $url_list[] = "event/" . $edition_url_name/
                $url_list[] = "event/" . $row['edition_slug'];
            }
            return $url_list;
        }
        return false;
    }

    public function search_events($field_list, $ss, $show_all = false, $incl_non_active = false) {

        $search_result = [];

        $this->db->select($field_list);
        $this->db->from("events");
        $this->db->join('editions', 'editions.event_id = events.event_id');
        $this->db->join('races', 'races.edition_id = editions.edition_id');
        $this->db->join('towns', 'towns.town_id = events.town_id');
        $this->db->join('racetypes', 'races.racetype_id = racetypes.racetype_id', 'left outer');
        $this->db->join('edition_user', 'editions.edition_id = edition_user.edition_id', 'left outer');
        $this->db->join('users', 'users.user_id = edition_user.user_id', 'left outer');
        $this->db->group_start();
        $this->db->or_like("event_name", $ss);
        $this->db->or_like("edition_name", $ss);
        $this->db->or_like("town_name", $ss);
        $this->db->group_end();

        if (!$incl_non_active) {
            $this->db->where_in("events.event_status", [1, 3, 4]);
            $this->db->where("editions.edition_status", 1);
            $this->db->where("races.race_status", 1);
        }
        if (!$show_all) {
            $this->db->where("edition_date > ", date("Y-m-d", strtotime("2 months ago")));
            $this->db->where("edition_date < ", date("Y-m-d", strtotime("+10 month")));
        }

        $this->db->order_by("edition_date", "DESC");
        $this->db->order_by("race_distance", "DESC");

//            echo $this->db->get_compiled_select();
//            die();

        return $this->db->get();
    }

    public function get_missing_editions($year) {
        $previous_year = $year - 1;

        // this year
        $date_from = date($previous_year . "-1-1");
        $date_to = date($year . "-12-31");

        $this->db->select("events.event_id, event_name, edition_id, edition_name, edition_date, asa_member_abbr");
        $this->db->from("events");
        $this->db->join('editions', 'event_id');
        $this->db->join('edition_asa_member', 'edition_id', 'left');
        $this->db->join('asa_members', 'asa_member_id', 'left');
        $this->db->where("(edition_date BETWEEN '" . $date_from . "' AND '" . $date_to . "')");
        $this->db->where("edition_remove_audit", 0);
        $this->db->order_by("edition_date", "ASC");

//        echo $this->db->get_compiled_select();
//        die();

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['event_id']][date("Y", strtotime($row['edition_date']))] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_event_data_newsletter() {

//        $field_list = "events.event_id, event_name, edition_id, edition_name, edition_date, edition_info_isconfirmed, edition_results_isloaded";
        $field_list = "events.event_id, event_name, edition_id, edition_name, edition_date, edition_info_status";

        // last month
        $from = date("Y-m-1 00:00:00", strtotime("10 days ago"));
        $to = date("Y-m-d 23:59:59");

        $this->db->select($field_list);
        $this->db->from("events");
        $this->db->join('editions', 'editions.event_id = events.event_id');
        $this->db->where("(edition_date BETWEEN '" . $from . "' AND '" . $to . "')");
        $this->db->where('editions.edition_status =', 1);
        $this->db->order_by("edition_date", "ASC");
        $query = $this->db->get();

        foreach ($query->result_array() as $row) {
            $year = date("Y", strtotime($row['edition_date']));
            $month = date("F", strtotime($row['edition_date']));
            $day = date("d", strtotime($row['edition_date']));
            $id = $row['edition_id'];

            $data["past"][$year][$month][$day][$id] = $row;
        }

        // next 2 month
        $from = date("Y-m-d 00:00:00");
        $to = date("Y-m-t 23:59:59", strtotime("2 months"));

        $this->db->select($field_list);
        $this->db->from("events");
        $this->db->join('editions', 'editions.event_id = events.event_id');
        $this->db->where("(edition_date BETWEEN '" . $from . "' AND '" . $to . "')");
        $this->db->where('editions.edition_status =', 1);
        $this->db->order_by("edition_date", "ASC");
        $query = $this->db->get();

        foreach ($query->result_array() as $row) {
            $year = date("Y", strtotime($row['edition_date']));
            $month = date("F", strtotime($row['edition_date']));
            $day = date("d", strtotime($row['edition_date']));
            $id = $row['edition_id'];

            $data["future"][$year][$month][$day][$id] = $row;
        }

        return $data;
    }

    public function get_edition_list($event_id) {
        $this->db->select("edition_id, edition_name, edition_date, edition_status, edition_slug");
        $this->db->from("editions");
        $this->db->where("event_id", $event_id);
        $this->db->where("edition_status", 1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['edition_id']] = $row;
                $data[$row['edition_id']]['edition_year'] = date("Y", strtotime($row['edition_date']));
                $edition_url_name = encode_edition_name($data[$row['edition_id']]['edition_name']);
                $data[$row['edition_id']]['edition_url'] = "/event/" . $row['edition_slug'];
            }
            return $data;
        }
        return false;
    }

    public function main_search($ss) {

        $data = [];

        $this->db->select("editions.edition_id, edition_name, edition_date, event_name, edition_status, edition_info_status, 
        main_status.status_name as main_status_name, info_status.status_name as info_status_name, race_id, race_distance");
        $this->db->from("events");
        $this->db->join('editions', 'editions.event_id = events.event_id');
        $this->db->join('`status` `main_status`', 'editions.edition_status = main_status.status_id');
        $this->db->join('`status` `info_status`', 'editions.edition_info_status = info_status.status_id');
        $this->db->join('races', 'races.edition_id = editions.edition_id');
        $this->db->join('towns', 'towns.town_id = events.town_id');
        $this->db->join('racetypes', 'races.racetype_id = racetypes.racetype_id', 'left outer');
        $this->db->group_start();
        $this->db->or_like("event_name", $ss);
        $this->db->or_like("edition_name", $ss);
        $this->db->or_like("town_name", $ss);
        $this->db->group_end();
        $this->db->order_by("edition_date", "DESC");
        $this->db->order_by("race_distance", "DESC");

        // wts($this->db->get_compiled_select(),1);

        $query = $this->db->get();
          

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['edition_id']]['edition_name'] = $row['edition_name'];
                $data[$row['edition_id']]['edition_date'] = $row['edition_date'];
                $data[$row['edition_id']]['event_name'] = $row['event_name'];
                $data[$row['edition_id']]['status_id'] = $row['edition_status'];
                $data[$row['edition_id']]['main_status_name'] = $row['main_status_name'];
                $data[$row['edition_id']]['info_status_id'] = $row['edition_info_status'];
                $data[$row['edition_id']]['info_status_name'] = $row['info_status_name'];
                $data[$row['edition_id']]['races'][$row['race_id']]['distance'] = $row['race_distance'];
                $data[$row['edition_id']]['races'][$row['race_id']]['color'] = $this->get_race_color($row['race_distance']);
            }
        }
        return $data;
    }

}
