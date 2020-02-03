<?php

class Filetype_model extends MY_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("filetypes");
    }

    public function get_filetype_list() {
        $this->db->select("filetypes.*");
        $this->db->from("filetypes");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['filetype_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_filetype_dropdown() {
        $this->db->select("filetype_id, filetype_name");
        $this->db->from("filetypes");
        $this->db->order_by("filetype_name");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['filetype_id']] = $row['filetype_name'];
            }
            return $data;
        }
        return false;
    }

    public function get_filetype_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where('filetypes', array('filetype_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_filetype($action, $id) {
        $data = array(
            'filetype_name' => $this->input->post('filetype_name'),
            'filetype_helptext' => $this->input->post('filetype_helptext'),
            'filetype_buttontext' => $this->input->post('filetype_buttontext'),
        );

        switch ($action) {
            case "add":
                return $this->db->insert('filetypes', $data);
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                return $this->db->update('filetypes', $data, array('filetype_id' => $id));

            default:
                show_404();
                break;
        }
    }

    public function remove_filetype($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('filetypes', array('filetype_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
