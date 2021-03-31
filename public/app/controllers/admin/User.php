<?php
class User extends Admin_Controller
{

    private $return_url = "/admin/user";
    private $create_url = "/admin/user/create";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/user_model');
    }

    public function _remap($method, $params = array())
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->search();
        }
    }

    public function search()
    {
        $page = "search";
        $this->data_to_header['title'] = "User Search";

        $this->load->library('table');
        $this->load->model('admin/user_model');
        $this->data_to_view['create_link'] = $this->create_url;

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Users" => "/admin/user/search",
            "Search" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add User",
                "icon" => "users",
                "uri" => "user/create/add",
            ],
        ];

        if ($this->input->get('u_query')) {
            $this->data_to_view['search_results'] = $this->user_model->user_search($this->input->get('u_query'));
            $this->data_to_view['msg'] = "<p>We could <b>not find</b> any user matching your search.<br>Please try again.</p>";
        } else {
            $this->data_to_view['msg'] = "<p>Please use the <b>search box</b> above to seach for a user.</p>";
        }

            //    wts($this->data_to_view['search_results']);
            //    die();

        $this->data_to_view['heading'] = ["ID", "Name", "Surname", "Email", "Roles", "Actions"];

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
        $this->load->view("/admin/user/search", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function import($submit = NULL)
    {

        $this->load->helper('form');
        $this->load->library('upload');
        $this->load->library('table');

        $this->data_to_view['title'] = "Import Users";
        $this->data_to_view['form_url'] = "/admin/user/import/confirm";
        $this->data_to_view['side_menu_arr']['import']['class'] = "active";

        $config['upload_path']          = $this->upload_path;
        $config['allowed_types']        = 'csv';
        $config['max_size']             = 8192;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('userfile')) {
            if (!empty($submit)) {
                $this->data_to_view['error'] = $this->upload->display_errors();
            }

            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view("/admin/user/import", $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {

            if ($submit == "confirm") {
                // get file data and meta data
                // $this->data_to_view['file_meta_data'] = $this->upload->data();
                $file_data = $this->csv_handler($this->upload->data('full_path'));
                $sum_data = $this->csv_flat_table_import($file_data);

                // set to session
                $_SESSION['sum_data'] = $sum_data;
                // send to view
                $this->data_to_view['sum_data'] = $sum_data;

                $this->load->view($this->header_url, $this->data_to_header);
                $this->load->view("/admin/user/import_confirm", $this->data_to_view);
                $this->load->view($this->footer_url, $this->data_to_footer);
            } else {
                die("Upload failure");
            }
        }
    }

    public function import_done()
    {
        // load helpers / libraries
        $this->load->helper('form');
        $db_write = false;
        if (isset($_SESSION['sum_data'])) {
            // wts($_SESSION['sum_data']);
            // die();
            foreach ($_SESSION['sum_data'] as $action => $entry_list) {
                foreach ($entry_list as $id => $user_data) {
                    $db_write = $this->user_model->set_user($action, $id, $user_data);
                }
            }
            unset($_SESSION['sum_data']);
        }

        if ($db_write) {
            $alert = "User list has been updated";
            $status = "success";
        } else {
            $alert = "Error committing to the database";
            $status = "danger";
        }

        $this->session->set_flashdata([
            'alert' => $alert,
            'status' => $status,
        ]);

        $this->data_to_view['title'] = "Import Complete";
        $this->data_to_view['side_menu_arr']['import']['class'] = "active";

        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view('/admin/user/import_success', $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }


    public function view()
    {
        // load helpers / libraries
        $this->load->library('table');

        $this->data_to_view["user_data"] = $this->user_model->get_user_list();
        $this->data_to_view['heading'] = ["ID", "User Name", "User Surname", "User Email", "Club Name", "Actions"];

        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['title'] = "List of Users";
        $this->data_to_header['crumbs'] =
            [
                "Home" => "/admin",
                "Users" => "/admin/user",
                "List" => "",
            ];

        $this->data_to_header['page_action_list'] =
            [
                [
                    "name" => "Add User",
                    "icon" => "users",
                    "uri" => "user/create/add",
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
        $this->load->view("/admin/user/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }



    public function create($action, $id = 0)
    {
        // additional models
        $this->load->model('admin/club_model');
        $this->load->model('admin/role_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set data
        $this->data_to_header['title'] = "User Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        //        $this->data_to_view['js_to_load']=array("select2.js");
        //        $this->data_to_view['js_script_to_load']='$(".autocomplete").select2({minimumInputLength: 2});';
        //        $this->data_to_view['css_to_load']=array("select2.css","select2-bootstrap.css");

        $this->data_to_view['club_dropdown'] = $this->club_model->get_club_dropdown();
        $this->data_to_view['role_dropdown'] = $this->role_model->get_role_dropdown();

        if ($action == "edit") {
            $this->data_to_view['user_detail'] = $this->user_model->get_user_detail($id);
            $this->data_to_view['user_detail']['role_id'] = $this->role_model->get_role_list_per_user($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
            $this->data_to_view['edition_links'] = $this->user_model->get_edition_links($id);
        }
        // set default sponsor
        if (empty($this->data_to_view['user_detail']['club_id'])) {
            $this->data_to_view['user_detail']['club_id'] = 8;
        }

        // set validation rules
        $this->form_validation->set_rules('user_name', 'Name', 'required');
        $this->form_validation->set_rules('user_surname', 'Surame', 'required');
        $this->form_validation->set_rules('user_email', 'Email', 'valid_email');
        $this->form_validation->set_rules('club_id', 'Club', 'required|numeric|greater_than[0]', ["greater_than" => "Please select a club for the user"]);
        $this->form_validation->set_rules('role_id[]', 'Role', 'required');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            if ($action == "add") {
                $this->data_to_view['user_detail']['role_id'][] = 3;
            }
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $id = $this->user_model->set_user($action, $id);
            if ($id) {
                $alert = "User has been updated";
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
                $this->return_url = base_url("admin/user/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }


    public function delete($user_id = 0)
    {

        if (($user_id == 0) and (!is_int($user_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $user_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get user detail for nice delete message
        $user_detail = $this->user_model->get_user_detail($user_id);
        // delete record
        $db_del = $this->user_model->remove_user($user_id);

        if ($db_del) {
            $msg = "User has successfully been deleted: " . $user_detail['user_name'] . " " . $user_detail['user_surname'];
            $status = "success";
        } else {
            $msg = "Error in deleting the record:'.$user_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }


    public function delete_old($confirm = false)
    {

        $id = $this->encryption->decrypt($this->input->post('user_id'));

        if ($id == 0) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        if ($confirm == 'confirm') {
            $db_del = $this->user_model->remove_user($id);
            if ($db_del) {
                $msg = "Event has been deleted";
                $status = "success";
            } else {
                $msg = "Error committing to the database ID:'.$id";
                $status = "danger";
            }

            $this->session->set_flashdata('alert', $msg);
            $this->session->set_flashdata('status', $status);
            redirect($this->return_url);
        } else {
            $this->session->set_flashdata('alert', 'Cannot delete record');
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }
    }


    public function export()
    {
        $this->load->dbutil();
        $this->load->helper('download');
        /* get the object   */
        $export = $this->user_model->export();
        /*  pass it to db utility function  */
        $new_report = $this->dbutil->csv_from_result($export);
        /*  Force download the file */
        force_download('users.csv', $new_report);
        /*  Done    */
    }


    public function profile()
    {
        // load helpers / libraries
        $this->load->library('table');

        $this->data_to_view['title'] = "User Profile";


        // load view
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view('/admin/user/profile', $this->data_to_view);
        $this->load->view($this->footer_url);
    }

    public function cleanup($debug = 1)
    {
        $user_detail = $this->user_model->get_bot_users();

        $skip_list = [1494, 3536, 552, 7709, 160, 307, 3292];
        $to_del = [];

        // name check
        foreach ($user_detail as $user_id => $user) :
            if (in_array($user_id, $skip_list)) : continue;
            endif;
            preg_match_all('/[A-Z]/', $user['name'], $cap_matches, PREG_OFFSET_CAPTURE);
            if (
                (count($cap_matches[0]) > 2) &&
                (count($cap_matches[0]) - strlen(trim($user['name'])) < -1)
            ) {
                $to_del[$user_id] = $user;
            }
        endforeach;
        // surname check
        foreach ($user_detail as $user_id => $user) :
            if (in_array($user_id, $skip_list)) : continue;
            endif;
            preg_match_all('/[A-Z]/', $user['surname'], $cap_matches, PREG_OFFSET_CAPTURE);
            if (
                (count($cap_matches[0]) > 2) &&
                (count($cap_matches[0]) - strlen(trim($user['surname'])) < -1)
            ) {
                $to_del[$user_id] = $user;
            }
        endforeach;

        // deletion
        if ($to_del) {
            if ($debug) {
                wts($to_del, 1);
            }
            foreach ($to_del as $user_id => $user) :
                if ($this->user_model->remove_user($user_id)) {
                    echo $user['surname'] . "(#" . $user_id . ") has been removed";
                } else {
                    echo $user['surname'] . "(#" . $user_id . ") could not be removed";
                }
                echo "<br>";
            endforeach;
        } else {
            echo "Nothing to remove";
        }

        // wts($user_detail,1);
    }
}
