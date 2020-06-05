<?php

class User_model extends Frontend_model {

    public function __construct() {
        parent::__construct();
    }

    public function record_count() {
        return $this->db->count_all("users");
    }
    
    public function exists($email) {
        $this->db->select("user_id");
        $this->db->from("users");
        $this->db->where("user_email", $email);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result[0]['user_id'];
        } 
        return false;
    }

    public function get_user_id($email, $create=[]) {
        $this->db->select("user_id");
        $this->db->from("users");
        $this->db->where('user_email', $email);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data = $row['user_id'];
            }
            return $data;
        } elseif (!empty($create)) {
            $params=[
                "action"=>"add",
                "user_data"=>$create,
                "role_arr"=>[3],                
            ];
            return($this->set_user($params));
        }
        return false;
    }

    public function get_user_list($att) {

        $this->db->select("*");
        $this->db->from("users");
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

    public function get_user_dropdown($att) {
        $this->db->select("user_id, user_name, user_surname");
        $this->db->from("users");
        if (isset($att['role_id'])) {
            $this->db->join('user_role', 'user_id', 'left');
            $this->db->where("role_id", $role_id);
        }
        if (isset($att['user_arr'])) {
            $this->db->where_in("user_id", $user_arr);
        }
        $this->db->order_by('user_name', 'user_surname');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['user_id']] = $row['user_name'] . " " . $row['user_surname'];
            }
            return $data;
        }
        return false;
    }

    public function get_user_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("users.*, club_name");
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

    public function set_user($params) {
        // $action, $user_data=[], $role_arr=[], $user_id=0, $debug=FALSE
        
        $user_data = $params['user_data'];
        $user_data['updated_date'] = date("Y-m-d H:i:s");
        switch ($params['action']) {
            case "add":
                $this->db->trans_start();
                $this->db->insert('users', $user_data);
                // get event ID from Insert
                $user_id = $this->db->insert_id();
                // insert role
                foreach ($params['role_arr'] as $role_id) {
                    $this->db->insert('user_role', ["user_id" => $user_id, "role_id" => $role_id]);
                }
                $this->db->trans_complete();
                break;

            case "edit":
                // get ID for ease of use
                $user_id = $user_data['user_id'];
                
                //check of password wat gepost is alreeds gehash is
//                if ($this->check_password($user_data['user_password'], $user_id)) {
//                    unset($user_data['user_password']);
//                }

                // start SQL transaction
                $this->db->trans_start();
                $this->db->update('users', $user_data, array('user_id' => $user_id));
                // delete uit user_role
                $this->db->where('user_id', $user_id);
                $this->db->delete('user_role');
                // add nuwe entries
                foreach ($params['role_arr'] as $role_id) {
                    $this->db->insert('user_role', ["user_id" => $user_id, "role_id" => $role_id]);
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

    public function update_user_field($user_data, $user_id) {
        $user_data['updated_date'] = date("Y-m-d H:i:s");
        $this->db->trans_start();
        $this->db->update('users', $user_data, ['user_id' => $user_id]);
        $this->db->trans_complete();

        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return true;
        } else {
            return false;
        }
    }

    public function check_credentials($email, $password) {
        $user_data = array(
            'user_email' => $email,
//            'user_password' => hash_pass($password),
        );

        $this->db->select('*');
        $this->db->from("users");
        $this->db->where($user_data);
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

    public function check_user_is_confirmed($user_id) {
        $this->db->select('user_id');
        $this->db->from("users");
        $this->db->where('user_id', $user_id);
        $this->db->where('user_isconfirmed', true);
        $query = $this->db->get();

        // mag net een user kry
        if ($query->num_rows() == 1) {
            return true;
        }
        return false;
    }

    public function check_user_guid($guid) {
        $this->db->select('user_id');
        $this->db->from("users");
        $this->db->where('user_confirm_guid', $guid);
        $this->db->where('user_guid_expire > ', date("Y-m-d H:i:s"));
        $query = $this->db->get();

        // mag net een user kry
        if ($query->num_rows() == 1) {
            $row = $query->row();
            return $row->user_id;
        }
        return false;
    }

    public function check_email($email) {
        $this->db->where('user_email', $email);
        $this->db->from('users');
        return $this->db->count_all_results();
    }

    public function export() {
        $this->db->select("users.user_id, user_name, user_surname, user_username, club_id");
        $this->db->from("users");
        return $query = $this->db->get();
    }

    private function check_password($password, $id) {
        $this->db->where('user_password', $password);
        $this->db->where('user_id', $id);
        $this->db->from('users');
        return $this->db->count_all_results();
    }
    

}
