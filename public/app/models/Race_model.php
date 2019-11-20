<?php

class Race_model extends MY_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("races");
    }

    public function get_race_list($edition_id = 0) {

        $this->db->select("races.*, edition_name, edition_date, racetype_name, racetype_abbr, racetype_icon");
        $this->db->from("races");
        $this->db->join('editions', 'editions.edition_id=races.edition_id', 'left');
        $this->db->join('racetypes', 'racetypes.racetype_id=races.racetype_id', 'left');
        if ($edition_id > 0) {
            $this->db->where('races.edition_id', $edition_id);
        }
        $this->db->order_by('races.race_distance', 'DESC');
        $this->db->order_by('racetype_name', 'ASC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['race_id']] = $row;
                $data[$row['race_id']]['race_color'] = $this->get_race_color($row['race_distance']);
                if (strtotime($row['race_date'])<0) {
                    $data[$row['race_id']]['race_date']=$row['edition_date'];
                }
            }
            return $data;
        }
        return false;
    }

    public function get_race_dropdown() {
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

    public function get_race_detail($id) {
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

    function get_race_color($distance) {

        switch (true) {
            case $distance <= 9:
                $color = 'danger';
                break;

            case $distance == 10:
                $color = 'warning';
                break;

            case $distance <= 21:
                $color = 'secondary';
                break;

            case $distance == 21.1:
                $color = 'primary';
                break;

            case $distance <= 42:
                $color = 'info';
                break;

            case $distance == 42.2:
                $color = 'success';
                break;

            default:
                $color = 'dark';
                break;
        }

        return $color;
    }
    
    function get_race_icon($type) {

        switch ($type) {
            case $distance <= 9:
                $color = 'danger';
                break;

            case $distance == 10:
                $color = 'warning';
                break;

            case $distance <= 21:
                $color = 'secondary';
                break;

            case $distance == 21.1:
                $color = 'primary';
                break;

            case $distance <= 42:
                $color = 'info';
                break;

            case $distance == 42.2:
                $color = 'success';
                break;

            default:
                $icon = 'running';
                break;
        }

        return $icon;
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

}
