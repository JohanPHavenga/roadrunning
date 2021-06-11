<?php

class MY_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_race_color($distance) {

        switch (true) {
            case $distance <= 9:
                $color = 'danger';
                break;

            case $distance == 10:
                $color = 'warning';
                break;

            case $distance <= 21:
                $color = 'secondary';
                break;

            case $distance == 21.1:
                $color = 'success';
                break;

            case $distance <= 42:
                $color = 'info';
                break;

            case $distance == 42.2:
                $color = 'primary';
                break;

            default:
                $color = 'dark';
                break;
        }

        return $color;
    }

}
//------------------------------------------------------------------------------
//  FRONT END MODEL
//------------------------------------------------------------------------------
class Frontend_model extends MY_model {

    function __construct() {
        parent::__construct();
    }

    // new way to pull status list using the status_
    public function get_status_name($status_id) {
        $this->db->select("status_name");
        $this->db->from("status");
        $this->db->where('status_id', $status_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data = $row['status_name'];
            }
            return $data;
        }
        return false;
    }

    public function get_edition_img_url($edition_id, $slug) {
        $file_detail = $this->file_exists("edition", $edition_id, 1);
        if ($file_detail) {
            return base_url("file/edition/" . $slug . "/logo/" . $file_detail['file_name']);
        } else {
            $num = rand(1, 21);
            if ($num < 10) {
                $num = "0{$num}";
            }
            return base_url("assets/img/thumbs/$num.jpg");
        }
    }

    public function file_exists($linked_to, $linked_id, $filetype_id) {

        $this->db->select("*");
        $this->db->from("files");
        $this->db->where('file_linked_to', $linked_to);
        $this->db->where('linked_id', $linked_id);
        $this->db->where('filetype_id', $filetype_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result[0];
        }
        return false;
    }

    public function get_edition_entrytype_list($edition_id = null) {
        if (!$edition_id) {
            return false;
        }
        $query = $this->db->get_where('edition_entrytype', array('edition_id' => $edition_id));
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['entrytype_id']] = $row['entrytype_id'];
            }
        } else {
            //$data = [$this->no_info_id];
            $data=[];
        }
        return $data;
    }

    public function log_search($search_query) {
        $query = $this->db->get_where('searches', array('search_term' => $search_query));
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $search_id = $result[0]['search_id'];
            $search_data = [
                "search_count" => $result[0]['search_count'] + 1,
                "updated_date" => date("Y-m-d H:i:s"),
            ];

            $this->db->trans_start();
            $this->db->update('searches', $search_data, ['search_id' => $search_id]);
            $this->db->trans_complete();
        } else {
            $search_data = [
                "search_term" => $search_query,
                "search_count" => 1,
                "updated_date" => date("Y-m-d H:i:s"),
            ];
            $this->db->trans_start();
            $this->db->insert('searches', $search_data);
            // get event ID from Insert
            $search_id = $this->db->insert_id();
            $this->db->trans_complete();
        }

        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return $search_id;
        } else {
            return false;
        }
    }

    public function get_most_searched($query_params = []) {

        $this->db->select("*");
        $this->db->from("searches");
        foreach ($query_params as $operator => $clause_arr) {
            if (is_array($clause_arr)) {
                foreach ($clause_arr as $field => $value) {
                    $this->db->$operator($field, $value);
                }
            } else {
                $this->db->$operator($clause_arr);
            }
        }
//        die($this->db->get_compiled_select());
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['search_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function log_runtime($runtime_data) {
        return $this->db->insert('runtimes', $runtime_data);
    }
    
    public function runtime_log_cleanup($before_date) {
        // get count for records older than date provided
        $this->db->select("*");
        $this->db->from("runtimes");
        $this->db->where('runtime_end < ', $before_date);
//        die($this->db->get_compiled_select());
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $record_count = $query->num_rows();
        } else {
            $record_count = 0;
        }

        // remove old records
        $this->db->trans_start();
        $this->db->where('runtime_end < ', $before_date);
        $this->db->delete('runtimes');
        $this->db->trans_complete();

        return $record_count;
    }

}


//------------------------------------------------------------------------------
//  ADMIN MODEL
//------------------------------------------------------------------------------

class Admin_model extends MY_model {

    function __construct() {
        parent::__construct();
        // Load any front-end only dependencies
    }

    public function get_status_dropdown() {
        $this->db->select("*");
        $this->db->from("status");
        $this->db->limit(3);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['status_id']] = $row['status_name'];
            }
            return $data;
        }
        return false;
    }

    // new way to pull status list using the status_
    public function get_status_list($use = NULL) {
        $this->db->select("*");
        $this->db->from("status");
        if ($use) {
            $this->db->like('status_use', $use);
        }
        $this->db->order_by("status_order", "ASC");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['status_id']] = $row['status_name'];
            }
            return $data;
        }
        return false;
    }
    
    public function get_status_name($status_id) {
        $this->db->select("status_name");
        $this->db->from("status");
        $this->db->where("status_id", $status_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result[0]['status_name'];
        }
        return false;
    }

    public function get_linked_to_dropdown($count = 6, $start = 0) {
        $this->db->select("*");
        $this->db->from("linked_to");
        $this->db->limit($count, $start);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['linked_to_name']] = $row['linked_to_name'];
            }
            return $data;
        }
        return false;
    }

    public function get_linked_to_list($count = 6, $start = 0) {
        $this->db->select("*");
        $this->db->from("linked_to");
        $this->db->limit($count, $start);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['linked_to_id']] = $row['linked_to_name'];
            }
            return $data;
        }
        return false;
    }

    public function set_results_flag($linked_to, $linked_id, $flag) {
        $id_field = $linked_to . "_id";
        $table = $linked_to . "s";
        // new results status field also needs to be set
        if ($flag) {
            $status = 11;
            $this->db->trans_start();
            // nuwe edition_info_status field
            if ($linked_to == "edition") {
                $this->db->update($table, ["edition_info_status" => $status], array($id_field => $linked_id));
            }
            $this->db->trans_complete();
        } 
        
        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return true;
        } else {
            return false;
        }
    }

    public function log_runtime($runtime_data) {
        return $this->db->insert('runtimes', $runtime_data);
    }

}