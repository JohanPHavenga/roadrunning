<?php

class Result_model extends Admin_model {

    public $table = "results";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all($this->table);
    }

    public function get_result_field_array() {
        $fields = $this->db->list_fields($this->table);
        foreach ($fields as $field) {
            $data[$field] = "";
        }
        return $data;
    }

    public function get_result_field_dropdown() {
        $result_fields = $this->get_result_field_array();
        $unset_arr = ["result_id", "race_id", "file_id", "created_date", "updated_date"];
        foreach ($unset_arr as $unset_field) {
            unset($result_fields[$unset_field]);
        }
        $result_fields = array_keys($result_fields);
        foreach ($result_fields as $field) {
            $field_arr[$field] = str_replace("result_", "", $field);
        }
        array_unshift($field_arr, '');
        return $field_arr;
    }

    public function get_result_list($race_id=null) {
        $this->db->select("results.*, race_name, race_distance, edition_name, edition_date, event_name, racetype_abbr");
        $this->db->from($this->table);
        $this->db->join('races', 'race_id');
        $this->db->join('racetypes', 'racetype_id');
        $this->db->join('editions', 'edition_id');
        $this->db->join('events', 'event_id', 'left');
        if ($race_id) {
            $this->db->where('race_id', $race_id);
        } else {
            $this->db->limit(1000);
        }
        $this->db->order_by('edition_date', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['result_id']] = $row;
                $distance = str_pad(round($row['race_distance'], 0), 2, '0', STR_PAD_LEFT);
                $year = date('Y', strtotime($row['edition_date']));
                $data[$row['result_id']]['race_sum'] = $row['event_name'] . " | " . $year . " | " . $distance . " km | " . $row['racetype_abbr'];
            }
            return $data;
        }
        return false;
    }

    public function get_result_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where('results', array('result_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_result($action, $result_id, $data) {
        if (isset($data['save_only'])) {
            unset($data['save_only']);
        }

        switch ($action) {
            case "add":
                $this->db->insert('results', $data);
                $result_id = $this->db->insert_id();
                break;
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                $this->db->update('results', $data, array('result_id' => $result_id));
                break;
            default:
                show_404();
                break;
        }
        return $result_id;
    }

    public function remove_result($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('results', array('result_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }
    
    public function remove_result_set($race_id) {
        if (!($race_id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('results', array('race_id' => $race_id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function search_result($ss) {

        $search_result = [];

        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->join('races', 'race_id');
        $this->db->group_start();
        $this->db->or_like("race_name", $ss);
        $this->db->or_like("result_surname", $ss);
        $this->db->or_like("result_name", $ss);
        $this->db->or_like("result_asanum", $ss);
        $this->db->or_like("result_racenum", $ss);
        $this->db->group_end();

        $this->db->order_by("result_surname", "DESC");

//            echo $this->db->get_compiled_select();
//            die();

        return $this->db->get();
    }
    
    public function auto_search($params) {

        $search_result = [];

        $this->db->select("results.*,race_distance,edition_name, edition_date");
        $this->db->from($this->table);
        $this->db->join('races', 'race_id');
        $this->db->join('editions', 'edition_id');
        $this->db->where('result_name', $params['name'], NULL, false);
        $this->db->where('result_surname', $params['surname'], NULL, false);
        $this->db->order_by("edition_date", "DESC");
        
//            echo $this->db->get_compiled_select();
//            die();
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['result_id']] = $row;                
            }
            return $data;
        }
        return false;
    }

    public function result_exist_for_race($race_id) {
        $query = $this->db->get_where('results', array('race_id' => $race_id));
        if ($query->num_rows() > 0) {
            return true;
        }
        return false;
    }

}
