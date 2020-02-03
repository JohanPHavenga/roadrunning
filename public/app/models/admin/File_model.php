<?php

class File_model extends Admin_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function set_file($params,$file_data=[]) {
        if (empty($file_data)) {
            // FIRST SEE IF FILE ALREADY EXISTS
            $file_id=$this->get_file_id($params);
            if ($file_id) {
                $action = "edit";
            } else {
                $action = "add";
            }
            // set array to be written to DB        
            $file_data = array(
                'file_name' => $params['data']['file_name'],
                'file_type' => $params['data']['file_type'],
                'file_ext' => $params['data']['file_ext'],
                'file_size' => $params['data']['file_size'],
                'file_is_image' => $params['data']['is_image'],
                'filetype_id' => $params['filetype_id'],
                'file_linked_to' => $params['file_linked_to'],
                'linked_id' => $params['id'],
            );
            if ($params['data']['is_image']) {
                $file_data['file_height'] = $params['data']['image_height'];
                $file_data['file_width'] = $params['data']['image_width'];
            }
        } else {
            $action="add";
        }

//        echo $action;        
//        wts($params);
//        wts($file_data);
//        die();

        switch ($action) {
            case "add":
                $this->db->trans_start();
                $this->db->insert('files', $file_data);
                $file_id = $this->db->insert_id();
                $this->db->trans_complete();
                break;
            case "edit":
                // add updated date to both data arrays
                $file_data['updated_date'] = date("Y-m-d H:i:s");
                $this->db->trans_start();
                $this->db->update('files', $file_data, array('file_id' => $file_id));
                $this->db->trans_complete();
                break;
            default:
                show_404();
                break;
        }
        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return $file_id;
        } else {
            return false;
        }
    }

    public function get_file_id($params) {
        if (!$params['data']['orig_name']) {
            return false;
        } else {
            $this->db->select("file_id");
            $this->db->from("files");
            $this->db->where("file_name", $params['data']['file_name']);
            $this->db->where("file_linked_to", $params['file_linked_to']);
            $this->db->where("linked_id", $params['id']);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $result = $query->result_array();
                return $result[0]['file_id'];
            } else {
                return false;
            }
        }
    }

    public function get_file_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("*");
            $this->db->from("files");
            $this->db->join('filetypes', 'filetype_id');
            $this->db->where('file_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function get_file_list($file_linked_to=NULL, $linked_id=0,$by_filetype=false) {
        
        $this->db->select("files.*,filetypes.*");
        $this->db->from("files");
        $this->db->join('filetypes', 'filetype_id');
        if ($file_linked_to) {
            $this->db->where("file_linked_to", $file_linked_to);
            $this->db->where("linked_id", $linked_id);
        }
//            $this->db->join('editions', 'edition_id',"left outer");
//            $this->db->join('races', 'race_id',"left outer");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                if ($by_filetype) {
                    $file_list[$row['filetype_id']][]=$row;
                } else {                    
                    $file_list[]=$row;
                }
            }
            return $file_list;
        }
        return false;
        
    }

    public function remove_file($file_id,$path) {
        
        $this->load->helper("file");
        // physical file delete
        unlink($path);
        $this->db->trans_start();
        $this->db->delete('files', array('file_id' => $file_id));
        $this->db->trans_complete();
        return $this->db->trans_status();
        
    }
    
    public function check_filetype_exists($linked_to, $id, $filetype_id) {
        $this->db->select("*");
        $this->db->from("files");
        $this->db->where('file_linked_to', $linked_to);
        $this->db->where('linked_id', $id);
        $this->db->where('filetype_id', $filetype_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result[0];
        } else {
            return false;
        }
    }

}
