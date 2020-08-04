<?php

class Result extends Admin_Controller {

    private $return_url = "/admin/result/view";
    private $create_url = "/admin/result/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/result_model');
        $this->load->model('admin/Import_model_phpexcel', 'import');
        $this->load->helper(array('form'));
        $this->load->library('form_validation');
    }

    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->view($params);
        }
    }

    public function search($search_string = null) {
        $this->session->unset_userdata('edition_return_url');

        // add models+helpers
        $this->load->model('admin/race_model');
        $this->load->library('table');
        // set form url
        $this->data_to_view['form_url'] = "";
        $this->data_to_view['create_link'] = $this->create_url;

        // stuff to load
        $this->data_to_header['css_to_load'] = array(
            "assets/admin/plugins/typeahead/typeahead.css",
            "assets/admin/plugins/datatables/datatables.min.css",
            "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css",
        );
        $this->data_to_footer['js_to_load'] = array(
            "assets/admin/plugins/typeahead/typeahead.bundle.min.js",
            "assets/admin/scripts/datatable.js",
            "assets/admin/plugins/datatables/datatables.min.js",
            "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js",
            "assets/admin/plugins/bootstrap-confirmation/bootstrap-confirmation.js"
        );
        $this->data_to_footer['scripts_to_load'] = array(
            "assets/admin/scripts/auto_complete_generic.js",
            "assets/admin/scripts/table-datatables-managed.js"
        );
        // get race list for auto_complete field
        $this->data_to_view['race_list'] = $this->race_model->get_race_list_with_results(false);


        if ($this->input->post('race_summary')) {
            // get race_id
            $this->data_to_view['post_data'] = $post_data = $this->input->post();
            $race_id = array_search($post_data['race_summary'], $this->data_to_view['race_list']);

            if ($race_id > 0) {
                $this->data_to_view['race_id'] = $race_id;
                // get race info
                $this->data_to_view["race_info"] = $this->race_model->get_race_detail($race_id);
                // get results
                $this->data_to_view["result_data"] = $this->result_model->get_result_list($race_id);
            }
        }

        // load view
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/admin/result/search", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function view($race_id = 0) {
        $this->session->unset_userdata('edition_return_url');
        // load helpers / libraries
        $this->load->library('table');

        $this->data_to_view["result_data"] = $this->result_model->get_result_list($race_id);
        $this->data_to_view['heading'] = ["Pos", "Race", "Name", "Date", "Club", "Time", "Actions"];

        $this->data_to_header['title'] = "List of all Results";
        $this->data_to_view['create_link'] = $this->create_url;

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Results" => "/admin/result",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Import Results",
                "icon" => "login",
                "uri" => "result/import",
            ],
