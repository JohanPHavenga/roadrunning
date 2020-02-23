<?php

class Url extends Admin_Controller {

    private $return_url = "/admin/url";
    private $create_url = "/admin/url/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/url_model');
    }

    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->view($params);
        }
    }

    public function view() {
        // load helpers / libraries
        $this->load->library('table');
        // unset edition return url session
        $this->session->unset_userdata('edition_return_url');


        $this->data_to_view["url_data"] = $this->url_model->get_url_list();
        $this->data_to_view['heading'] = ["ID", "URL", "URLtype", "Linked To", "ID", "Actions"];

        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['title'] = "List of URLs";

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Users" => "/admin/url",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add URL",
                "icon" => "link",
                "uri" => "url/create/add",
            ],
        ];

        $this->data_to_view['url'] = $this->url_disect();

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
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/admin/url/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0, $linked_type = NULL) {

        // set return url to session should it exists
        if ($this->session->has_userdata('edition_return_url')) {
            $this->return_url = $this->session->edition_return_url . "#url_list";
        }

        // additional models
        $this->load->model('admin/urltype_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "URL Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

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

        $this->data_to_view['urltype_dropdown'] = $this->urltype_model->get_urltype_dropdown();
        $this->data_to_view['linked_to_dropdown'] = $this->urltype_model->get_linked_to_dropdown();
        $this->data_to_view['linked_to_list'] = $this->urltype_model->get_linked_to_list();

        // dynamically get drop downs using the linked_to_table
        foreach ($this->data_to_view['linked_to_list'] as $linked_to_id => $linked_to_name) {
            $dropdown = $linked_to_name . "_dropdown";
            $model = $linked_to_name . "_model";
            $method = "get_" . $linked_to_name . "_dropdown";

            $this->load->model("admin/".$model);
            $this->data_to_view[$dropdown] = $this->$model->$method();
        }

        if ($action == "edit") {
            $this->data_to_view['url_detail'] = $this->url_model->get_url_detail($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {
            if ($id > 0) {
                $this->data_to_view['url_detail']['linked_id'] = $id;
                $this->data_to_view['url_detail']['url_linked_to'] = $linked_type;
            }
        }

//        wts($this->data_to_view['url_detail']);
        // set validation rules
        $this->form_validation->set_rules('url_name', 'URL Name', 'required|valid_url');
        $this->form_validation->set_rules('urltype_id', 'URL Type', 'required|greater_than[0]', ["greater_than" => "Please select a URL Type"]);


        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            // SET URL
            $id = $this->url_model->set_url($action, $id);
            // set the results flag
            $results_link_arr = ['edition', 'race'];
            if (in_array($this->input->post("url_linked_to"), $results_link_arr)) {
                $id_type = $this->input->post("url_linked_to") . "_id";
                $linked_id = $this->input->post($id_type);
                $set = $this->set_results_flag($this->input->post("url_linked_to"), $linked_id);
            }
            
            // set update date on linked entity
            $model=$this->input->post("url_linked_to")."_model";
            $this->$model->update_field($linked_id,"updated_date", fdateLong());

            if ($id) {
                $alert = "URL details has been " . $action . "ed";
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
                $this->return_url = base_url("admin/url/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }

    public function delete($url_id = 0) {

        // set return url to session should it exists
        if ($this->session->has_userdata('edition_return_url')) {
            $this->return_url = $this->session->edition_return_url;
        }

        if (($url_id == 0) AND ( !is_int($url_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $url_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get url detail for nice delete message
        $url_detail = $this->url_model->get_url_detail($url_id);
        // delete record
        $db_del = $this->url_model->remove_url($url_id);

        // check results flag
        $results_link_arr = ['edition', 'race'];
        if (in_array($url_detail['url_linked_to'], $results_link_arr)) {
            $set = $this->set_results_flag($url_detail['url_linked_to'], $url_detail['linked_id']);
        }

        if ($db_del) {
            $msg = "URL has successfully been deleted: " . $url_detail['url_name'];
            $status = "success";
        } else {
            $msg = "Error in deleting the record:'.$url_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

    function exists($linked_type, $linked_id, $urltype_id) {
        return $this->url_model->exists($linked_type, $linked_id, $urltype_id);
    }

    function port() {
        // function to port old URLs from fields directly on Edition to URl table
        $this->load->model('admin/edition_model');
        $this->load->model('admin/urltype_model');

        $edition_list = $this->edition_model->get_edition_list();
        $urltype_list = $this->urltype_model->get_urltype_list();

        $url_map_arr = [
            "edition_url" => 1,
            "edition_url_entry" => 5,
            "edition_url_flyer" => 2,
            "edition_url_results" => 4,
        ];
        $n = 0;
        $r = 0;
        foreach ($edition_list as $e_id => $edition) {
            foreach ($url_map_arr as $old_field => $map_id) {
                if ($edition[$old_field] && !$this->exists("edition", $e_id, $map_id)) {
                    $url_data = array(
                        'url_name' => $edition[$old_field],
                        'urltype_id' => $map_id,
                        'url_linked_to' => "edition",
                        'linked_id' => $e_id,
                    );
                    $set = $this->url_model->set_url("add", 0, $url_data, false);
                    $n++;
                }

                if ($edition[$old_field] && $map_id == 4) {
                    $r++;
                    $set_results_flag = $this->url_model->set_results_flag("edition", $e_id, 1);
                }
            }
        }

        echo "Done<br>";
        echo $n . " records added to URL table<br>";
        echo $r . " results flags set<br>";
    }

}
