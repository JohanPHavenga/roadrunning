<?php

class Town extends Admin_Controller {

    private $return_url = "/admin/town";
    private $create_url = "/admin/town/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/town_model');
    }

    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->search();
        }
    }

//    public function view($id = FALSE) {
//        // load helpers / libraries
//        $this->load->library('table');
//
//        $url_disect = $this->url_disect();
//
//        // pagination
//        // pagination config
//        $per_page = 20;
//        $uri_segment = count($url_disect['url_string_arr']);
//        $total_rows = $this->town_model->record_count();
//        $config = fpaginationConfig($this->return_url, $per_page, $total_rows, $uri_segment);
//
//        // pagination init
//        $this->load->library("pagination");
//        $this->pagination->initialize($config);
//        $this->data_to_view["pagination"] = $this->pagination->create_links();
//
//        // set data
//        $page = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;
//        $this->data_to_view["list"] = $this->town_model->get_town_list($per_page, $page);
//        $this->data_to_header['title'] = "List of Towns";
//
//        // as daar data is
//        if ($this->data_to_view["list"]) {
//            $this->data_to_view['heading'] = ftableHeading(array_keys($this->data_to_view['list'][key($this->data_to_view['list'])]));
//        }
//
//        // load view;
//        $this->load->view($this->header_url, $this->data_to_header);
//        $this->load->view($this->view_url, $this->data_to_view);
//        $this->load->view($this->footer_url, $this->data_to_footer);
//    }

    public function json($ss = '') {
        if (empty($ss)) {
            die("No search string passed");
        }
        $this->data_to_view["search_result"] = $this->town_model->town_search($ss);
        $this->load->view("/admin/json", $this->data_to_view);
    }

    public function search() {
        $page = "search";
        $this->data_to_header['title'] = "Town Search";

        $this->load->library('table');
        $this->load->model('admin/town_model');
        $this->data_to_view['create_link'] = $this->create_url;

        $this->data_to_header['crumbs'] = [
                    "Home" => "/admin",
                    "Towns" => "/admin/town/search",
                    "Search" => "",
        ];

        $this->data_to_header['page_action_list'] = [
                    [
                        "name" => "Add Town",
                        "icon" => "home",
                        "uri" => "town/create/add",
                    ],
        ];

        if ($this->input->get('t_query')) {
            $this->data_to_view['search_results'] = $this->town_model->town_full_search($this->input->get('t_query'));
            $this->data_to_view['msg'] = "<p>We could <b>not find</b> any town matching your search.<br>Please try again.</p>";
        } else {
            $this->data_to_view['msg'] = "<p>Please use the <b>search box</b> above to seach for a town.</p>";
        }

//        wts($this->data_to_view['search_results']);
//        die();

        $this->data_to_view['heading'] = ["ID", "Town Name", "Province", "Region","Area", "Actions"];

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

        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/admin/town/search", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0) {
        // additional models
        $this->load->model('admin/town_model');
        $this->load->model('admin/area_model');
        $this->load->model('admin/province_model');
        $this->load->model('admin/region_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "Town Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        $this->data_to_view['province_dropdown'] = $this->province_model->get_province_dropdown();
        $this->data_to_view['area_dropdown'] = $this->area_model->get_area_dropdown();
        $this->data_to_view['region_dropdown'] = $this->region_model->get_region_dropdown();

        if ($action == "edit") {
            $this->data_to_view['town_detail'] = $this->town_model->get_town_detail($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {
            $this->data_to_view['town_detail']['province_id'] = 11;
        }

        // set validation rules
        $this->form_validation->set_rules('town_name', 'Town Name', 'required');
        $this->form_validation->set_rules('province_id', 'Province', 'required|numeric|greater_than[0]', ["greater_than" => "Please select a Province"]);
//        $this->form_validation->set_rules('province_id', 'Area', 'required|numeric|greater_than[0]', ["greater_than" => "Please select an Area"]);

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
//            wts($_REQUEST);
//            die();

            $id = $this->town_model->set_town($action, $id);
            if ($id) {
                $alert = "Town details has been " . $action . "ed";
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
                $this->return_url = base_url("admin/town/create/edit/" . $id);
            }

            redirect($this->return_url."?t_query=".$this->input->post('town_name'));
            
        }
    }

    public function set_town($action, $id) {
        $data = array(
            'town_name' => $this->input->post('town_name'),
        );

        switch ($action) {
            case "add":
                return $this->db->insert('towns', $data);
            case "edit":
                $data['updated_date'] = date("Y-m-d H:i:s");
                return $this->db->update('towns', $data, array('town_id' => $id));

            default:
                show_404();
                break;
        }
    }

    public function remove_towns($id) {
        if (!($id)) {
            return false;
        } else {
            $this->db->trans_start();
            $this->db->delete('towns', array('town_id' => $id));
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }
    
    public function delete($town_id=0) {

        if (($town_id==0) AND (!is_int($town_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: '.$town_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get race detail for nice delete message
        $town_detail=$this->town_model->get_town_detail($town_id);
        // delete record
        $db_del=$this->town_model->remove_town($town_id);
        
        if ($db_del)
        {
            $msg="Town has successfully been deleted: ".$town_detail['town_name'];
            $status="success";
        }
        else
        {
            $msg="Error in deleting the record:'.$town_id";
            $status="danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }
    
//    public function gen_update_script() {
//        $params=[
//            "where"=>[
//                "region_id !=" => 61,
//            ],
//        ];
//        $town_list=$this->town_model->get_town_list($params);
//        
//        foreach ($town_list as $town) {
//            echo "UPDATE towns SET region_id = '$town[region_id]' WHERE town_id = '$town[town_id]';<br>";
//        }
//        
//        wts($town_list);
//    }

}
