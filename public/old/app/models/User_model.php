<?php

class User_model extends MY_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function hash_pass($password) {
        if ($password) {
            return sha1($password . "37");
        } else {
            return NULL;
        }
    }

    public function record_count() {
        return $this->db->count_all("users");
    }

    public function get_user_id($email) {
        $this->db->select("user_id");
        $this->db->from("users");
        $this->db->where('user_email', $email);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data = $row['user_id'];
            }
            return $data;
        }
        return false;
    }

    public function get_user_list($limit = NULL, $start = NULL) {
        if (isset($limit) && isset($start)) {
            $this->db->limit($limit, $start);
        }

        $this->db->select("users.*, club_name");
        $this->db->from("users");
        $this->db->join('clubs', 'club_id');
        $this->db->order_by('user_name', 'user_surname');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['user_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_user_dropdown($role_id = NULL, $user_arr = []) {
        $this->db->select("user_id, user_name, user_surname");
        $this->db->from("users");
        if (isset($role_id)) {
            $this->db->join('user_role', 'user_id', 'left');
            $this->db->where("role_id", $role_id);
        }
        if ($user_arr) {
            $this->db->where_in("user_id", $user_arr);
        }
        $this->db->order_by('user_name', 'user_surname');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
//            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['user_id']] = $row['user_name'] . " " . $row['user_surname'];
            }
            move_to_top($data, 60);
            return $data;
        }
        return false;
    }

    public function get_user_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("users.*, club_id");
            $this->db->from("users");
            $this->db->join('clubs', 'club_id', 'left');
            $this->db->where('user_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function get_user_name($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("user_id, user_name, user_surname, user_email");
            $this->db->from("users");
            $this->db->where('user_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_user($action, $user_id, $user_data = [], $debug = FALSE) {
        $role_arr = [];
//        wts($user_data); die();
        // POSTED DATA
        if (empty($user_data)) {
            $user_data = array(
                'user_name' => $this->input->post('user_name'),
                'user_surname' => $this->input->post('user_surname'),
                'user_email' => $this->input->post('user_email'),
                'user_contact' => $this->int_phone($this->input->post('user_contact')),
//                'user_username' => $this->input->post('user_username'),
//                'user_password' => $this->hash_pass($this->input->post('user_password')),
                'club_id' => $this->input->post('club_id'),
            );
            $role_arr = $this->input->post('role_id');
        } else {
            // way to pass that the user role
            if ($user_data['role_arr']) {
                $role_arr = $user_data['role_arr'];
                unset($user_data['role_arr']);
            }
//            if (isset($user_data['user_password'])) {
//                $user_data['user_password'] = $this->hash_pass($user_data['user_password']);
//            }
        }

        $user_data['updated_date'] = date("Y-m-d H:i:s");
        switch ($action) {
            case "add":
                $this->db->trans_start();
                $this->db->insert('users', $user_data);
                // get event ID from Insert
                $user_id = $this->db->insert_id();

                // update data array
                if (empty($role_arr)) {
                    $role_arr = [3];
                } // set role_arr to 'contact' for new users
                foreach ($role_arr as $role_id) {
                    $this->db->insert('user_role', ["user_id" => $user_id, "role_id" => $role_id]);
                }

                $this->db->trans_complete();
                break;

            case "edit":
                // add updated date to both data arrays
                $user_data['updated_date'] = date("Y-m-d H:i:s");
                //check of password wat gepost is alreeds gehash is
//                if (@$this->check_password($this->input->post('user_password'), $user_id)) {
//                    unset($user_data['user_password']);
//                }
                // start SQL transaction
                $this->db->trans_start();
                $this->db->update('users', $user_data, array('user_id' => $user_id));


                if ($role_arr) {
                    // delete uit user_role
                    $this->db->where('user_id', $user_id);
                    $this->db->delete('user_role');

                    // add nuwe entries
                    foreach ($role_arr as $role_id) {
                        $this->db->insert('user_role', ["user_id" => $user_id, "role_id" => $role_id]);
                    }
                }

                $this->db->trans_complete();
                break;

            default:
                show_404();
                break;
        }
        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return $user_id;
        } else {
            return false;
        }
    }

    public function remove_user($id) {
        if (!($id)) {
            return false;
        } else {
            // only edition needed, SQL key constraints used to remove records from organizing_club
            $this->db->trans_start();
            $this->db->delete('users', array('user_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function check_login($login_type = "user") {
        $user_name = $this->input->post('user_username');
        $password = $this->input->post('user_password');
        $user_data = array(
            'user_username' => $user_name,
        );

        $this->db->select('users.user_id,user_name,user_surname,user_email,user_password,user_contact, role_id');
        $this->db->from("users");
        if ($login_type == "admin") {
            $this->db->join("user_role", "users.user_id=user_role.user_id");
            $user_data["role_id"] = 1;
        }
        $this->db->where($user_data);
//        echo $this->db->get_compiled_select();
        $query = $this->db->get();


        // mag net een user kry
        if ($query->num_rows() == 1) {
            foreach ($query->result_array() as $row) {
                if (password_verify($password, $row['user_password'])) {
                    unset($row['user_password']);
                    return $row;
                } else {
                    return false;
                }
            }
        }
        return false;
    }

//    public function check_login($login_type = "user") {
//        $user_data = array(
//            'user_username' => $this->input->post('user_username'),
//            'user_password' => $this->hash_pass($this->input->post('user_password')),
//        );
//
//        $this->db->select("users.user_id, user_name, user_surname, role_id");
//        $this->db->from("users");
//        if ($login_type == "admin") {
//            $this->db->join("user_role", "users.user_id=user_role.user_id");
//            $user_data["role_id"] = 1;
//        }
//        $this->db->where($user_data);
//        $query = $this->db->get();
//
//        if ($query->num_rows() > 0) {
//            return $query->row_array();
//        } else {
//            // nuwe metode
//            $this->db->select('user_id,user_name,user_surname,user_email,user_password,user_contact');
//            $this->db->from("users");
//            $this->db->where('user_username', $this->input->post('user_username'));
//            $query = $this->db->get();
//
//            // mag net een user kry
//            if ($query->num_rows() == 1) {
//                foreach ($query->result_array() as $row) {
//                    if (password_verify($this->input->post('user_password'), $row['user_password'])) {
//                        unset($row['user_password']);
//                        return $row;
//                    } else {
//                        return false;
//                    }
//                }
//            }
//        }
//        return false;
//    }

    private function check_password($password, $id) {
        $this->db->where('user_password', $password);
        $this->db->where('user_id', $id);
        $this->db->from('users');
        return $this->db->count_all_results();
    }

    public function export() {
        $this->db->select("users.user_id, user_name, user_surname, user_username, club_id");
        $this->db->from("users");
        return $query = $this->db->get();
    }

    private function int_phone($phone) {
        if ($phone) {
            $phone = trim($phone);
            $phone = str_replace(" ", "", $phone);
            $phone = str_replace("-", "", $phone);
            return preg_replace('/^(?:\+?27|0)?/', '+27', $phone);
        } else {
            return false;
        }
    }

}
