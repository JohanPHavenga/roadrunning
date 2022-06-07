<?php

class Comment_model extends Admin_model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function record_count()
    {
        return $this->db->count_all("comments");
    }


    public function get_comment_list($edition_id = NULL)
    {
        $this->db->select("comments.*");
        $this->db->from("comments");
        $this->db->order_by("updated_date", "DESC");
        if ($edition_id) {
            $this->db->where('edition_id', $edition_id);
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function get_comment_detail($comment_id)
    {
        $this->db->select("comments.*");
        $this->db->from("comments");
        $this->db->where('comment_id', $comment_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    public function set_comment($action, $edition_id, $comment_id, $comment_data = [], $debug = false)
    {

        // POSTED DATA
        if (empty($comment_data)) {
            $comment_data = array(
                'comment_data' => $this->input->post('comment_data'),
                'comment_isadminnote' => 1,
                'edition_id' => $edition_id,
            );
        }

        if ($debug) {
            echo "<p><b>COMMENT SET Transaction</b></p>";
            echo "ACTION: " . $action . "<br>";
            echo "COMMENT ID: " . $comment_id . "<br>";
            wts($comment_data);
            die();
        } else {
            switch ($action) {
                case "add":
                    $this->db->trans_start();
                    $this->db->insert('comments', $comment_data);
                    $sql = $this->db->set($comment_data)->get_compiled_insert('comment_data');
                    //                wts($sql);
                    //                die();

                    $comment_id = $this->db->insert_id();
                    $this->db->trans_complete();
                    break;
                case "edit":
                    // add updated date to both data arrays
                    $comment_data['updated_date'] = date("Y-m-d H:i:s");

                    // start SQL transaction
                    $this->db->trans_start();
                    $this->db->update('comments', $comment_data, array('comment_id' => $comment_id));
                    $this->db->trans_complete();
                    break;
                default:
                    show_404();
                    break;
            }
            // return ID if transaction successfull
            if ($this->db->trans_status()) {
                return $comment_id;
            } else {
                return false;
            }
        }
    }

    public function remove_comment($id)
    {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('comments', array('comment_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }


    public function copy($id)
    {
        /* generate the select query */
        $this->db->where('comment_id', $id);
        $query = $this->db->get('comments');

        foreach ($query->result() as $row) {
            foreach ($row as $key => $val) {
                if ($key != 'comment_id') {
                    /* $this->db->set can be used instead of passing a data array directly to the insert or update functions */
                    $this->db->set($key, $val);
                } //endif              
            } //endforeach
        } //endforeach

        /* insert the new record into table */
        $this->db->trans_start();
        $this->db->insert('comments');
        $comment_id = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            return $comment_id;
        } else {
            return false;
        }
    }
}
