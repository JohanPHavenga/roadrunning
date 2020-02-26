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
            $asamember_detail = $this->asamember_model->get_asamember_detail($this->input->post("asamember_id")); // gete asamember details
            $data = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true); // set data from excel 
            $map = $data[1]; // map the columns to the rows
            foreach ($map as $col => $item) {
                if (empty($item)) {
                    unset($map[$col]);
                }
            }
            array_shift($data); // drop headings
            foreach ($data as $key => $row) { // loop through data to setup insert data array for insert function
                foreach ($map as $col => $item) {
                    if (empty($item)) {
                        continue;
                    }
                    $ins_data[$key][$item] = $row[$col];
                }
                $ins_data[$key]["asamember_id"] = $this->input->post("asamember_id");
                $ins_data[$key]["asamember_name"] = $asamember_detail['asa_member_name'];
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
        unset($_SESSION['import']);
        // get temp table data
        $_SESSION['import']['event'] = $temp_data = $this->import->get_temp_table_data();
        $unset_arr = ["time", "contact", "phone", "club_web", "asamember_id", "asamember_name", "timestamp"];
        $this->data_to_view['asamember_name'] = $temp_data[1]['asamember_name'];
        foreach ($temp_data as $key => $row) {
            foreach ($row as $column => $value) {
                if (!in_array($column, $unset_arr)) {
                    $temp_data_disp[$key][$column] = $value;
                }
            }
        }
        $this->data_to_view['import_data'] = $temp_data_disp;
        $this->data_to_view['columns'] = array_keys($temp_data_disp[1]);
        $this->data_to_view['columns'][0] = "#";

        $fields_to_check = ["user_id", "club_id", "town_id", "event_id", "edition_id", "club_web", "gps"];
        foreach ($fields_to_check as $field) {
            $check = $this->import->check_ids("$field");
            if ($check) {
                $this->data_to_view[$field . '_btn'] = "default";
            } else {
                $this->data_to_view[$field . '_btn'] = "primary";
            }
        }
        // import users
        // import towns
        // import clubs

        $this->data_to_header['title'] = "Import: " . $this->data_to_view['asamember_name'];
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

    function fill_temp($type) {
        switch ($type) {
            case "user":
                $this->load->model('user_model');
                foreach ($_SESSION['import']['event'] as $temp_id => $event) {
                    if ($event['user_id'] == 0) {
                        if (!empty($event['email'])) {
                            // set user_arr for create if needed
                            $value_parts = explode(" ", trim($event['contact']));
                            $user_arr["user_name"] = $value_parts[0];
                            unset($value_parts[0]);
                            $user_arr["user_surname"] = implode(" ", $value_parts);
                            $user_arr['user_email'] = trim($event['email']);
                            $user_arr['user_contact'] = $this->int_phone($event['phone']);
                            // get user id
                            $user_id = $this->user_model->get_user_id($event['email'], $user_arr);
                            // set user_id in temp table
                            $set = $this->import->update_field($temp_id, "user_id", $user_id);
                        } else {
                            $error_arr[$event['contact']] = "No email for <b>" . $event['contact'] . "</b> #" . $temp_id;
                        }
                    }
                }
                break;
            case "town":
                $stop = false;
                $this->load->model('admin/town_model');
                foreach ($_SESSION['import']['event'] as $temp_id => $event) {
                    if ($event['town_id'] == 0) {
                        // get town id
                        $town_id = $this->town_model->get_town_id($event['town']);
                        // set town_id in temp table
                        if ($town_id == -1) {
                            $error_arr[$event['town']] = "<b>" . $event['town'] . "</b> has no region set ";
                        } elseif ($town_id > 0) {
                            $set = $this->import->update_field($temp_id, "town_id", $town_id);
                        } else {
                            $error_arr[$event['town']] = "Town not found: <b>" . $event['town'] . "</b>";
                        }
                    }
                }
                break;
            case "club":
                $this->load->model('admin/club_model');
                foreach ($_SESSION['import']['event'] as $temp_id => $event) {
                    if ($event['club_id'] == 0) {
                        // set club_arr for create if needed. Can only be done after town
                        $club_arr = [
                            'club_name' => $event['club'],
                            'club_status' => 1,
                            'town_id' => $event['town_id'],
                        ];
                        // get club id
                        $club_id = $this->club_model->get_club_id($event['club'], $club_arr);
                        // set club_id in temp table
                        $set = $this->import->update_field($temp_id, "club_id", $club_id);
                    }
                }
                break;
            case "gps":
                $this->load->model('admin/club_model');
                foreach ($_SESSION['import']['event'] as $temp_id => $event) {
                    if (empty($event['gps'])) {

                        $address = url_title($event['address'], "+") . "," . url_title($event['town'], "+") . ",South+Africa";
                        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "&key=".$_SESSION['webdata']['google']['api_key'];
                        $data = json_decode($this->url_get_contents($url));
                        if (isset($data->results[0]->geometry->location->lat)) {
                            $lat = $data->results[0]->geometry->location->lat;
                            $lng = $data->results[0]->geometry->location->lng;

                            $gps = $lat . "," . $lng;
                            $set = $this->import->update_field($temp_id, "gps", $gps);
                        }
                    }
                }
                break;
        }

        if ($error_arr) {
            $this->session->set_flashdata([
                'alert' => "There was a few issues with pulling in the <b>" . ucfirst($type) . "s</b>. See below for details",
                'status' => "danger",
                'errors' => $error_arr,
            ]);
        } else {
            $this->session->set_flashdata([
                'alert' => ucfirst($type) . "s were added successfully.",
                'status' => "success",
            ]);
        }

        redirect("/admin/import/temp");
    }

    public function table($type) {
        // import data into actual table
        switch ($type) {
            case "club_web":
                $n = $o = $s = 0;
                $this->load->model('admin/url_model');
                foreach ($_SESSION['import']['event'] as $temp_id => $event) {
                    if (($event['club_id'] > 0) && ($event['club_web'])) {
                        $url_id = $this->url_model->exists("club", $event['club_id'], 1);
                        if (!$url_id) {
                            // set url_arr for create if needed. Can only be done after club import
                            $url_arr = array(
                                'url_name' => $event['club_web'],
                                'urltype_id' => 1,
                                'url_linked_to' => "club",
                                'linked_id' => $event['club_id'],
                            );
                            // get club id
                            $url_id = $this->url_model->set_url("add", 0, $url_arr);
                            $n++;
                        } else {
                            $o++;
                        }
                    } else {
                        $s++;
                    }
                }
                $this->session->set_flashdata([
                    'alert' => "<b>" . $n . "</b> new URLs were created. <b>" . $o . "</b> were already created. There were no URLs listed for <b>" . $s . "</b> of the clubs.",
                    'status' => "success",
                ]);
                break;
            // EVENTS
            case "event":
                $n = $o = $s = 0;
                $this->load->model('admin/event_model');
                foreach ($_SESSION['import']['event'] as $temp_id => $event) {
                    if ($event['event_id'] == 0) {
                        // get event id
                        $event_id = $this->event_model->get_event_id($event['event']);
                        if (!$event_id) {
                            if (!empty($event['event'])) {
                                $event_data = array(
                                    'event_name' => $event['event'],
                                    'event_status' => 1,
                                    'town_id' => $event['town_id'],
                                    'club_id' => $event['club_id'],
                                );
                                $event_id = $this->event_model->set_event("add", 0, $event_data);
                                $n++;
                            } else {
                                $error_arr[$event['event']] = "Event name cannot be empty #" . $temp_id;
                            }
                        } else {
                            $o++;
                        }
                        // set event_id in temp table
                        $set = $this->import->update_field($temp_id, "event_id", $event_id);
                    } else {
                        $s++;
                    }
                }
                if ($error_arr) {
                    $this->session->set_flashdata([
                        'alert' => "There was a few issues with pulling in the <b>" . ucfirst($type) . "s</b>. See below for details",
                        'status' => "danger",
                        'errors' => $error_arr,
                    ]);
                } else {
                    $this->session->set_flashdata([
                        'alert' => "<b>" . $n . "</b> new events were created. <b>" . $o . "</b> existing events were linked to. <b>" . $s . "</b> were skipped.",
                        'status' => "success",
                    ]);
                }
                break;

            // EDTIONS
            case "edition":
                $n = $o = $s = 0;
                $this->load->model('admin/edition_model');
                $this->load->model('admin/date_model');
                foreach ($_SESSION['import']['event'] as $temp_id => $event) {
                    if ($event['edition_id'] == 0) {
                        // get edition id
                        $edition_name = $event['event'] . " " . date("Y", strtotime($event['date']));
                        $edition = $this->edition_model->get_edition_id_from_name($edition_name);
                        if (!$edition) {
                            if ((!empty($event['date'])) && (!empty($event['gps'])) && (!empty($event['address']))) {
                                $edition_data = array(
                                    'user_id' => $event['user_id'],
                                    'event_id' => $event['event_id'],
                                    'edition_asa_member' => $event['asamember_id'],
                                    'edition_status' => 1,
                                    'edition_info_status' => 14,
                                    'edition_name' => $edition_name,
                                    'edition_date' => $event['date'],
                                    'edition_address' => $event['address'],
                                    'edition_address_end' => $event['address'],
                                    'edition_gps' => $event['gps'],
                                    'edition_isfeatured' => 0,
                                    'edition_no_auto_mail' => 0,
                                    'edition_info_prizegizing' => "00:00",
                                );
                                $edition_id = $this->edition_model->set_edition("add", 0, $edition_data);
                                // create start and end DATES
                                $date_data = [
                                    'date_start' => $event['date'],
                                    'date_end' => $event['date'],
                                    'datetype_id' => 1,
                                    'date_linked_to' => "edition",
                                    'linked_id' => $edition_id,
                                ];
                                $this->date_model->set_date("add", NULL, $date_data, false);
                                $n++;
                            } else {
                                $error_arr[$event['event']] = "Some of the edition fields are empty #" . $temp_id;
                                continue;
                            }
                        } else {
                            $edition_id = $edition['edition_id'];
                            $o++;
                        }
                        // set edition_id in temp table
                        $set = $this->import->update_field($temp_id, "edition_id", $edition_id);
                    } else {
                        $s++;
                    }
                }
                if ($error_arr) {
                    $this->session->set_flashdata([
                        'alert' => "There was a few issues with pulling in the <b>" . ucfirst($type) . "s</b>. See below for details",
                        'status' => "danger",
                        'errors' => $error_arr,
                    ]);
                } else {
                    $this->session->set_flashdata([
                        'alert' => "<b>" . $n . "</b> new editions were created. <b>" . $o . "</b> existing editions were linked to. <b>" . $s . "</b> were skipped.",
                        'status' => "success",
                    ]);
                }
                break;

            // RACES
            case "race":
                $n = $o = $s = 0;
                $this->load->model('admin/race_model');
                $this->load->model('admin/edition_model');
                foreach ($_SESSION['import']['event'] as $temp_id => $event) {
                    if ($event['race_ids'] == "") {
                        // get race list
                        $race_list = $this->race_model->get_race_list($event['edition_id']);
                        if (!$race_list) {
                            if (!empty($event['time'])) {
                                $race_dist_arr = explode(",", $event['races']);
                                foreach ($race_dist_arr as $race_dist) {
                                    $race_data_basic = array(
                                        'race_distance' => $race_dist,
                                        'race_status' => 1,
                                        'race_time_start' => $event['time'],
                                        'edition_id' => $event['edition_id'],
                                        'racetype_id' => 4,
                                        'race_name' => "",
                                    );
                                    $race_data = $this->race_fill_blanks($race_data_basic, ["edition_asa_member" => $event['asamember_id'], "edition_date" => $event['date']]);
                                    $race_id_arr[] = $this->race_model->set_race("add", 0, $race_data);
                                }
                                $n++;
                            } else {
                                $error_arr[$event['event']] = "Time cannot be empty #" . $temp_id;
                            }
                        } else {
                            $race_id_arr = array_keys($race_list);
                            $o++;
                        }
                        $race_id_str = implode(",", $race_id_arr);
                        // set event_id in temp table
                        $set = $this->import->update_field($temp_id, "race_ids", $race_id_str);
                        unset($race_id_arr);
                    } else {
                        $s++;
                    }
                    // tags
                    $edition_data = $this->edition_model->get_edition_detail($event['edition_id']);
                    $race_data = $this->race_model->get_race_list($event['edition_id']);
                    $this->set_tags($event['edition_id'], $edition_data, $race_data);
                }

                // FLASH DATA

                if ($error_arr) {
                    $this->session->set_flashdata([
                        'alert' => "There was a few issues with pulling in the <b>" . ucfirst($type) . "s</b>. See below for details",
                        'status' => "danger",
                        'errors' => $error_arr,
                    ]);
                } else {
                    $this->session->set_flashdata([
                        'alert' => "<b>" . $n . "</b> new race sets were created. <b>" . $o . "</b> existing races were linked to. <b>" . $s . "</b> were skipped.",
                        'status' => "success",
                    ]);
                }
                break;
        }
        redirect("/admin/import/temp");
    }

    public function import_confirmation() {

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Import" => "/admin/import",
            "Success" => "",
        ];

        if ($this->session->has_userdata('edition_return_url')) {
            $this->data_to_view['return_url'] = $this->session->edition_return_url;
        } else {
            $this->data_to_view['return_url'] = "/admin/import";
        }

        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/admin/import/import_success", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

}
