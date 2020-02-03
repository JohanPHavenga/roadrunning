<?php

class Emailtemplate_model extends Admin_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("emailtemplates");
    }

    public function get_emailtemplate_list($top = 0) {

        $this->db->select("emailtemplates.*");
        $this->db->from("emailtemplates");
        if ($top > 0) {
            $this->db->limit($top);
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['emailtemplate_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_emailtemplate_detail($id) {

        $this->db->select("emailtemplates.*");
        $this->db->from("emailtemplates");
        $this->db->where("emailtemplate_id", $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    public function set_emailtemplate($action, $emailtemplate_id, $data) {
        switch ($action) {
            case "add":
                $this->db->trans_start();
                $this->db->insert('emailtemplates', $data);
                // get edition ID from Insert
                $emailtemplate_id = $this->db->insert_id();
                $this->db->trans_complete();
                break;
            case "edit":
                $this->db->trans_start();
                $data['updated_date'] = date("Y-m-d H:i:s");
                $this->db->update('emailtemplates', $data, array('emailtemplate_id' => $emailtemplate_id));
                $this->db->trans_complete();
                break;
            default:
                die("Action not set");
                break;
        }
        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return $emailtemplate_id;
        } else {
            return false;
        }
    }

    public function remove_emailtemplate($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('emailtemplates', array('emailtemplate_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function copy_template($id) {
        /* generate the select query */
        $this->db->where('emailtemplate_id', $id);
        $query = $this->db->get('emailtemplates');

        foreach ($query->result() as $row) {
            foreach ($row as $key => $val) {
                if ($key != 'emailtemplate_id') {
                    /* $this->db->set can be used instead of passing a data array directly to the insert or update functions */
                    $this->db->set($key, $val);
                }//endif              
            }//endforeach
        }//endforeach

        /* insert the new record into table */
        $this->db->trans_start();
        $this->db->insert('emailtemplates');
        $edition_id = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            return $edition_id;
        } else {
            return false;
        }
    }

    public function get_emailtemplate_dropdown() {
        $this->db->select("emailtemplate_id, emailtemplate_name, emailtemplate_linked_to");
        $this->db->from("emailtemplates");
        $this->db->order_by("emailtemplate_linked_to, emailtemplate_name");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[0] = "None";
            foreach ($query->result_array() as $row) {
                $data[$row['emailtemplate_id']] = ucfirst($row['emailtemplate_linked_to'])." | ".$row['emailtemplate_name'];
            }
//                return array_slice($data, 0, 500, true);
            return $data;
        }
        return false;
    }

}
