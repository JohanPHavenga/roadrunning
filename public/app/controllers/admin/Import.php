<?php

class Import extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Import_model_phpexcel', 'import');
        $this->load->helper(array('form'));
        $this->load->library('form_validation');
    }

    public function index() {
        // models and stuff
        $this->load->model('admin/asamember_model');
        $this->load->library('upload');
        $this->load->library('table');
        $this->load->library('excel');

        // send thigns to view
        $this->data_to_header['title'] = "Import Event Info";
        $this->data_to_view['form_url'] = "/admin/import/index/confirm";
        $this->data_to_view['asamember_dropdown'] = $this->asamember_model->get_asamember_dropdown("name");

        // set config for upload
        $config['upload_path'] = "uploads/temp/";
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['max_size'] = 8192;
        $config['remove_spaces'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        // check if folder exists and clear
        $this->manage_temp_folder($config['upload_path']);
        // clear session import
        $this->session->unset_userdata('import');

        // form validation rules
        $this->form_validation->set_rules('asamember_id', 'ASA Memeber', 'required|numeric|greater_than[0]',
                [
                    "greater_than" => "Select an <b>ASA Memeber</b> to upload the event information set against",
                ]
        );

        // first check form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view("/admin/import/file_upload", $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } elseif (!$this->upload->do_upload('event_info_file')) {
            $this->data_to_view['error'] = $this->upload->display_errors();
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view("/admin/import/file_upload", $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $data = array('upload_data' => $this->upload->data());
            if (!empty($data['upload_data']['file_name'])) {
                $import_xls_file = $data['upload_data']['file_name'];
            } else {
                $import_xls_file = 0;
            }
            $inputFileName = $config['upload_path'] . $import_xls_file;
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' . $e->getMessage());
            }

            // GET DATA + FORMAT + ADD ASAMEMEBER STUFF            
            $asamember_detail=$this->asamember_model->get_asamember_detail($this->input->post("asamember_id")); // gete asamember details
            $data = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true); // set data from excel 
            $map = $data[1]; // map the columns to the rows
            foreach ($map as $col => $item) {
                if (empty($item)) {
                    unset($map[$col]);
                }
            }
            array_shift($data); // drop headings
            foreach ($data as $key=>$row) { // loop through data to setup insert data array for insert function
                foreach ($map as $col => $item) {
                    if (empty($item)) {
                        continue;
                    }
                    $ins_data[$key][$item] = $row[$col];
                }
                $ins_data[$key]["asamember_id"]=$this->input->post("asamember_id");
                $ins_data[$key]["asamember_name"]=$asamember_detail['asa_member_name'];
            }
            
            // INSERT INTO TEMP TABLE
            $import = $this->import->insert_into_temp_table("temp_import_event", $ins_data);
            redirect("/admin/import/temp");
        }
    }

    private function manage_temp_folder($path) {
        if (!file_exists($path)) {
            if (!mkdir($config['upload_path'], 0777, true)) {
                return false;
            }
        } else {
            $files = glob($path . '*'); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file))
                    unlink($file); // delete file
            }
        }
    }

    public function temp() {
        $this->load->library('table');
        
        // get temp table data
        $this->data_to_view['import_data'] = $temp_data = $this->import->get_temp_table_data("temp_import_event");
        $this->data_to_view['columns'] = array_keys($temp_data[1]);
//        wts($temp_data,1);

        // show table of data with buttons next to them to: (if IDs are not set)
        // import users
        // import towns
        // import clubs

        $this->data_to_header['title'] = "Import: ".$temp_data[0]['asamember_name'];
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/admin/import/temp", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);


        // loop back to this function to show results of import attempt        
        // show errors if you try and import any, else insert ID into temp table
        // if all ID has been filled, show button to IMPORT EVENT
        // 
        // Create Event, then Edition, the Races. 
        // Do all in a transaction so if somethign goes wrong it rolls back
        // Add event ID to temp table as you go. If you run it again and there is an ID, it is skipped
        //
        // show result of import attempt. Pull data from editions table and display
        //
    }

    function user() {

        $this->load->model('admin/user_model');
        $this->load->model('admin/club_model');
        $this->load->model('admin/town_model');
        $this->load->model('admin/race_model');
        $this->load->model('admin/edition_model');
        $this->load->model('admin/event_model');

        $page = "import_confirm";
        $this->load->library('table');
        $skip_add = 1;

        // get results table fields
        $result_fields = $this->result_model->get_result_field_array();
        $unset_arr = ["result_id", "race_id", "file_id", "created_date", "updated_date"];
        foreach ($unset_arr as $unset_field) {
            unset($result_fields[$unset_field]);
        }

        //set skip arr
        for ($x = 1; $x <= 12; $x++) {
            $skip_arr[$x] = $x;
        }

        // set stuff to go to view
        $this->data_to_view['skip_arr'] = $skip_arr;
        $this->data_to_view['result_fields'] = $this->add_exception_fields($this->result_model->get_result_field_dropdown());
        $this->data_to_view['columns'] = array_keys($_SESSION['import']['result_data'][1]);
        $this->data_to_view['import_data'] = array_slice($_SESSION['import']['result_data'], 0, 12);

        $race = $_SESSION['import']['race'];
        $distance = str_pad(round($race['race_distance'], 0), 2, '0', STR_PAD_LEFT);
        $year = date('Y', strtotime($race['edition_date']));
        $this->data_to_view['race_name'] = $race['event_name'] . " | " . $year . " | " . $distance . " km | " . $race['racetype_abbr'];

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
                    $input_data = $this->set_exception_fields($input_data, $field, $_SESSION['import']['result_data'][$this->input->post('skip') + $skip_add][$column]);
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
            // get skip from 
            foreach ($_SESSION['import']['result_data'] as $key => $row) {
                if (is_numeric($row['A'])) {
                    $skip = $key;
                    $skip_display = $skip - 1;
                    break;
                }
            }
            $pre_load = $this->pre_load_per_asa($_SESSION['import']['race']['asa_member_id'], $skip);

            $this->data_to_view['input_data'] = $pre_load['input'];
            $this->data_to_view['pre_load'] = $pre_load['pre'];
            $this->data_to_view['skip'] = $skip_display;
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
