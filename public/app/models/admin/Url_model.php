<?php

class Url_model extends Admin_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("urls");
    }
    
    public function exists($linked_to, $linked_id, $urltype_id) {
        $this->db->select("url_id");
        $this->db->from("urls");        
        $this->db->where('url_linked_to', $linked_to);
        $this->db->where('linked_id', $linked_id);   
        $this->db->where('urltype_id', $urltype_id);          
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result=$query->result_array();
            return $result[0]['url_id'];
        }
        return false;
    }

    public function get_url_list($linked_to=NULL, $linked_id=0,$by_urltype=false) {

        $this->db->select("urls.*, urltype_name");
        $this->db->join("urltypes", "urltype_id");
        $this->db->from("urls");
        
        if ($linked_id>0) {
            $this->db->where('url_linked_to', $linked_to);
            $this->db->where('linked_id', $linked_id);
        }
        
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                if ($by_urltype) {
                    $url_list[$row['urltype_id']][]=$row;
                } else {                    
                    $url_list[]=$row;
                }
                
//                $data[$row['url_id']] = $row;
            }
            return $url_list;
//            return $data;
        }
        return false;
    }

    public function get_url_dropdown() {
        $this->db->select("url_id, url_name");
        $this->db->from("urls");
        $this->db->order_by('url_name');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['url_id']] = $row['url_name'];
            }
//                return array_slice($data, 0, 500, true);
            return $data;
        }
        return false;
    }

    public function get_url_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->select("urls.*");
            $this->db->from("urls");
            $this->db->where('url_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_url($action, $url_id, $url_data=[], $debug=false) {
        
        // POSTED DATA
        if (empty($url_data))
        {
            $id_type = $this->input->post("url_linked_to") . "_id";
            $id = $this->input->post($id_type);

            $url_data = array(
                'url_name' => $this->input->post('url_name'),
                'urltype_id' => $this->input->post('urltype_id'),
                'url_linked_to' => $this->input->post('url_linked_to'),
                'linked_id' => $id,
            );
        } 

        if ($debug) {
            echo "<p><b>URL SET Transaction</b></p>";
            echo "ACTION: ".$action."<br>";
            echo "URL ID: ".$url_id."<br>";
            wts($url_data);
            die();
        } else {
            switch ($action) {
                case "add":
                    $this->db->trans_start();
                    $this->db->insert('urls', $url_data);
                    $sql = $this->db->set($url_data)->get_compiled_insert('urls');
    //                wts($sql);
    //                die();

                    $url_id = $this->db->insert_id();
                    $this->db->trans_complete();
                    break;
                case "edit":
                    // add updated date to both data arrays
                    $url_data['updated_date'] = date("Y-m-d H:i:s");

                    // start SQL transaction
                    $this->db->trans_start();
                    $this->db->update('urls', $url_data, array('url_id' => $url_id));
                    $this->db->trans_complete();
                    break;
                default:
                    show_404();
                    break;
            }
            // return ID if transaction successfull
            if ($this->db->trans_status()) {
                return $url_id;
            } else {
                return false;
            }
        }
    }

    public function remove_url($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('urls', array('url_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }
    
    public function check_urltype_exists($linked_to, $id, $urltype_id) {
        $this->db->select("url_id");
        $this->db->from("urls");
        $this->db->where('url_linked_to', $linked_to);
        $this->db->where('linked_id', $id);
        $this->db->where('urltype_id', $urltype_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

}
