<?php

class Role_model extends Admin_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("roles");
    }

    public function get_role_list() {

        $this->db->select("roles.*");
        $this->db->from("roles");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['role_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_role_list_per_user($id) {
        $this->db->select("role_id");
        $this->db->from("user_role");
        $this->db->where(["user_id" => $id]);
        $query = $this->db->get();


        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['role_id']] = $row['role_id'];
            }
            return $data;
        }
        return false;
    }
    
    public function set_user_role($user_id,$role_id) {
        $data = array(
            'user_id' => $user_id,
            'role_id' => $role_id,
        );

         return $this->db->insert('user_role', $data);
    }

    public function get_role_dropdown() {
        $this->db->select("role_id, role_name");
        $this->db->from("roles");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
//                $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['role_id']] = $row['role_name'];
            }
//                return array_slice($data, 0, 500, true);
            return $data;
        }
        return false;
    }

    public function get_role_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where('roles', array('role_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_role($action, $id) {
        $data = array(
            'role_name' => $this->input->post('role_name'),
            'role_status' => $this->input->post('role_status'),
        );

        switch ($action) {
            case "add":
                return $this->db->insert('roles', $data);
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                return $this->db->update('roles', $data, array('role_id' => $id));

            default:
                show_404();
                break;
        }
    }

    public function remove_role($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('roles', array('role_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
