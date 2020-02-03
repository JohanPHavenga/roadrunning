<?php

class Edition_model extends MY_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("editions");
    }

    public function get_edition_field_array() {
        $fields = $this->db->list_fields("editions");
        foreach ($fields as $field) {
            $data[$field] = "";
        }
        return $data;
    }

    public function get_edition_id_from_name($edition_name) {
        // CHECK Editions table vir die naame
        $this->db->select("edition_id, edition_name, edition_status");
        $this->db->from("editions");
        $this->db->where("edition_name", $edition_name);
//            $this->db->where("REPLACE(edition_name, '\'', '')='$edition_name'"); // fix vir as daar 'n ' in die naam is
//            $this->db->or_where("REPLACE(edition_name, '/', ' ')=$edition_name`"); // fix vir as daar 'n / in die naam is
//            echo $this->db->get_compiled_select(); exit();
        $editions_query = $this->db->get();


        // CHECK Editions_Past vir as die naam van die edition verander
        $this->db->select("edition_id");
        $this->db->from("editions_past");
        $this->db->where("edition_name", $edition_name);
//            $this->db->where("REPLACE(edition_name, '\'', '')=`$edition_name`"); // fix vir as daar 'n ' in die naam is
//            $this->db->or_where("REPLACE(edition_name, '/', ' ')='$edition_name'"); // fix vir as daar 'n / in die naam is
//            echo $this->db->get_compiled_select();   exit();

        $editions_past_query = $this->db->get();


        if ($editions_query->num_rows() > 0) {
            $result = $editions_query->result_array();
            return $result[0];
        } elseif ($editions_past_query->num_rows() > 0) {
            $result = $editions_past_query->result_array();
            $result[0]['edition_name'] = $this->get_edition_name_from_id($result[0]['edition_id']);
            return $result[0];
        } else {
            return false;
        }
    }

    public function get_edition_id_from_slug($edition_slug) {
        // CHECK Editions table vir die naame
        $this->db->select("edition_id, edition_name, edition_status");
        $this->db->from("editions");
        $this->db->where("edition_slug", $edition_slug);
        $editions_query = $this->db->get();

        // CHECK Editions_Past vir as die naam van die edition verander
        $this->db->select("edition_id, edition_name");
        $this->db->from("editions_past");
        $this->db->where("edition_slug", $edition_slug);
        $editions_past_query = $this->db->get();

        if ($editions_query->num_rows() > 0) {
            $result = $editions_query->result_array();
            $result[0]['source'] = "org";
            return $result[0];
        } elseif ($editions_past_query->num_rows() > 0) {
            $result = $editions_past_query->result_array();
            $result[0]['source'] = "past";
            return $result[0];
        } else {
            return false;
        }
    }

    public function get_edition_slug($edition_id) {
        // CHECK Editions table vir die naame
        $this->db->select("edition_slug");
        $this->db->from("editions");
        $this->db->where("edition_id", $edition_id);
        $editions_query = $this->db->get();
        if ($editions_query->num_rows() > 0) {
            $result = $editions_query->result_array();
            return $result[0]['edition_slug'];
        } else {
            return false;
        }
    }

    public function get_edition_name_from_id($edition_id) {
        // CHECK Editions table vir die naame
        $this->db->select("edition_name");
        $this->db->from("editions");
        $this->db->where("edition_id", $edition_id);
        $editions_query = $this->db->get();
        if ($editions_query->num_rows() > 0) {
            $result = $editions_query->result_array();
            return $result[0]['edition_name'];
        } else {
            return false;
        }
    }

    public function get_edition_url_from_id($edition_id) {
        // CHECK Editions table vir die naame
        $this->db->select("edition_name");
        $this->db->from("editions");
        $this->db->where("edition_id", $edition_id);
        $editions_query = $this->db->get();
        if ($editions_query->num_rows() > 0) {
            $result = $editions_query->result_array();
            $e_name = $result[0]['edition_name'];
            $return = [
                'edition_id' => $edition_id,
                'edition_name' => $e_name,
                'edition_url' => base_url() . "event/" . encode_edition_name($e_name)
            ];
            return $return;
        } else {
            return false;
        }
    }

    public function get_edition_list() {
        $this->db->select("editions.*, event_name, asa_member_abbr");
        $this->db->from("editions");
        $this->db->join('events', 'events.event_id=editions.event_id', 'left');
        $this->db->join('edition_asa_member', 'edition_id', 'left');
        $this->db->join('asa_members', 'asa_member_id', 'left');

        $this->db->order_by('editions.edition_date', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['edition_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_edition_list_new($query_params = [], $field_arr = NULL) {
        if (is_null($field_arr)) {
            $field_arr = [
                "edition_id", "edition_name", "edition_date", "edition_slug", "editions.created_date", "editions.updated_date",
                "club_name","user_email","events.event_id", "event_name", "asa_member_id AS edition_asa_member",
                "towns.town_id","town_name","regions.region_id", "region_name","provinces.province_id","province_name",
            ];
        }
        $select = implode(",", $field_arr);
        $this->db->select($select);
        $this->db->from("editions");
        $this->db->join('events', 'event_id');
        $this->db->join('organising_club', 'event_id', 'left');
        $this->db->join('clubs', 'club_id', 'left');
        $this->db->join('edition_user', 'edition_id', 'left');
        $this->db->join('users', 'user_id', 'left');
        $this->db->join('edition_asa_member', 'edition_id', 'left');
        $this->db->join('asa_members', 'asa_member_id', 'left');
        $this->db->join('towns', 'events.town_id=towns.town_id');
        $this->db->join('regions', 'region_id');
        $this->db->join('provinces', 'regions.province_id=provinces.province_id');
        foreach ($query_params as $operator => $clause_arr) {
            if (is_array($clause_arr)) {
                foreach ($clause_arr as $field => $value) {
                    $this->db->$operator($field, $value);
                }
            } else {
                $this->db->$operator($clause_arr);
            }
        }
        if (!isset($query_params['order_by'])) {
            $this->db->order_by('edition_date', 'ASC');
        }

//        die($this->db->get_compiled_select());
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['edition_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_edition_dropdown($use_names = false) {
        $this->db->select("edition_id, edition_name");
        $this->db->from("editions");
        $this->db->order_by("edition_name");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            if ($use_names) {
                $data["General Query"] = "No event selected";
                foreach ($query->result_array() as $row) {
                    $data[$row['edition_name']] = $row['edition_name'];
                }
            } else {
                $data[] = "Please Select";
                foreach ($query->result_array() as $row) {
                    $data[$row['edition_id']] = $row['edition_name'];
                }
            }
            return $data;
        }
        return false;
    }

    public function get_edition_dropdown_abbr() {
        $this->db->select("edition_id, edition_name");
        $this->db->from("editions");
        $this->db->where("edition_date > ", date("Y-m-d", strtotime("4 months ago")));
        $this->db->where("edition_date < ", date("Y-m-d", strtotime("+9 month")));
        $this->db->order_by("edition_name");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['edition_id']] = $row['edition_name'];
            }
            return $data;
        }
        return false;
    }

    public function get_edition_list_simple() {
        $data[0] = "All Editions";
        $this->db->select("edition_id, edition_name");
        $this->db->from("editions");
        $this->db->order_by("edition_id");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['edition_id']] = $row['edition_name'];
            }
        }
        return $data;
    }

    public function get_edition_detail($id) {
        if (!($id)) {
            return false;
        } else {
//            $this->db->select("editions.*, sponsor_id, users.user_id, user_name, user_surname, user_email, asa_member_id AS edition_asa_member, event_name, town_name, club_name");
//            $this->db->from("editions");
//            $this->db->join('edition_sponsor', 'edition_id', 'left');
//            $this->db->join('events', 'event_id', 'left');
//            $this->db->join('towns', 'town_id', 'left');
//            $this->db->join('organising_club', 'event_id', 'left');
//            $this->db->join('clubs', 'club_id', 'left');
//            $this->db->join('edition_user', 'edition_id', 'left');
//            $this->db->join('users', 'user_id', 'left');
//            $this->db->join('edition_asa_member', 'edition_id', 'left');
//            $this->db->where('edition_id', $id);
//            $query = $this->db->get();

            $this->db->select("*, asa_member_id AS edition_asa_member");
            $this->db->from("editions");
            $this->db->join('events', 'event_id');
            $this->db->join('towns', 'town_id');
            $this->db->join('regions', 'region_id');
            $this->db->join('provinces', 'regions.province_id=provinces.province_id');
            $this->db->join('organising_club', 'event_id', 'left');
            $this->db->join('clubs', 'club_id', 'left');
            $this->db->join('edition_user', 'edition_id', 'left');
            $this->db->join('users', 'user_id', 'left');
            $this->db->join('edition_asa_member', 'edition_id', 'left');
            $this->db->join('asa_members', 'asa_member_id', 'left');
            $this->db->where('edition_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function get_edition_detail_lite($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("events.event_name,"
                    . "editions.edition_name, "
                    . "editions.edition_date, "
                    . "towns.town_name");
            $this->db->from("editions");
            $this->db->join('events', 'events.event_id=editions.event_id', 'left');
            $this->db->join('towns', 'towns.town_id=events.town_id', 'left');
            $this->db->where('editions.edition_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function get_edition_detail_full($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("events.*,editions.*, sponsors.*, clubs.club_id, club_name, users.user_email, towns.town_name, asa_members.*");
            $this->db->from("editions");
            $this->db->join('events', 'events.event_id=editions.event_id', 'left');
            $this->db->join('organising_club', 'events.event_id=organising_club.event_id', 'left');
            $this->db->join('clubs', 'organising_club.club_id=clubs.club_id', 'left');
            $this->db->join('edition_user', 'editions.edition_id=edition_user.edition_id', 'left');
            $this->db->join('users', 'users.user_id=edition_user.user_id', 'left');
            $this->db->join('edition_sponsor', 'editions.edition_id=edition_sponsor.edition_id', 'left');
            $this->db->join('edition_asa_member', 'editions.edition_id=edition_asa_member.edition_id', 'left');
            $this->db->join('asa_members', 'edition_asa_member.asa_member_id=asa_members.asa_member_id', 'left');
            $this->db->join('sponsors', 'sponsors.sponsor_id=edition_sponsor.sponsor_id', 'left');
            $this->db->join('towns', 'towns.town_id=events.town_id', 'left');
            $this->db->where('editions.edition_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_edition($action, $edition_id, $edition_data = [], $debug = false) {

        // POSTED DATA        
        if (empty($edition_data)) {
            // get field array from editions table and loop through the fields to see if post field is set
            $field_array = $this->get_edition_field_array();
            foreach ($field_array as $field => $dud) {
                if ($this->input->post($field) !== NULL) {
                    switch ($field) {
                        case "edition_address_end":
                            if (empty($this->input->post($field))) {
                                $edition_data[$field] = $this->input->post('edition_address');
                            } else {
                                $edition_data[$field] = $this->input->post($field);
                            }
                            break;
                        default:
                            $edition_data[$field] = $this->input->post($field);
                            break;
                    }
                }
            }
            // add slug
            $edition_data['edition_slug'] = url_title($this->input->post('edition_name'));

            // edition sponsor
            $edition_sponsor_data = ["edition_id" => $edition_id, "sponsor_id" => $this->input->post('sponsor_id')];
            // edition entrytype
            $edition_entrytype_data = ["edition_id" => $edition_id, "entrytype_id" => $this->input->post('entrytype_id')];
            // edition regtype
            $edition_regtype_data = ["edition_id" => $edition_id, "regtype_id" => $this->input->post('regtype_id')];
            // edition user
            $edition_user_data = ["edition_id" => $edition_id, "user_id" => $this->input->post('user_id')];
            // ASA member
            if ($this->input->post('edition_asa_member') > 0) {
                $edition_asamember_data = ["edition_id" => $edition_id, "asa_member_id" => $this->input->post('edition_asa_member')];
            }
        } else {

            $edition_sponsor_data = ["edition_id" => $edition_id, "sponsor_id" => [4]];
            $edition_entrytype_data = ["edition_id" => $edition_id, "entrytype_id" => [5]];
            $edition_regtype_data = ["edition_id" => $edition_id, "regtype_id" => [3]];
            // check if user_id is sent;
            if (@$edition_data['user_id']) {
                $user_id = $edition_data['user_id'];
                unset($edition_data['user_id']);
            } else {
                $user_id = 19;
            }
            $edition_user_data = ["edition_id" => $edition_id, "user_id" => $user_id];
            // Status check
            if (!isset($edition_data['edition_status'])) {
                $edition_data['edition_status'] = 1;
            }
            // check if asa_memberdata exists
            if (array_key_exists("edition_asa_member", $edition_data)) {
                if ($edition_data['edition_asa_member'] > 0) {
                    $edition_asamember_data = ["edition_id" => $edition_id, "asa_member_id" => $edition_data['edition_asa_member']];
                }
                unset($edition_data['edition_asa_member']);
            }
        }

        if ($debug) {
            echo "<b>Edition Transaction</b>";
            wts($_POST);
            wts($action);
            wts($edition_id);
            wts($edition_data);
            wts($edition_sponsor_data);
            die();
        } else {

            switch ($action) {
                case "add":
                    $this->db->trans_start();
                    $this->db->insert('editions', $edition_data);
                    // get edition ID from Insert
                    $edition_id = $this->db->insert_id();
                    // update sponser array
                    foreach ($edition_sponsor_data['sponsor_id'] as $sponsor_id) {
                        $insert_array = [
                            "edition_id" => $edition_id,
                            "sponsor_id" => $sponsor_id,
                        ];
                        $this->db->insert('edition_sponsor', $insert_array);
                    }
                    // update entrytype array
                    foreach ($edition_entrytype_data['entrytype_id'] as $entrytype_id) {
                        $insert_array = [
                            "edition_id" => $edition_id,
                            "entrytype_id" => $entrytype_id,
                        ];
                        $this->db->insert('edition_entrytype', $insert_array);
                    }
                    // update regtype array
                    foreach ($edition_regtype_data['regtype_id'] as $regtype_id) {
                        $insert_array = [
                            "edition_id" => $edition_id,
                            "regtype_id" => $regtype_id,
                        ];
                        $this->db->insert('edition_regtype', $insert_array);
                    }
                    // update user array
                    $edition_user_data["edition_id"] = $edition_id;
                    $this->db->insert('edition_user', $edition_user_data);
                    // update asamember array
                    if (@$edition_asamember_data) {
                        $edition_asamember_data["edition_id"] = $edition_id;
                        $this->db->insert('edition_asa_member', $edition_asamember_data);
                    }
                    $this->db->trans_complete();
                    break;
                case "edit":
                    // add updated date to both data arrays
                    $edition_data['updated_date'] = date("Y-m-d H:i:s");

                    // start SQL transaction
                    $this->db->trans_start();
                    // EDITION SPONSOR UPDATE
                    $this->db->delete('edition_sponsor', array('edition_id' => $edition_id));
                    foreach ($edition_sponsor_data['sponsor_id'] as $sponsor_id) {
                        $insert_array = [
                            "edition_id" => $edition_id,
                            "sponsor_id" => $sponsor_id,
                        ];
                        $this->db->insert('edition_sponsor', $insert_array);
                    }
                    // EDITION ENTRYTYPE UPDATE
                    $this->db->delete('edition_entrytype', array('edition_id' => $edition_id));
                    foreach ($edition_entrytype_data['entrytype_id'] as $entrytype_id) {
                        $insert_array = [
                            "edition_id" => $edition_id,
                            "entrytype_id" => $entrytype_id,
                        ];
                        $this->db->insert('edition_entrytype', $insert_array);
                    }
                    // EDITION REGTYPE UPDATE
                    $this->db->delete('edition_regtype', array('edition_id' => $edition_id));
                    foreach ($edition_regtype_data['regtype_id'] as $regtype_id) {
                        $insert_array = [
                            "edition_id" => $edition_id,
                            "regtype_id" => $regtype_id,
                        ];
                        $this->db->insert('edition_regtype', $insert_array);
                    }
                    // EDITION USER CHECK
                    // check if record already exists
                    $item_exists = $this->db->get_where('edition_user', array('edition_id' => $edition_id, 'user_id' => $this->input->post('user_id')));
                    if ($item_exists->num_rows() == 0) {
                        $edition_user_data['updated_date'] = date("Y-m-d H:i:s");
                        $this->db->delete('edition_user', array('edition_id' => $edition_id));
                        $this->db->insert('edition_user', $edition_user_data);
                    }
                    // EDITION ASA MEMBER CHECK
                    // check if record already exists
                    $item_exists = $this->db->get_where('edition_asa_member', array('edition_id' => $edition_id, 'asa_member_id' => $this->input->post('edition_asa_member')));
                    if ($item_exists->num_rows() == 0) {
                        $edition_user_data['updated_date'] = date("Y-m-d H:i:s");
                        $this->db->delete('edition_asa_member', array('edition_id' => $edition_id));
                        if (@$edition_asamember_data) {
                            $this->db->insert('edition_asa_member', $edition_asamember_data);
                        }
                    }
                    // UPDATE ACTUAL EDITIONS TABLE
                    $this->db->update('editions', $edition_data, array('edition_id' => $edition_id));
                    $this->db->trans_complete();


                    // check of die naam van die edition verander het, indien wel, kryf na editions_past

                    if ($this->input->post('edition_name_past') != $this->input->post('edition_name')) {
                        $this->db->trans_start();
                        $this->db->insert('editions_past', [
                            "edition_id" => $edition_id,
                            "edition_name" => $this->input->post('edition_name_past'),
                            "edition_slug" => url_title($this->input->post('edition_name_past'))
                        ]);
                        $this->db->trans_complete();
                    }


                    break;
                default:
                    show_404();
                    break;
            }
            // return ID if transaction successfull
            if ($this->db->trans_status()) {
                return $edition_id;
            } else {
                return false;
            }
        }
    }

    public function update_field($e_id, $field, $value) {
        if (!($e_id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->update('editions', [$field => $value], array('edition_id' => $e_id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function remove_edition($id) {
        if (!($id)) {
            return false;
        } else {
            // only edition needed, SQL key constraints used to remove records from organizing_club
            $this->db->trans_start();
            $this->db->delete('editions', array('edition_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function get_timeperiod() {
        $this->db->select("edition_date");
        $this->db->from("editions");
        $this->db->group_by("YEAR(edition_date)");
        $this->db->group_by("MONTH(edition_date)");
        $this->db->order_by("edition_date", "DESC");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[date("Y-m", strtotime($row->edition_date))] = date("F Y", strtotime($row->edition_date));
            }

            return $data;
        }
        return [];
    }

}
