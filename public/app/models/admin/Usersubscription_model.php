<?php

class Usersubscription_model extends Admin_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("usersubscriptions");
    }

    public function exists($user_id, $linked_to, $linked_id) {
        $this->db->select("*");
        $this->db->from("usersubscriptions");
        $this->db->where('user_id', $user_id);
        $this->db->where('linked_to', $linked_to);
        $this->db->where('linked_id', $linked_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        }
        return false;
    }

    public function get_usersubscription_list($linked_to = NULL, $linked_id = 0) {

        $this->db->select("*");
        $this->db->from("usersubscriptions");

        if ($linked_to) {
            $this->db->where('linked_to', $linked_to);
            $this->db->where('linked_id', $linked_id);
        }
//        echo $this->db->get_compiled_select();
//        die();        
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $usersubscription_list[] = $row;
            }
            return $usersubscription_list;
        }
        return false;
    }

    public function get_usersubscription_detail($id, $linked_to = NULL, $linked_id = 0) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("usersubscriptions.*");
            $this->db->from("usersubscriptions");
            $this->db->where('user_id', $id);
            if ($linked_id > 0) {
                $this->db->where('linked_to', $linked_to);
                $this->db->where('linked_id', $linked_id);
            }
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_usersubscription($action, $usersubscription_data = [], $debug = false) {

        // POSTED DATA
        if (empty($usersubscription_data)) {
            $id_type = $this->input->post("linked_to") . "_id";
            $id = $this->input->post($id_type);

            $usersubscription_data = array(
                'user_id' => $this->input->post('user_id'),
                'linked_to' => $this->input->post('linked_to'),
                'linked_id' => $id,
            );
        }

        if ($debug) {
            echo "<p><b>URL SET Transaction</b></p>";
            echo "ACTION: " . $action . "<br>";
            wts($usersubscription_data);
            die();
        } else {
            switch ($action) {
                case "add":
                    $this->db->trans_start();
                    $this->db->insert('usersubscriptions', $usersubscription_data);
                    $sql = $this->db->set($usersubscription_data)->get_compiled_insert('usersubscriptions');
                    //                wts($sql);
                    //                die();
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

    public function remove_usersubscription($user_id, $linked_to, $linked_id) {
        if (!($user_id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('usersubscriptions', array('user_id' => $user_id, 'linked_to' => $linked_to, 'linked_id' => $linked_id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function check_usersubscription_exists($user_id, $linked_to, $linked_id) {
        $this->db->select("*");
        $this->db->from("usersubscriptions");
        $this->db->where('linked_to', $linked_to);
        $this->db->where('linked_id', $linked_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

}
