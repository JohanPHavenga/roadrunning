<?php

class Emailmerge_model extends Admin_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("emailmerges");
    }

    public function count_draft() {
        $this->db->where('emailmerge_status', 4);
        $this->db->from('emailmerges');
        return $this->db->count_all_results(); 
    }

    public function get_emailmerge_list($top = 0) {

        $this->db->select("emailmerges.*");
        $this->db->from("emailmerges");
        if ($top > 0) {
            $this->db->limit($top);
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $count_arr = explode(",", $row['emailmerge_recipients']);
                $row['count'] = count($count_arr);
                $data[$row['emailmerge_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_emailmerge_detail($id) {

        $this->db->select("emailmerges.*");
        $this->db->from("emailmerges");
        $this->db->where("emailmerge_id", $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    public function set_emailmerge($action, $emailmerge_id, $data) {
        $data['updated_date'] = date("Y-m-d H:i:s");
        switch ($action) {
            case "add":
                $this->db->trans_start();
                $this->db->insert('emailmerges', $data);
                // get edition ID from Insert
                $emailmerge_id = $this->db->insert_id();
                $this->db->trans_complete();
                break;
            case "edit":
                $this->db->trans_start();
                $data['updated_date'] = date("Y-m-d H:i:s");
                $this->db->update('emailmerges', $data, array('emailmerge_id' => $emailmerge_id));
                $this->db->trans_complete();
                break;
            default:
                die("Action not set");
                break;
        }
        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return $emailmerge_id;
        } else {
            return false;
        }
    }

    public function remove_emailmerge($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('emailmerges', array('emailmerge_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function copy_template($id) {
        /* generate the select query */
        $this->db->where('emailmerge_id', $id);
        $query = $this->db->get('emailmerges');

        foreach ($query->result() as $row) {
            foreach ($row as $key => $val) {
                if ($key != 'emailmerge_id') {
                    /* $this->db->set can be used instead of passing a data array directly to the insert or update functions */
                    $this->db->set($key, $val);
                }//endif              
            }//endforeach
        }//endforeach

        /* insert the new record into table */
        $this->db->trans_start();
        $this->db->insert('emailmerges');
        $edition_id = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            return $edition_id;
        } else {
            return false;
        }
    }

}
