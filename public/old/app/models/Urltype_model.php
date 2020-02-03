<?php

class Urltype_model extends MY_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("urltypes");
    }

    public function get_urltype_list() {
        $this->db->select("urltypes.*");
        $this->db->from("urltypes");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['urltype_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_urltype_dropdown() {
        $this->db->select("urltype_id, urltype_name");
        $this->db->from("urltypes");
        $this->db->order_by("urltype_name");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['urltype_id']] = $row['urltype_name'];
            }
            return $data;
        }
        return false;
    }

    public function get_urltype_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where('urltypes', array('urltype_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_urltype($action, $id) {
        $data = array(
            'urltype_name' => $this->input->post('urltype_name'),
            'urltype_helptext' => $this->input->post('urltype_helptext'),
            'urltype_buttontext' => $this->input->post('urltype_buttontext'),
        );
        
        switch ($action) {
            case "add":
                return $this->db->insert('urltypes', $data);
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                return $this->db->update('urltypes', $data, array('urltype_id' => $id));

            default:
                show_404();
                break;
        }
    }

    public function remove_urltype($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('urltypes', array('urltype_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
