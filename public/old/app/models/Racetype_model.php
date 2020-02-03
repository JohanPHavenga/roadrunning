<?php

class Racetype_model extends MY_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("racetypes");
    }

    public function get_racetype_list($limit = 100, $start = 0) {
        $this->db->limit($limit, $start);

        $this->db->select("racetypes.*");
        $this->db->from("racetypes");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['racetype_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_racetype_dropdown() {
        $this->db->select("racetype_id, racetype_name");
        $this->db->from("racetypes");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['racetype_id']] = $row['racetype_name'];
            }
//                return array_slice($data, 0, 500, true);
            return $data;
        }
        return false;
    }

    public function get_racetype_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where('racetypes', array('racetype_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_racetype($action, $id) {
        $data = array(
            'racetype_name' => $this->input->post('racetype_name'),
            'racetype_abbr' => $this->input->post('racetype_abbr'),
            'racetype_status' => $this->input->post('racetype_status'),
//            'racetype_color' => $this->input->post('racetype_color'),
            'racetype_icon' => $this->input->post('racetype_icon'),
        );

        switch ($action) {
            case "add":
                return $this->db->insert('racetypes', $data);
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                return $this->db->update('racetypes', $data, array('racetype_id' => $id));

            default:
                show_404();
                break;
        }
    }

    public function remove_racetype($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('racetypes', array('racetype_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
