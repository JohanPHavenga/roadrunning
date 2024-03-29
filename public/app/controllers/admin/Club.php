<?php
class Club extends Admin_Controller
{

    private $return_url = "/admin/club";
    private $create_url = "/admin/club/create";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/club_model');
    }

    public function _remap($method, $params = array())
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            // $this->view($params);
            $this->search();
        }
    }

    public function search()
    {
        $page = "search";
        $this->data_to_header['title'] = "Club Search";

        $this->load->library('table');
        $this->load->model('admin/club_model');
        $this->data_to_view['create_link'] = $this->create_url;

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Clubs" => "/admin/club/search",
            "Search" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add Club",
                "icon" => "clubs",
                "uri" => "club/create/add",
            ],
        ];

        if ($this->input->get('u_query')) {
            $this->data_to_view['search_results'] = $this->club_model->club_search($this->input->get('u_query'));
            $this->data_to_view['msg'] = "<p>We could <b>not find</b> any club matching your search.<br>Please try again.</p>";
        } else {
            $this->data_to_view['msg'] = "<p>Please use the <b>search box</b> above to seach for a user.</p>";
        }

        //    wts($this->data_to_view['search_results']);
        //    die();

        $this->data_to_view['heading'] = ["ID", "Club Name", "Town", "Province", "Actions"];

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
        $this->load->view("/admin/club/search", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function view()
    {
        // load helpers / libraries
        $this->load->library('table');


        $this->data_to_view["club_data"] = $this->club_model->get_club_list();
        $this->data_to_view['heading'] = ["ID", "Club Name", "Status", "Town", "Province", "Sponsor", "Actions"];

        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['title'] = "List of Clubs";

        $this->data_to_header['crumbs'] =
            [
                "Home" => "/admin",
                "Users" => "/admin/club",
                "List" => "",
            ];

        $this->data_to_header['page_action_list'] =
            [
                [
                    "name" => "Add Club",
                    "icon" => "badge",
                    "uri" => "club/create/add",
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
        $this->load->view("/admin/club/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }


    public function create($action, $id = 0)
    {
        // additional models
        $this->load->model('admin/town_model');
        $this->load->model('admin/sponsor_model');
        $this->load->model('admin/event_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "Club Input Page";
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
        );


        $this->data_to_view['status_dropdown'] = $this->club_model->get_status_dropdown();
        $this->data_to_view['town_dropdown'] = $this->town_model->get_town_dropdown();
        $this->data_to_view['sponsor_dropdown'] = $this->sponsor_model->get_sponsor_dropdown();

        if ($action == "edit") {
            $this->data_to_view['club_detail'] = $this->club_model->get_club_detail($id);
            $this->data_to_view['club_detail']['event_list'] = $this->club_model->get_club_races($id);
            foreach ($this->data_to_view['club_detail']['event_list'] as $event_id=>$event) {                
                $this->data_to_view['club_detail']['event_list'][$event_id]['latest_edition']=$this->event_model->get_edition_list($event_id, true);
            }
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {
            $this->data_to_view['club_detail']['club_status'] = 1;
            $this->data_to_view['club_detail']['sponsor_id'] = 4;
        }

        // set validation rules
        $this->form_validation->set_rules('club_name', 'Club Name', 'required');
        $this->form_validation->set_rules('club_status', 'Club Status', 'required|greater_than[0]', ["greater_than" => "Please select a Status"]);
        $this->form_validation->set_rules('town_id', 'Town', 'required|numeric|greater_than[0]', ["greater_than" => "Please select a Town"]);
        $this->form_validation->set_rules('sponsor_id', 'Club Sponsor', 'required|greater_than[0]', ["greater_than" => "Please select a Sponsor"]);


        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            //            wts($_REQUEST);
            //            die();

            $id = $this->club_model->set_club($action, $id);
            if ($id) {
                $alert = "Club details has been " . $action . "ed";
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
                $this->return_url = base_url("admin/club/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }


    public function delete($club_id = 0)
    {

        if (($club_id == 0) and (!is_int($club_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $club_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get club detail for nice delete message
        $club_detail = $this->club_model->get_club_detail($club_id);
        // delete record
        $db_del = $this->club_model->remove_club($club_id);

        if ($db_del) {
            $msg = "Club has successfully been deleted: " . $club_detail['club_name'];
            $status = "success";
        } else {
            $msg = "Error in deleting the record:'.$club_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }
}
