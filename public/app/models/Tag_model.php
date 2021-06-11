<?php

class Tag_model extends Frontend_model {

    public $table = "tags";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("tags");
    }

    public function get_tag_field_array() {
        $fields = $this->db->list_fields($this->table);
        foreach ($fields as $field) {
            $data[$field] = "";
        }
        return $data;
    }

    public function exists($tag_name) {
        $this->db->select("tag_id");
        $this->db->from("tags");
        $this->db->where("tag_name", $tag_name);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result[0]['tag_id'];
        }
        return false;
    }

    public function get_tag_list() {
        $this->db->select("tags.*");
        $this->db->from("tags");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['tag_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_tag_dropdown() {
        $this->db->select("tag_id, tag_name");
        $this->db->from("tags");
        $this->db->where("tag_status", true);
        $this->db->order_by("tag_name");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['tag_id']] = $row['tag_name'];
            }
            return $data;
        }
        return false;
    }

    public function get_tag_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where($this->table, array('tag_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_tag($action, $id = 0, $data = []) {
        if (empty($data)) {
            $data = array(
                'tag_name' => $this->input->post('tag_name'),
                'tagtype_id' => $this->input->post('tagtype_id'),
                'tag_status' => $this->input->post('tag_status'),
            );
        }

        switch ($action) {
            case "add":
                $this->db->insert($this->table, $data);
                $id = $this->db->insert_id();
                break;
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                $this->db->update($this->table, $data, array('tag_id' => $id));
                break;
            default:
                show_404();
                break;
        }
        return $id;
    }

    public function remove_tag($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete($this->table, array('tag_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

    public function get_edition_tag_list($edition_id = null) {
        if (!$edition_id) {
            return false;
        } else {
            $data=[];
        }
        $this->db->select("tag_id, tag_name, tagtype_name");
        $this->db->from("edition_tag");
        $this->db->join("tags", "tag_id");
        $this->db->join("tagtypes", "tagtype_id", "left");
        $this->db->where("tag_status", 1);
        $this->db->where("tagtype_status", 1);
        $this->db->where("edition_id", $edition_id);
//        $this->db->order_by("tag_name");

        $query = $this->db->get();
//        $query = $this->db->get_where('edition_tag', array('edition_id' => $edition_id));
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['tag_id']] = $row;
            }
        }
        return $data;
    }
    
    public function clear_edition_tags($edition_id) {
        if (!$edition_id) {
            return false;
        }
        $this->db->trans_start();
        $this->db->delete('edition_tag', array('edition_id' => $edition_id));
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    public function set_edition_tag($edition_id, $tag_id) {
        if (!$edition_id) {
            return false;
        }
        $this->db->trans_start();
        $insert_array = [
            "edition_id" => $edition_id,
            "tag_id" => $tag_id,
        ];
        $id = $this->db->insert('edition_tag', $insert_array);
        $this->db->trans_complete();

        return $id;
    }

}
