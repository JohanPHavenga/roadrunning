<?php

// NOT SURE IF THIS CONTROLLER IS IN USE 
// bar the dropdown method that returns nothing


class Newsletter_model extends MY_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function record_count() {
        return $this->db->count_all("newsletters");
    }

    public function get_newsletter_list() {
        $this->db->select("newsletters.*");
        $this->db->from("newsletters");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['newsletter_id']] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_newsletter_dropdown() {

        $this->db->select("newsletter_id, newsletter_name");
        $this->db->from("newsletters");
        $this->db->order_by("newsletter_name");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['newsletter_id']] = $row['newsletter_name'];
            }
//                return array_slice($data, 0, 500, true);
            return $data;
        }
        return false;
    }

    public function get_newsletter_list_simple() {
        $data[0]="All Newsletters";
        $this->db->select("newsletter_id, newsletter_name");
        $this->db->from("newsletters");
        $this->db->order_by("newsletter_id");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data[] = "Please Select";
            foreach ($query->result_array() as $row) {
                $data[$row['newsletter_id']] = $row['newsletter_name'];
            }
        }
        return $data;
    }

    public function get_newsletter_detail($id) {
        if (!($id)) {
            return false;
        } else {
            $query = $this->db->get_where('newsletters', array('newsletter_id' => $id));

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        }
    }

    public function set_newsletter($action, $newsletter_id) {
        $data = array(
            'newsletter_name' => $this->input->post('newsletter_name'),
            'newsletter_status' => $this->input->post('newsletter_status'),
        );

        switch ($action) {
            case "add":
                $this->db->trans_start();
                $this->db->insert('newsletters', $data);
                $newsletter_id = $this->db->insert_id();
                $this->db->trans_complete();
                break;
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");

                // start SQL transaction
                $this->db->trans_start();
                $this->db->update('newsletters', $data, array('newsletter_id' => $newsletter_id));
                $this->db->trans_complete();
                break;
            default:
                show_404();
                break;
        }

        // return ID if transaction successfull
        if ($this->db->trans_status()) {
            return $newsletter_id;
        } else {
            return false;
        }
    }

    public function remove_newsletter($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('newsletters', array('newsletter_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }

}
