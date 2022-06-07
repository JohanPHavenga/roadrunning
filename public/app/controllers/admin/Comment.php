<?php

class Comment extends Admin_Controller
{

    private $return_url = "/admin/comment";
    private $create_url = "/admin/comment/create";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/comment_model');
    }

    public function _remap($method, $params = array())
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->view($params);
        }
    }

    public function view()
    {
        // load helpers / libraries
        $this->load->library('table');
        // unset edition return comment session
        $this->session->unset_userdata('edition_return_comment');


        $this->data_to_view["comment_data"] = $this->comment_model->get_comment_list();
        $this->data_to_view['heading'] = ["ID", "URL", "URLtype", "Linked To", "ID", "Actions"];

        $this->data_to_view['create_link'] = $this->create_comment;
        $this->data_to_header['title'] = "List of URLs";

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Users" => "/admin/comment",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add URL",
                "icon" => "link",
                "uri" => "comment/create/add",
            ],
        ];

        // $this->data_to_view['comment'] = $this->comment_disect();

        $this->data_to_header['css_to_load'] = array(
            "assets/admin/plugins/datatables/datatables.min.css",
            "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css",
        );

        $this->data_to_footer['js_to_load'] = array(
            "assets/admin/scripts/datatable.js",
            "assets/admin/plugins/datatables/datatables.min.js",
            "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js",
            "assets/admin/plugins/bootstrap-confirmation/bootstrap-confirmation.js",
        );

        $this->data_to_footer['scripts_to_load'] = array(
            "assets/admin/scripts/table-datatables-managed.js",
        );

        // load view
        $this->load->view($this->header_comment, $this->data_to_header);
        $this->load->view("/admin/comment/view", $this->data_to_view);
        $this->load->view($this->footer_comment, $this->data_to_footer);
    }

    public function create($action, $edition_id, $comment_id = 0)
    {

        // set return url
        $this->return_url = base_url("admin/edition/create/edit/" . $edition_id . "#comment_list");

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "Comment Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $edition_id;

        $this->data_to_header['css_to_load'] = array(
            "assets/admin/plugins/typeahead/typeahead.css"
        );

        $this->data_to_footer['js_to_load'] = array(
            "assets/admin/plugins/typeahead/handlebars.min.js",
            "assets/admin/plugins/typeahead/typeahead.bundle.min.js",
        );

        $this->data_to_footer['scripts_to_load'] = array(
            "assets/admin/scripts/autocomplete.js",
            "assets/admin/scripts/linked_to_hide_show.js",
        );

        if ($action == "edit") {
            $this->data_to_view['comment_detail'] = $this->comment_model->get_comment_detail($comment_id);
            $this->data_to_view['form_url'] =  $this->data_to_view['form_url'] . "/" . $comment_id;
            // wts($this->data_to_view['comment_detail'],1);
        } 

        // set validation rules
        $this->form_validation->set_rules('comment_data', 'Comment', 'required');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view("/admin/comment/create", $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            // SET COMMENT
            $comment_id = $this->comment_model->set_comment($action, $edition_id, $comment_id);            

            if ($comment_id) {
                $alert = "Comment has been " . $action . "ed";
                $status = "success";
            } else {
                $alert = "Error committing to the database";
                $status = "danger";
            }

            $this->session->set_flashdata([
                'alert' => $alert,
                'status' => $status,
            ]);

            // save_only takes you back to the edit page.
            if (array_key_exists("save_only", $_POST)) {
                $this->return_comment = base_url("admin/comment/create/edit/" . $edition_id . "/". $comment_id);
            }

            redirect($this->return_url);
        }
    }

    public function delete($edition_id, $comment_id)
    {
        // set return url
        $this->return_url = base_url("admin/edition/create/edit/" . $edition_id . "#comment_list");

        if (($comment_id == 0) and (!is_int($comment_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $comment_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get comment detail for nice delete message
        $comment_detail = $this->comment_model->get_comment_detail($comment_id);
        // delete record
        $db_del = $this->comment_model->remove_comment($comment_id);

        

        if ($db_del) {
            $msg = "Comment has successfully been deleted: " . $comment_detail['comment_data'];
            $status = "success";
        } else {
            $msg = "Error in deleting the record:'.$comment_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }
}
