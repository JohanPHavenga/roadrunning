<?php

class Userresult_model extends Admin_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("user_result");
    }

    public function exists($user_id, $result_id) {
        $this->db->select("*");
        $this->db->from("user_result");
        $this->db->where('user_id', $user_id);
        $this->db->where('result_id', $result_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        }
        return false;
    }

    public function get_userresult_list($user_id = null, $result_id = null, $race_id = null) {

        if (($user_id == null) && ($result_id == null) && ($race_id == null)) {
            return false;
        }

        $this->db->select("results.*,races.*,user_id, user_name, user_surname, edition_date, edition_name,edition_slug");
        $this->db->from("user_result");
        $this->db->join("users", "user_id");
        $this->db->join("results", "result_id");
        $this->db->join("races", "race_id");
        $this->db->join("editions", "edition_id");

        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }
        if ($result_id) {
            $this->db->where('result_id', $result_id);
        }
        if ($race_id) {
            $this->db->where('race_id', $race_id);
        }

        $this->db->order_by('edition_date', "DESC");
        $this->db->order_by('user_surname', "ASC");
//        echo $this->db->get_compiled_select();
//        die();
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $key => $row) {
                $userresult_list[$key] = $row;
                $userresult_list[$key]['race_color'] = $this->get_race_color($row['race_distance']);
            }
            return $userresult_list;
        }
        return false;
    }

    public function set_userresult($action, $userresult_data = [], $debug = false) {

// POSTED DATA
        if (empty($userresult_data)) {
            $userresult_data = array(
                'user_id' => $this->input->post('user_id'),
                'result_id' => $this->input->post('result_id'),
            );
        }

        if ($debug) {
            echo "<p><b>URL SET Transaction</b></p>";
            echo "ACTION: " . $action . "<br>";
            wts($userresult_data);
            die();
        } else {
            switch ($action) {
                case "add":
                    $this->db->trans_start();
                    $this->db->insert('user_result', $userresult_data);
                    $sql = $this->db->set($userresult_data)->get_compiled_insert('userresults');
// wts($sql,1);
                    $this->db->trans_complete();
                    break;
                default:
                    show_404();
                    break;
            }
// return ID if transaction successfull
            if ($this->db->trans_status()) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function remove_userresult($user_id, $result_id) {
        if (!($user_id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('user_result', array('user_id' => $user_id, 'result_id' => $result_id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
