<?php

class Result extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
        if (empty($this->logged_in_user)) {
            $this->session->set_flashdata([
                'alert' => "You are not currently logged in, or your session has expired. Please log in or register",
                'status' => "warning",
                'icon' => "info-circle",
            ]);
            redirect(base_url("login"));
        } else {
            $this->load->model('user_model');
            $this->load->model('race_model');
            $this->load->library('table');
            $this->data_to_views['page_menu'] = $this->get_user_menu();
        }
    }

    // SEARCH FOR RESULTS
    public function search() {
        if ($this->input->post("result_search") !== null) {

            $this->data_to_views['page_title'] = "Search for results";
            $this->data_to_views['meta_description'] = "Search for results";
            $this->data_to_views['crumbs_arr'] = [
                "Home" => base_url(),
                "User" => base_url("user"),
                "My Results" => base_url("user/my-results"),
                "Search" => "",
            ];

            $this->data_to_views['race_list'] = $this->race_model->get_race_list_with_results($this->input->post("result_search"));
//        echo $this->input->post("result_search");
//        wts($this->data_to_view['race_list'],1);
            // load view
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view($this->notice_url, $this->data_to_views);
            $this->load->view('templates/page_menu', $this->data_to_views);
            $this->load->view('result/search', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            $this->session->set_flashdata([
                'alert' => "Use the form below to find race results",
                'status' => "warning",
                'icon' => "info-circle",
            ]);
            redirect(base_url("user/my-results"));
        }
    }

    public function list($race_id, $load = "summary") {
        if (is_numeric($race_id)) {
            // set basics for the view
            $this->data_to_views['page_title'] = "List of results";
            $this->data_to_views['meta_description'] = "List of results for race";
            $this->data_to_views['load'] = $load;
            $this->data_to_views['crumbs_arr'] = [
                "Home" => base_url(),
                "User" => base_url("user"),
                "My Results" => base_url("user/my-results"),
                "Search" => "",
            ];

            $params['race_id'] = $race_id;
            if ($load == "summary") {
                $params['name'] = $this->logged_in_user['user_name'];
                $params['surname'] = $this->logged_in_user['user_surname'];
            }
            $this->data_to_views['result_list'] = $this->race_model->get_race_detail_with_results($params);
            if (!$this->data_to_views['result_list']) {
                redirect(base_url("result/list/".$race_id."/full"));
            }
            
            $firstKey = array_key_first($this->data_to_views['result_list']);
            $result = $this->data_to_views['result_list'][$firstKey];
            foreach ($result as $key => $value) {
                if (strpos($key, "result_") === false) {
                    $this->data_to_views['race_info'][$key] = $value;
                }
            }

            $this->data_to_views['css_to_load'] = [base_url("assets/js/plugins/components/datatables/datatables.min.css")];
            $this->data_to_views['scripts_to_load'] = [
                base_url("assets/js/plugins/components/datatables/datatables.min.js"),
                base_url("assets/js/data-tables.js"),
            ];

            // load view
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view($this->notice_url, $this->data_to_views);
            $this->load->view('templates/page_menu', $this->data_to_views);
            $this->load->view('result/list', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            $this->session->set_flashdata([
                'alert' => "That race does not exist. Use the form below to find race results",
                'status' => "danger",
                'icon' => "times-circle",
            ]);
            redirect(base_url("user/my-results"));
        }
    }

    public function view($result_id) {
        $this->load->model('admin/userresult_model');

        if ((is_numeric($result_id)) && ($this->userresult_model->exists($this->logged_in_user['user_id'], $result_id))) {

            $result = $this->race_model->get_race_detail_with_results(["result_id" => $result_id]);
            $this->data_to_views['result_detail'] = $result[$result_id];

            $this->data_to_views['page_title'] = "Detail for result #" . $result_id;
            $this->data_to_views['meta_description'] = "Result details for " . $result[$result_id]['result_name'] . " " . $result[$result_id]['result_surname'] . " in the " . $result[$result_id]['edition_name'] . " race.";

            $this->data_to_views['crumbs_arr'] = [
                "Home" => base_url(),
                "User" => base_url("user"),
                "My Results" => base_url("user/my-results"),
                "View" => "",
            ];

            // load view
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view($this->notice_url, $this->data_to_views);
            $this->load->view('result/view', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            $this->session->set_flashdata([
                'alert' => "You are not linked to that result.",
                'status' => "danger",
                'icon' => "times-circle",
            ]);
            redirect(base_url("user/my-results"));
        }
    }

    public function claim($result_id) {
        $this->load->model('admin/userresult_model');
        if (is_numeric($result_id)) {
            $user_id = $this->logged_in_user['user_id'];
            // check if already exists
            if (!$this->userresult_model->exists($user_id, $result_id)) {
                $this->userresult_model->set_userresult("add", ["user_id" => $user_id, "result_id" => $result_id]);
                $this->session->set_flashdata([
                    'alert' => "Result has been added to your profile",
                    'status' => "success",
                    'icon' => "check-circle",
                ]);
            } else {
                $this->session->set_flashdata([
                    'alert' => "That result is already linked to your profile",
                    'status' => "warning",
                    'icon' => "info-circle",
                ]);
            }
        } else {
            $this->session->set_flashdata([
                'alert' => "That result does not exist. Use the form below to find race results",
                'status' => "danger",
                'icon' => "times-circle",
            ]);
        }
        redirect(base_url("user/my-results"));
    }

    public function remove($result_id) {
        $this->load->model('admin/userresult_model');
        if ((is_numeric($result_id)) && ($this->userresult_model->exists($this->logged_in_user['user_id'], $result_id))) {
            $user_id = $this->logged_in_user['user_id'];
            // check if already exists
            $this->userresult_model->remove_userresult($user_id, $result_id);
            $this->session->set_flashdata([
                'alert' => "Result has been removed from your profile",
                'status' => "success",
                'icon' => "check-circle",
            ]);
        } else {
            $this->session->set_flashdata([
                'alert' => "You are not linked to that result.",
                'status' => "danger",
                'icon' => "times-circle",
            ]);
        }
        redirect(base_url("user/my-results"));
    }

}