//            [
//                "name" => "Manually Add Result",
//                "icon" => "plus",
//                "uri" => "result/create/add",
//            ],
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
        $this->load->view("/admin/result/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0) {
        // block add
        if ($action != "edit") {
            $this->session->set_flashdata('alert', "Can only edit results, not add them");
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // additional models
        $this->load->model('admin/race_model');
        $this->load->model('admin/userresult_model');
        $this->load->library('table');

        // set data
        $this->data_to_header['title'] = "Result Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;

        $this->data_to_header['css_to_load'] = array(
            "assets/admin/plugins/typeahead/typeahead.css",
            "assets/admin/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css",
        );

        $this->data_to_footer['js_to_load'] = array(
            "assets/admin/plugins/typeahead/handlebars.min.js",
            "assets/admin/plugins/typeahead/typeahead.bundle.min.js",
            "assets/admin/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js",
        );

        $this->data_to_footer['scripts_to_load'] = array(
            "assets/admin/scripts/autocomplete.js",
            "assets/admin/scripts/components-date-time-pickers.js",
        );

        if ($action == "edit") {
            $this->data_to_view['result_detail'] = $this->result_model->get_result_detail($id);
            $result_id = $id;
            $race_id = $this->data_to_view['result_detail']['race_id'];
            $this->data_to_view['user_result_list'] = $this->userresult_model->get_userresult_list(null, $result_id);
        } else {
            $race_id = $id;
            $result_id = "";
            $this->data_to_view['result_detail'] = $this->result_model->get_result_field_array();
            $this->data_to_view['result_detail']['race_id'] = 0;
            $this->data_to_view['result_detail']['result_time'] = 0;
        }

        $this->data_to_view['race_detail'] = $this->race_model->get_race_detail($race_id);
        $this->data_to_view['race_id'] = $race_id;
        $this->data_to_view['result_id'] = $result_id;

        // set validation rules
        $this->form_validation->set_rules('result_pos', 'Position', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('result_surname', 'Surname', 'required|min_length[3]');
        $this->form_validation->set_rules('result_time', 'Time', 'required');
        $this->form_validation->set_rules('race_id', 'Race', 'required|numeric|greater_than[0]');

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Results" => "/admin/result/view/" . $race_id,
            ucfirst($action) . " #" . $result_id => "",
        ];

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url . "/" . $race_id;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $data = $this->input->post();
            $id = $this->result_model->set_result($action, $id, $data);
            if ($id) {
                $alert = "Result has been updated";
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
                $this->return_url = base_url("admin/result/create/edit/" . $id);
            }

            redirect($this->return_url . "/" . $race_id);
        }
    }

    public function delete($result_id = 0) {

        if (($result_id == 0) AND ( !is_int($result_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $result_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }
        // delete record
        $db_del = $this->result_model->remove_result($result_id);

        if ($db_del) {
            $msg = "Result has successfully been deleted: " . $result_id;
            $status = "success";
        } else {
            $msg = "Error in deleting the record: " . $result_id;
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

    public function delete_result_set($race_id = 0) {
        $this->load->model('admin/race_model');

        // set return url to session should it exists
        if ($this->session->has_userdata('edition_return_url')) {
            $this->return_url = $this->session->edition_return_url;
        } else {
            $this->return_url = base_url("admin/result/search");
            ;
        }

//        wts($race_id,1);
        // get race detail for nice delete message
        $race_detail = $this->race_model->get_race_detail($race_id);
        // delete record
        $db_del = $this->result_model->remove_result_set($race_id);

        if ($db_del) {
            $msg = "Result set successfully deleted for the <b>" . $race_detail['edition_name'] . " " . $race_detail['race_name'] . "</b>";
            $status = "success";
        } else {
            $msg = "Error in deleting the result set for Race ID: '.$race_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

    public function import($race_id = 0) {

        $this->load->library('upload');
        $this->load->library('table');
        $this->load->library('excel');
        $this->load->model('admin/race_model');
        $this->load->model('admin/file_model');

        $this->data_to_header['title'] = "Import Result Set";
        $this->data_to_view['form_url'] = "/admin/result/import/confirm";
        $this->data_to_view['race_dropdown'] = $this->race_model->get_race_dropdown(false, true);
        $this->data_to_view['race_id'] = $race_id;

        // set config for upload
        $config['upload_path'] = $this->upload_path;
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['max_size'] = 8192;
        $config['remove_spaces'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        // set validation rules
        $this->form_validation->set_rules('race_id', 'Race', 'required|numeric|greater_than[0]',
//        $this->form_validation->set_rules('race_id', 'Race', 'required|numeric|greater_than[0]|callback_check_result_file',
                [
//                    "check_result_file" => "This race already <b>has a result file loaded</b> against it",
                    "greater_than" => "Select a <b>race</b> to upload the result set against",
                ]
        );

        // first check form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view("/admin/result/import", $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } elseif (!$this->upload->do_upload('result_file')) {
            $this->data_to_view['error'] = $this->upload->display_errors();
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view("/admin/result/import", $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $data = array('upload_data' => $this->upload->data());
            if (!empty($data['upload_data']['file_name'])) {
                $import_xls_file = $data['upload_data']['file_name'];
            } else {
                $import_xls_file = 0;
            }
            $inputFileName = $this->upload_path . $import_xls_file;
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' . $e->getMessage());
            }

            // SET SESSION
            $_SESSION['import']['result_data'] = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            // COPY FILE;            
            $this->check_upload_folder("race", $this->input->post('race_id'));
            rename($inputFileName, './uploads/race/' . $this->input->post('race_id') . "/" . $import_xls_file);
            // write FILES table entry
            $params = [
                "id_type" => "race_id",
                "id" => $this->input->post('race_id'),
                "filetype_id" => 4,
                "file_linked_to" => "race",
                "data" => $data['upload_data'],
                "debug" => false,
            ];
            // set file
            $_SESSION['import']['file_id'] = $this->file_model->set_file($params);
            // add race detail to session
            $_SESSION['import']['race_id'] = $this->input->post('race_id');
            $_SESSION['import']['race'] = $this->race_model->get_race_detail($this->input->post('race_id'));
            $_SESSION['import']['count'] = count($_SESSION['import']['result_data']);
            // set results flag 
            $set = $this->set_results_flag("race", $this->input->post('race_id'));

//            wts($file_db_w);
//            wts($set);
//            wts($inputFileName);
//            wts($this->input->post());
//            wts($_SESSION['import']);
//            die();
            redirect("/admin/result/import_confirm");
        }
    }

    function import_confirm() {
        $page = "import_confirm";
        $this->load->library('table');

        // get results table fields
        $result_fields = $this->result_model->get_result_field_array();
        $unset_arr = ["result_id", "race_id", "file_id", "created_date", "updated_date"];
        foreach ($unset_arr as $unset_field) {
            unset($result_fields[$unset_field]);
        }

        // how many rows to skip
        $skip_add = 1;
        if ($this->input->post()) {
            $this->data_to_view['skip'] = $this->input->post('skip') + $skip_add;
        } else {
            // get skip from 
            foreach ($_SESSION['import']['result_data'] as $key => $row) {
                if (is_numeric($row['A'])) {
                    $skip = $key;
                    $skip_display = $skip - 1;
                    break;
                }
            }
            $this->data_to_view['skip'] = $skip_display;
        }
        
        $rows_to_display=$this->data_to_view['skip']+3;


        //set skip arr
        for ($x = 1; $x <= $rows_to_display; $x++) {
            $skip_arr[$x] = $x;
        }

        // set stuff to go to view
        $this->data_to_view['skip_arr'] = $skip_arr;
        $this->data_to_view['result_fields'] = $this->add_exception_fields($this->result_model->get_result_field_dropdown());
        $this->data_to_view['columns'] = array_keys($_SESSION['import']['result_data'][1]);
        $this->data_to_view['import_data'] = array_slice($_SESSION['import']['result_data'], 0, $rows_to_display);

//        wts($this->data_to_view, 1);

        $race = $_SESSION['import']['race'];
        $distance = str_pad(round($race['race_distance'], 0), 2, '0', STR_PAD_LEFT);
        $year = date('Y', strtotime($race['edition_date']));
        $this->data_to_view['race_name'] = $race['event_name'] . " | " . $year . " | " . $distance . " km | " . $race['racetype_abbr'] . " | #" . $_SESSION['import']['count'] . " results";

        // set input_data from either the post, or preload templates
        if ($this->input->post()) {
            foreach ($this->input->post("columns") as $column => $field) {

                if ($field) {
                    // exceptions
//                    if ($field == "result_name_surname") {
//                        $input_data = $this->set_exception_fields($input_data, "result_name_surname", $_SESSION['import']['result_data'][$this->input->post('skip') + $skip_add][$column]);
//                    } else {
//                        $input_data[$field] = $_SESSION['import']['result_data'][$this->input->post('skip') + $skip_add][$column];
//                    }
                    if (!isset($input_data)) {
                        $input_data = [];
                    }
                    $input_data = $this->set_exception_fields($input_data, $field, $_SESSION['import']['result_data'][$this->data_to_view['skip']][$column]);
                }
            }
            $this->data_to_view['input_data'] = $input_data;

//            // save_only takes you back to the edit page.
            if (array_key_exists("upload", $this->input->post())) {
                // set column_map and skip values
                $_SESSION['import']['column_map'] = $this->input->post("columns");
                $_SESSION['import']['skip'] = $this->input->post("skip");
                redirect("/admin/result/run_import");
                die();
            }
        } else {

            $pre_load = $this->pre_load_per_asa($_SESSION['import']['race']['asa_member_id'], $skip);

            $this->data_to_view['input_data'] = $pre_load['input'];
            $this->data_to_view['pre_load'] = $pre_load['pre'];
        }

        if ($this->session->has_userdata('edition_return_url')) {
            $this->data_to_view['cancel_url'] = $this->session->edition_return_url;
        } else {
            $this->data_to_view['cancel_url'] = "/admin/result/import";
        }

        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/admin/result/" . $page, $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    private function add_exception_fields($field_arr) {
        $field_arr["result_name_surname"] = "name_surname";
        return $field_arr;
    }

    private function set_exception_fields($input_data = [], $field = null, $value = null) {
        switch ($field) {
            case "result_name_surname" :
                $value_parts = explode(" ", $value);
                $input_data["result_name"] = $value_parts[0];
                unset($value_parts[0]);
                $input_data["result_surname"] = implode(" ", $value_parts);
                break;
            case "result_sex" :
                $input_data[$field] = strtoupper(substr($value, 0, 1));
                break;
            default:
                $input_data[$field] = $value;
                break;
        }
        return $input_data;
    }

    private function pre_load_per_asa($asa_member_id, $skip) {
        switch ($asa_member_id) {
            case 1: //WPA
                $data['pre']["A"] = "result_pos";
                $data['pre']["B"] = "result_surname";
                $data['pre']["C"] = "result_name";
                $data['pre']["D"] = "result_club";
                $data['pre']["E"] = "result_age";
                $data['pre']["F"] = "result_sex";
                $data['pre']["G"] = "result_cat";
                $data['pre']["H"] = "result_asanum";
                $data['pre']["I"] = "result_time";
                break;
            case 2: // BOLA
                $data['pre']["A"] = "result_pos";
                $data['pre']["B"] = "result_name";
                $data['pre']["C"] = "result_surname";
                $data['pre']["D"] = "result_club";
                $data['pre']["E"] = "result_asanum";
                $data['pre']["F"] = "result_age";
                $data['pre']["G"] = "result_cat";
                $data['pre']["H"] = "result_sex";
                $data['pre']["I"] = "result_time";
                break;
            case 3: // ASWD
                $data['pre']["A"] = "result_pos";
                $data['pre']["B"] = "result_name_surname";
                $data['pre']["C"] = "result_club";
                $data['pre']["D"] = "result_racenum";
                $data['pre']["E"] = "result_asanum";
                $data['pre']["F"] = "result_age";
                $data['pre']["G"] = "result_sex";
                $data['pre']["H"] = "result_time";
                break;
            case 4: // AGW
                $data['pre']["A"] = "result_pos";
                $data['pre']["B"] = "result_asanum";
                $data['pre']["C"] = "result_time";
                $data['pre']["D"] = "result_name";
                $data['pre']["E"] = "result_surname";
                $data['pre']["F"] = "result_sex";
                $data['pre']["G"] = "";
                $data['pre']["H"] = "result_age";
                $data['pre']["I"] = "";
                $data['pre']["J"] = "result_club";
                break;
            default:
                $data['pre']["A"] = "result_pos";
                $data['pre']["B"] = "result_surname";
                $data['pre']["C"] = "result_name";
                $data['pre']["D"] = "result_club";
                $data['pre']["E"] = "result_age";
                $data['pre']["F"] = "result_sex";
                $data['pre']["G"] = "result_cat";
                $data['pre']["H"] = "result_asanum";
                $data['pre']["I"] = "result_time";
                break;
        }
        foreach ($data['pre'] as $column => $field) {
            if ($field) {
                $data['input'] = $this->set_exception_fields($data['input'], $field, $_SESSION['import']['result_data'][$skip][$column]);
            }
        }
        return $data;
    }

    function run_import() {
//        wts($_SESSION['import']);
//        die();
        // IMPORT LOGIC
        $this->load->model('admin/result_model');
        if ($_SESSION['import']) {
            $import = $_SESSION['import'];
            if (!$this->result_model->result_exist_for_race($import['race_id'])) {

                // get_race_name
                $race = $_SESSION['import']['race'];
                $distance = str_pad(round($race['race_distance'], 0), 2, '0', STR_PAD_LEFT);
                $year = date('Y', strtotime($race['edition_date']));
                $race_name = $race['event_name'] . " | " . $year . " | " . $distance . " km | " . $race['racetype_abbr'];
                $import_counter = 0;

                foreach ($import['result_data'] as $key => $row) {
                    if ($key <= $import['skip']) {
                        continue;
                    }
                    foreach ($row as $col => $value) {
                        if ($import['column_map'][$col]) {
//                            if ($import['column_map'][$col] == "result_name_surname") {
//                                $result_data = $this->set_exception_fields($result_data, "result_name_surname", $value);
//                            } else {
//                                $result_data[$import['column_map'][$col]] = $value;
//                            }
                            $result_data = $this->set_exception_fields($result_data, $import['column_map'][$col], $value);
                        }
                    }
                    $result_data['race_id'] = $import['race_id'];
                    $result_data['file_id'] = $import['file_id'];
                    // check is pos is an INT
                    if (!is_numeric($result_data['result_pos'])) {
                        break;
                    }
                    $set_result = $this->result_model->set_result("add", NULL, $result_data);
                    $import_counter++;
                }
                // UNSET SESSION IMPORT DATA
                unset($_SESSION['import']);

                $this->session->set_flashdata([
                    'alert' => "Upload Successful",
                    'status' => "success",
                    'race_name' => $race_name,
                    'import_counter' => $import_counter,
                ]);
            } else {
                $this->session->set_flashdata([
                    'alert' => "Upload Failed! Results already exists for this race",
                    'status' => "danger",
                ]);
            }
        } else {
            // go to view
            $this->session->set_flashdata([
                'alert' => "Upload Failed! No upload data found",
                'status' => "danger",
            ]);
        }
        redirect("./admin/result/import_confirmation");
    }

    public function import_confirmation() {

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Result" => "/admin/result",
            "Import" => "/admin/result/import",
            "Success" => "",
        ];

        if ($this->session->has_userdata('edition_return_url')) {
            $this->data_to_view['return_url'] = $this->session->edition_return_url;
        } else {
            $this->data_to_view['return_url'] = "/admin/result/import";
        }

        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/admin/result/import_success", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    // =========================================================================
    // CUSTOM VALIDATIONS
    // =========================================================================

    function check_result_file() {
        // check if a result file exists for the race
        $this->load->model('admin/file_model');
        if ($this->file_model->check_filetype_exists("race", $this->input->post("race_id"), 4)) {
            return false;
        } else {
            return true;
        }
    }

}
