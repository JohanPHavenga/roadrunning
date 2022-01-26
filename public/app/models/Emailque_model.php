<?php

class Emailque_model extends Frontend_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("emailques");
    }

    public function check_id($id) {
        $this->db->select("emailque_id");
        $this->db->from("emailques");
        $this->db->where("emailque_id", $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        }
        return false;
    }

    public function get_emailstatus_name($id) {
        $this->db->select("status_name");
        $this->db->from("status");
        $this->db->where("status_id", $id);
//        echo $this->db->get_compiled_select();
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $status_name = $row['status_name'];
            }
            return $status_name;
        }
        return false;
    }

    public function get_emailque_list($top = 0, $status = 4) {

        $this->db->select("emailques.*");
        $this->db->from("emailques");
        if ($top > 0) {
            $this->db->limit($top);
        }
        $this->db->where("emailque_status", $status);  // status 5 = pending
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['emailque_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_emailque_detail($id) {

        $this->db->select("emailques.*");
        $this->db->from("emailques");
        $this->db->where("emailque_id", $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    public function set_emailque_status($emailque_id, $status_id) {
        $data = array(
            'emailque_status' => $status_id,
            'updated_date' => date("Y-m-d H:i:s"),
        );
        return $this->db->update('emailques', $data, array('emailque_id' => $emailque_id));
    }

    public function set_emailque($params) {
        $action=$params['action'];
        $data=$params['data'];
        $data['updated_date'] = date("Y-m-d H:i:s");
        switch ($action) {
            case "add":
                $this->db->trans_start();
                $this->db->insert('emailques', $data);
                // get edition ID from Insert
                $emailque_id = $this->db->insert_id();
                $this->db->trans_complete();
                break;
            case "edit":
                $emailque_id=$params['id'];
                $this->db->trans_start();
                $data['updated_date'] = date("Y-m-d H:i:s");
                $this->db->update('emailques', $data, array('emailque_id' => $emailque_id));
                $this->db->trans_complete();
                break;
            default:
                die("Action not set");
                break;
        }
        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return $emailque_id;
        } else {
            return false;
        }
    }

    public function remove_emailque($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('emailques', array('emailque_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function copy_email($id) {
        /* generate the select query */
        $this->db->where('emailque_id', $id);
        $query = $this->db->get('emailques');

        foreach ($query->result() as $row) {
            foreach ($row as $key => $val) {
                if ($key != 'emailque_id') {
                    /* $this->db->set can be used instead of passing a data array directly to the insert or update functions */
                    $this->db->set($key, $val);
                }//endif              
            }//endforeach
        }//endforeach

        /* insert the new record into table */
        $this->db->trans_start();
        $this->db->insert('emailques');
        $edition_id = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            return $edition_id;
        } else {
            return false;
        }
    }

    public function remove_old_emails($before_date)
    {
        // get count for records older than date provided
        $this->db->select("emailque_id");
        $this->db->from("emailques");
        $this->db->where('updated_date < ', $before_date);
        $record_count = $this->db->count_all_results();

        // remove old records
        $this->db->trans_start();
        $this->db->where('updated_date < ', $before_date);
        $this->db->delete('emailques');
        $this->db->trans_complete();

        return $record_count;
    }

}
