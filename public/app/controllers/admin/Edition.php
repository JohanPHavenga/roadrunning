<?php

class Edition extends Admin_Controller {

    private $return_url = "/admin/edition/view";
    private $create_url = "/admin/edition/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/edition_model');
        $this->load->model('admin/file_model');
    }

    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->view($params);
        }
    }

    // LIST VIEW
    public function view() {
        // load helpers / libraries
        $this->load->library('table');
        // unset dashboard return url session
        $this->session->unset_userdata('dashboard_return_url');

        $this->data_to_view["edition_data"] = $this->edition_model->get_edition_list();
        $this->data_to_view['heading'] = ["ID", "Edition Name", "Status", "Affiliation", "Edition Date", "Event Name", "Actions"];

        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['title'] = "List of Editions";
        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Editions" => "/admin/edition",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add Edition",
                "icon" => "calendar",
                "uri" => "edition/create/add",
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
        $this->load->view("/admin/edition/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    // THE BIG CREATE METHOD - ADD and EDIT
    public function create($action, $edition_id = 0) {
        if ($edition_id) {
            $this->data_to_view['delete_url'] = "/admin/edition/delete/" . $edition_id;
        }
        // additional models
        $this->load->model('admin/event_model');
        $this->load->model('admin/user_model');
        $this->load->model('admin/race_model');
        $this->load->model('admin/racetype_model');
        $this->load->model('admin/venue_model');
        $this->load->model('admin/date_model');
        $this->load->model('admin/url_model');
        $this->load->model('admin/file_model');
        $this->load->model('admin/asamember_model');
        $this->load->model('admin/sponsor_model');
        $this->load->model('admin/entrytype_model');
        $this->load->model('admin/regtype_model');
        $this->load->model('admin/result_model');
        $this->load->model('admin/tag_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('table');

        // set return url to session should it exists
        if ($this->session->has_userdata('dashboard_return_url')) {
            $this->return_url = $this->session->dashboard_return_url;
        }

        // set data
        $this->data_to_header['title'] = "Edition Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        $this->data_to_header['css_to_load'] = array(
            "assets/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css",
            "assets/admin/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css",
            "assets/admin/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css",
            "assets/admin/plugins/bootstrap-summernote/summernote.css",
            "assets/admin/plugins/datatables/datatables.min.css",
            "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css",
        );

        $this->data_to_footer['js_to_load'] = array(
            "assets/admin/plugins/moment.min.js",
            "assets/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js",
            "assets/admin/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js",
            "assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js",
            "assets/admin/plugins/bootstrap-summernote/summernote.min.js",
            "assets/admin/scripts/datatable.js",
            "assets/admin/plugins/datatables/datatables.min.js",
            "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js",
            "assets/admin/plugins/bootstrap-confirmation/bootstrap-confirmation.js",
        );

        $this->data_to_footer['scripts_to_load'] = array(
            "assets/admin/scripts/components-date-time-pickers.js",
            "assets/admin/scripts/components-editors.js",
            "assets/admin/scripts/table-datatables-managed.js",
        );

        // GET DATA TO SEND TO VIEW
        $this->data_to_view['contact_dropdown'] = $this->user_model->get_user_dropdown(3);
        $this->data_to_view['event_dropdown'] = $this->event_model->get_event_dropdown();
        $this->data_to_view['status_dropdown'] = $this->event_model->get_status_list("main");
        $this->data_to_view['status_list'] = $this->event_model->get_status_list();
        $this->data_to_view['info_status_dropdown'] = $this->event_model->get_status_list("info");
        $this->data_to_view['asamember_dropdown'] = $this->asamember_model->get_asamember_dropdown();
        $this->data_to_view['sponsor_dropdown'] = $this->sponsor_model->get_sponsor_dropdown();
        $this->data_to_view['entrytype_dropdown'] = $this->entrytype_model->get_entrytype_dropdown();
        $this->data_to_view['regtype_dropdown'] = $this->regtype_model->get_regtype_dropdown();
        $this->data_to_view['racetype_dropdown'] = $this->racetype_model->get_racetype_dropdown();
        $this->data_to_view['venue_dropdown'] = $this->venue_model->get_venue_dropdown();
        $this->data_to_view['admin_fee_dropdown'] = [
            "" => "None",
            "online" => "online",
            "late" => "late",
            "on the day" => "on the day",
        ];

        $this->data_to_view['sponsor_list'] = $this->sponsor_model->get_edition_sponsor_list($edition_id);
        $this->data_to_view['entrytype_list'] = $this->entrytype_model->get_edition_entrytype_list($edition_id);
        $this->data_to_view['regtype_list'] = $this->regtype_model->get_edition_regtype_list($edition_id);

        if ($action == "edit") {
            $this->data_to_view['edition_detail'] = $this->edition_model->get_edition_detail($edition_id);
            $this->data_to_view['race_list'] = $this->race_model->get_race_list($edition_id);
            $this->data_to_view['tag_list'] = $this->tag_model->get_edition_tag_list($edition_id);
            $this->data_to_view['date_list'] = $this->date_model->get_date_list("edition", $edition_id);
            $this->data_to_view['date_list_by_type'] = $this->date_model->get_date_list("edition", $edition_id, false, true);
            $this->data_to_view['url_list'] = $this->url_model->get_url_list("edition", $edition_id);
            $this->data_to_view['file_list'] = $this->file_model->get_file_list("edition", $edition_id);
            $this->data_to_view['file_list_by_type'] = $this->file_model->get_file_list("edition", $edition_id, true);
            foreach ($this->data_to_view['race_list'] as $race_id => $race) {
                $this->data_to_view['race_list'][$race_id]['has_results'] = $this->result_model->result_exist_for_race($race_id);
                $this->data_to_view['race_list'][$race_id]['file_list'] = $this->file_model->get_file_list("race", $race_id, true);
            }
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $edition_id;
            // set edition_return_url for races
            $this->session->set_userdata('edition_return_url', "/" . uri_string());
            $this->data_to_view['event_edit_url'] = "/admin/event/create/edit/" . $this->data_to_view['edition_detail']['event_id'];
        } else {
            $this->data_to_view['edition_detail'] = $this->edition_model->get_edition_field_array();
            $this->data_to_view['edition_detail']['edition_status'] = 1;
            $this->data_to_view['edition_detail']['edition_info_status'] = 14;
            $this->data_to_view['edition_detail']['edition_isfeatured'] = 0;
            $this->data_to_view['edition_detail']['sponsor_id'][] = 4;
            $this->data_to_view['edition_detail']['entrytype_id'][] = 5;
            $this->data_to_view['edition_detail']['user_id'] = 60;
            $this->data_to_view['edition_detail']['edition_asa_member'] = '';
        }

        // set validation rules
        $this->form_validation->set_rules('edition_name', 'Edition Name', 'trim|required|min_length[5]|callback_name_check',
                array('name_check' => 'Enter a valid year at the end of the Edition Name'));
        $this->form_validation->set_rules('event_id', 'Event', 'required|numeric|greater_than[0]', ["greater_than" => "Please select an event"]);
        $this->form_validation->set_rules('edition_status', 'Edition status', 'required');
        $this->form_validation->set_rules('edition_date', 'Edition date', 'required');
        $this->form_validation->set_rules('edition_address', 'Edition Address', 'required');
        $this->form_validation->set_rules('edition_gps', 'GPS', 'trim|required');
        $this->form_validation->set_rules('sponsor_id[]', 'Sponsor', 'required');
        $this->form_validation->set_rules('entrytype_id[]', 'Entry Type', 'required');
        $this->form_validation->set_rules('regtype_id[]', 'Registration Type', 'required');
        $this->form_validation->set_rules('user_id', 'Contact Person', 'required|numeric|greater_than[0]', ["greater_than" => "Please select a Contact Person"]);

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $id = $this->edition_model->set_edition($action, $edition_id, [], false);
            if ($id) {
                $alert = "<b>" . $this->input->post('edition_name') . "</b> has been successfully saved";
                $status = "success";
                if ($action == "edit") {
                    $new_edition_detail = $this->edition_model->get_edition_detail($id);
                    if ($this->input->post('edition_status') !== $this->data_to_view['edition_detail']['edition_status']) {
                        $this->race_status_update(array_keys($this->data_to_view['race_list']), $this->input->post('edition_status'));
                        $alert .= "<br>Status change on races also actioned";
                    }
                    // RACES FLAT
                    if ($this->input->post('races') !== NULL) {
                        $race_data = $this->set_races_from_edition($this->input->post('races'), $this->data_to_view['race_list'], $new_edition_detail);
                    }
                    // DATES FLAT
                    if ($this->input->post('dates') !== NULL) {
                        $this->set_dates_from_edition($this->input->post('dates'), $this->data_to_view['date_list'], $new_edition_detail);
                    }
                }
                // DATES checks
                $this->check_start_end_dates($id, $new_edition_detail, $this->input->post('entrytype_id'), $this->input->post('regtype_id'));
                // CEHCK TAGS
                $this->set_tags_on_post($id, $new_edition_detail, $race_data);
            } else {
                $alert = "Error committing to the database";
                $status = "danger";
            }

            // if copy date
            if (array_key_exists("copy_date", $this->input->post())) {
                $this->return_url = base_url($this->input->post("copy_date"));
            }
            // save_only takes you back to the edit page.
            if (array_key_exists("save_only", $this->input->post())) {
                $this->return_url = base_url("admin/edition/create/edit/" . $id . "#" . $this->input->post("save_only"));
            }
            $this->session->set_flashdata(['alert' => $alert, 'status' => $status,]);
            redirect($this->return_url);
        }
    }

    private function set_tags_on_post($edition_id, $edition_data, $race_data) {
        $this->load->model('admin/tag_model');
        $this->load->model('admin/tagtype_model');

        $tagtype_list = $this->tagtype_model->get_tagtype_list();
        foreach ($tagtype_list as $tagtype_id => $tagtype) {
            $tagtype_arr[$tagtype['tagtype_name']] = $tagtype_id;
        }
        // SET TAGS
        // get race distance tags
        foreach ($race_data as $race_id => $race) {
            $tags[trim($race['race_name'])] = $tagtype_arr['race_name'];
            $distance = $race['race_distance'] + 0;
            $tags[$distance . "km"] = $tagtype_arr['race_distance'];
        }
        unset($tagtype_arr['race_distance']);
        unset($tagtype_arr['race_name']);

        // edition_year
        $tags[fdateYear($edition_data['edition_date'])] = $tagtype_arr['edition_year'];
        unset($tagtype_arr['edition_year']);

        // edition_month
        $tags[fdateMonth($edition_data['edition_date']) . " " . fdateYear($edition_data['edition_date'])] = $tagtype_arr['edition_month'];
        unset($tagtype_arr['edition_month']);

        // REST of fields on flat edition_data array
        foreach ($tagtype_arr as $tagfield_name => $tagfield_id) {
            $tags[$edition_data[$tagfield_name]] = $tagfield_id;
        }

        // CLEAR EDITION TAGS
        $stats['new_tag']=0;
        $stats['edition_tag_link']=0;
        $this->tag_model->clear_edition_tags($edition_id);
        foreach ($tags as $tag => $tagtype_id) {
            // CHECK IF TAGS EXISTS, ELSE ADD
            $tag=trim($tag);
            if (!empty($tag)) {
                $tag_id = $this->tag_model->exists($tag);
                if (!$tag_id) {
                    $data = [
                        'tag_name' => $tag,
                        'tagtype_id' => $tagtype_id,
                        'tag_status' => 1,
                    ];                    
                    $tag_id = $this->tag_model->set_tag("add", 0, $data);
                    $stats['new_tag']++;
                }
                // ADD TAG
                $this->tag_model->set_edition_tag($edition_id, $tag_id);
                $stats['edition_tag_link']++;
            }
        }
//        wts($tagtype_arr);
//        wts($tags);
//        echo $edition_id;
//        wts($race_data);
//        wts($edition_data, true);
//        return($stats);
    }

    public function race_status_update($race_id_arr, $status_id) {
        $this->load->model('admin/race_model');
        return $this->race_model->update_race_status($race_id_arr, $status_id);
    }

    private function set_races_from_edition($race_list_post, $race_list_current, $edition_info) {
        $this->load->model('admin/race_model');
        foreach ($race_list_post as $race_id => $race) {
            $combine = array_merge($race_list_current[$race_id], $race);
            $remove = ['created_date', 'updated_date', 'edition_date', 'edition_name', 'racetype_name', 'racetype_abbr', 'race_color', 'has_results', 'file_list'];
            $race_data = array_diff_key($this->race_fill_blanks($combine, $edition_info), array_flip($remove));
            $this->race_model->set_race("edit", $race_id, $race_data);
            $return[$race_id] = $race_data;
        }
        return $return;
    }

    private function set_dates_from_edition($date_list_post, $date_list_current, $edition_info) {
        $this->load->model('admin/date_model');
        foreach ($date_list_post as $date_id => $date_array) {
            // hierdie hieronder is slegs om datum te verander soos nodig na die post
            foreach ($date_array as $field => $value) {
                $datetype_id = $date_list_current[$date_id]['datetype_id'];
                switch ($datetype_id) {
                    case 4:
                    case 10:
                        if ($field == "date_end") {
                            $new_date_array["date_end"] = fdateShort($date_array['date_start']) . " " . $value . ":00";
                        } else {
                            $new_date_array[$field] = $value;
                        }
                        break;
                    case 6:
                    case 9:
                        $new_date_array[$field] = fdateShort($edition_info['edition_date']) . " " . $value . ":00";
                        break;
                    default:
                        $new_date_array[$field] = $value;
                        break;
                }
            }
            $combine = array_merge($date_list_current[$date_id], $new_date_array);
            $remove = ['created_date', 'updated_date', 'datetype_name', 'datetype_display', 'datetype_status', 'venue_name'];
            $date_data = array_diff_key($combine, array_flip($remove));

            $this->date_model->set_date("edit", $date_id, $date_data);
            if ($date_id == 0) {
                echo "POST";
                wts($date_list_post);
                echo "NEW DATE ARRAY";
                wts($new_date_array);
                echo "COMBINE";
                wts($combine);
                echo "WHAT WILL BE WRITTEN TO DB";
                wts($date_data);
                echo "CURRENT DATE LIST";
                wts($date_list_current);
                die();
            }
        }
    }

    public function name_check($str) {
        $year = substr($str, -4);
        $valid = true;

        if (strtotime($year) === false) {
            $valid = false;
        }
        return $valid;
    }

    private function check_start_end_dates($e_id, $edition_details, $entrytype_list, $regtype_list) {
        $this->load->model('admin/date_model');

        $datetype_id_list = [1]; // edition start and end dates
        // check vir online entries
        if (in_array(4, $entrytype_list)) {
            $datetype_id_list[] = 3;
        }
        // PRE entries
        if (in_array(3, $entrytype_list)) {
            $datetype_id_list[] = 4;
        }
        // OTD entries
        if (in_array(1, $entrytype_list)) {
            $datetype_id_list[] = 6;
        }
        // Manual entries
        if (in_array(2, $entrytype_list)) {
            $datetype_id_list[] = 5;
        }
        // OTD registration
        if (in_array(1, $regtype_list)) {
            $datetype_id_list[] = 9;
        }
        // PRE registration
        if (in_array(2, $regtype_list)) {
            $datetype_id_list[] = 10;
        }
        // No-subs
        if ($edition_details['edition_entry_nosubstitution']) {
            $datetype_id_list[] = 7;
        }
        // No-downgrades
        if ($edition_details['edition_entry_nodowngrade']) {
            $datetype_id_list[] = 8;
        }
        // check if dates is loaded, else add
        foreach ($datetype_id_list as $datetype_id) {
            if (!$this->date_model->exists("edition", $e_id, $datetype_id)) {
                $date_data = [
                    'date_start' => $edition_details['edition_date'],
                    'date_end' => $edition_details['edition_date'],
                    'datetype_id' => $datetype_id,
                    'date_linked_to' => "edition",
                    'linked_id' => $e_id,
                ];
                $this->date_model->set_date("add", NULL, $date_data, false);
            }
        }
    }

    // DELETE EDITION
    public function delete($edition_id = 0) {

        if (($edition_id == 0) AND ( !is_int($edition_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $edition_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get edition detail for nice delete message
        $edition_detail = $this->edition_model->get_edition_detail($edition_id);
        // delete record
        $db_del = $this->edition_model->remove_edition($edition_id);

        if ($db_del) {
            $msg = "Edition has successfully been deleted: " . $edition_detail['edition_name'];
            $status = "success";
        } else {
            $msg = "Error in deleting the record:'.$edition_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

    // ==========================================================================================
    // EDITION COPY FUCNTIONS
    // ==========================================================================================
    // MAKE A COPY OF AN OLD EDITION
    public function copy($id) {
        $this->load->model('admin/user_model');
        $this->load->model('admin/event_model');
        $this->load->model('admin/race_model');
        $this->load->model('admin/date_model');
        $this->load->model('admin/sponsor_model');
        $this->load->model('admin/entrytype_model');
        $this->load->model('admin/asamember_model');
        $this->load->model('admin/file_model');

        // get data
        $race_list = $this->race_model->get_race_list($id);
        $edition_detail = $this->edition_model->get_edition_detail($id);
        $file_list = $this->file_model->get_file_list("edition", $id, true);

        // create new edition data
        $name = substr($edition_detail['edition_name'], 0, -5);
        $year = substr($edition_detail['edition_name'], -4);
        $year++;

        $edition_data['edition_name'] = $name . " " . $year;
        $edition_data['edition_slug'] = url_title($edition_data['edition_name']);
        $edition_data['edition_status'] = 1;
        $edition_data['edition_info_status'] = 14;
        $edition_data['edition_date'] = $this->get_new_date($edition_detail['edition_date']);

        $edition_data['edition_address'] = $edition_detail['edition_address'];
        $edition_data['edition_address_end'] = $edition_detail['edition_address_end'];
        $edition_data['event_id'] = $edition_detail['event_id'];
        $edition_data['edition_gps'] = $edition_detail['edition_gps'];
        $edition_data['edition_isfeatured'] = $edition_detail['edition_isfeatured'];
        $edition_data['user_id'] = $edition_detail['user_id'];
        $edition_data['edition_asa_member'] = $edition_detail['edition_asa_member'];

        $e_id = $this->edition_model->set_edition("add", NULL, $edition_data, false);

        // create new RACES
        foreach ($race_list as $race) {
            $race_data['race_distance'] = $race['race_distance'];
            $race_data['race_time_start'] = $race['race_time_start'];
            $race_data['race_status'] = $race['race_status'];
            $race_data['racetype_id'] = $race['racetype_id'];
            $race_data['edition_id'] = $e_id;
            $this->race_model->set_race("add", NULL, $race_data, false);
        }

        // create start and end DATES
        $date_data = [
            'date_start' => $edition_data['edition_date'],
            'date_end' => $edition_data['edition_date'],
            'datetype_id' => 1,
            'date_linked_to' => "edition",
            'linked_id' => $e_id,
        ];
        $this->date_model->set_date("add", NULL, $date_data, false);
//        $date_data = [
//            'date_date' => $edition_data['edition_date'],
//            'datetype_id' => 2,
//            'date_linked_to' => "edition",
//            'linked_id' => $e_id,
//        ];
//        $this->date_model->set_date("add", NULL, $date_data, false);
        // copy LOGO over
        if (isset($file_list[1])) {
            $file_data = $file_list[1][0];
            $file_data['linked_id'] = $e_id;
            $to_remove = ['file_id', 'created_date', 'updated_date', 'filetype_name', 'filetype_helptext', 'filetype_buttontext'];
            $date_to_set = array_diff_key($file_data, array_flip($to_remove));
            $file_id = $this->file_model->set_file([], $date_to_set);

            $src = "./uploads/edition/" . $id . "/" . $file_data['file_name'];
            $dest = "./uploads/edition/" . $e_id . "/" . $file_data['file_name'];
            // check new folder
            $dest_folder = "./uploads/edition/" . $e_id;
            if (!file_exists($dest_folder)) {
                if (!mkdir($dest_folder, 0777, true)) {
                    return false;
                }
            }
            copy($src, $dest);
        }

        if ($e_id) {
            $alert = "Edition information has been successfully added";
            $status = "success";
            $return_url = base_url("admin/edition/create/edit/" . $e_id);
        } else {
            $alert = "Error trying to add <b>" . $edition_data['edition_name'] . "</b> to the database";
            $status = "danger";
            $return_url = base_url("admin/dashboard");
        }

        $this->session->set_flashdata([
            'alert' => $alert,
            'status' => $status,
        ]);

        redirect($return_url);
        die();
    }

    private function get_new_date($old_date) {
        $timestamp = strtotime("+1 years", strtotime($old_date));
        $year = date('Y', strtotime($old_date));
        $month = date('m', strtotime($old_date));
        $day = date('d', strtotime($old_date));
        $year++;
        // set exception list where dates should not move
        $exception_list = ["0101", "0321", "0427", "0501", "0616", "0809", "0924", "1216", "1226", "1231"];
        if (!in_array($month . $day, $exception_list)) {
            // check for leap year
            if (date('L', strtotime("$year-01-01"))) {
                if ($month > 2) {
                    $timestamp = $timestamp - 172800; // 2 dae in sekondes
                } else {
                    $timestamp = $timestamp - 86400; // 1 dag in sekondes
                }
            } else {
                $timestamp = $timestamp - 86400; // 1 dae\g in sekondes
            }
        }
        return date("Y-m-d H:i:s", $timestamp);
    }

    // ==========================================================================================
    // TEMP FIX SCRIPTS
    // ==========================================================================================
    // temp method - fix wehere end-date is empty
    function end_date_fix() {
        // function to port old URLs from fields directly on Edition to URl table
        $this->load->model('admin/edition_model');
        $edition_list = $this->edition_model->get_edition_list();
        $n = 0;
        $r = 0;
        foreach ($edition_list as $e_id => $edition) {
            if (strtotime($edition['edition_date_end']) < 1) {

                $update = $this->edition_model->update_field($e_id, "edition_date_end", $edition['edition_date']);
                $n++;
            }
        }

        echo "Done<br>";
        echo $n . " dates were updated<br>";
    }

    // temp method - fix wehere results status
    function results_status_fix() {
        // function to port old URLs from fields directly on Edition to URl table
        $this->load->model('admin/edition_model');
        $edition_list = $this->edition_model->get_edition_list();
        $l = 0;
        $nl = 0;
        foreach ($edition_list as $e_id => $edition) {
            if ($edition['edition_results_isloaded']) {
                $update = $this->edition_model->update_field($e_id, "edition_results_status", 11);
                $l++;
            } else {
                $update = $this->edition_model->update_field($e_id, "edition_results_status", 10);
                $nl++;
            }
        }

        echo "Done<br>";
        echo "<b>" . $nl . "</b> statuse were updated to NOT LOADED<br>";
        echo "<b>" . $l . "</b> statuse were updated to LOADED<br>";
    }

    // temp method - fix wehere results status
    function info_status_fix() {
        // function to port old URLs from fields directly on Edition to URl table
        $this->load->model('admin/edition_model');
        $edition_list = $this->edition_model->get_edition_list();
        $future = 0;
        $verified = 0;
        foreach ($edition_list as $e_id => $edition) {
            // gee nuwe veld die resutls status value
//            $this->edition_model->update_field($e_id, "edition_info_status", $edition['edition_results_status']);
            // as event nog in die toekoms is, gee dit 'n status van Preliminary
            if ($edition['edition_date'] > date("Y-m-d H:i:s")) {
                $future++;
                $this->edition_model->update_field($e_id, "edition_info_status", 14);

                // as info confirmed is, set status na Verified
                if ($edition['tbr_edition_info_isconfirmed']) {
                    $verified++;
                    $this->edition_model->update_field($e_id, "edition_info_status", 16);
                }
            }
        }

        echo "Done<br>";
        echo "<b>" . $future . "</b> statuse were updated to Prelim<br>";
        echo "<b>" . $verified . "</b> statuse were updated to Verified<br>";
    }

    // ==========================================================================================
    // TEMP DATA GENERATION SCRIPTS
    // ==========================================================================================
    // create slugs for all the editions
    function generate_slugs() {
        // function to port old URLs from fields directly on Edition to URl table
        $this->load->model('admin/edition_model');
        $edition_list = $this->edition_model->get_edition_list();
        $n = 0;
        foreach ($edition_list as $e_id => $edition) {
            $this->edition_model->update_field($e_id, "edition_slug", url_title($edition['edition_name']));
            $n++;
        }

        echo "Done<br>";
        echo "<b>" . $n . "</b> slugs were updated<br>";
    }

    // create slugs for all the editions
    function generate_gps() {
        // function to port old URLs from fields directly on Edition to URl table
        $this->load->model('admin/edition_model');
        $edition_list = $this->edition_model->get_edition_list();
        $n = 0;
        foreach ($edition_list as $e_id => $edition) {
            $gps = str_replace(" ", "", trim($edition['latitude_num']) . "," . trim($edition['longitude_num']));
            $this->edition_model->update_field($e_id, "edition_gps", $gps);
            $n++;
        }

        echo "Done<br>";
        echo "<b>" . $n . "</b> gps values were updated<br>";
    }

    // create tags on all races
    function generate_tags() {
        $this->load->model('admin/edition_model');
        $this->load->model('admin/race_model');
        $query_params = [
            "order_by" => ["edition_status" => "1"],
        ];
        $edition_list = $this->edition_model->get_edition_list_new($query_params);

        $acc_stats['new_tag']=0;
        $acc_stats['edition_tag_link']=0;
        foreach ($edition_list as $edition_id => $edition_data) {
            $race_list = $this->race_model->get_race_list($edition_id, 1);
            $stats=$this->set_tags_on_post($edition_id, $edition_data, $race_list);
            $acc_stats['new_tag']=$acc_stats['new_tag']+$stats['new_tag'];
            $acc_stats['edition_tag_link']=$acc_stats['edition_tag_link']+$stats['edition_tag_link'];
        }
        
        echo "<p>".$acc_stats['new_tag']." new tags generated</p>";
        echo "<p>".$acc_stats['edition_tag_link']." new tag links to editions</p>";
    }

    // move edition dates to dates table
    function move_edition_dates() {
        // function to port old URLs from fields directly on Edition to URl table
        $this->load->model('admin/edition_model');
        $this->load->model('admin/date_model');
        $this->load->model('admin/datetype_model');
        $field_list = ["edition_id", "edition_name", "edition_date", "tbr_edition_date_end", "tbr_edition_entries_date_open", "tbr_edition_entries_date_close"];
        $query_params = [
            "order_by" => ["edition_date" => "DESC"],
        ];
        $edition_list = $this->edition_model->get_edition_list_new($query_params, $field_list);
        $date_list = $this->date_model->get_date_list("edition", 0, true);

        $counter = [];
        $date_fields_to_move = [
            1 => [
                "date_start" => "edition_date",
                "date_end" => "edition_date",
            ],
            3 => [
                "date_start" => "tbr_edition_entries_date_open",
                "date_end" => "tbr_edition_entries_date_close",
            ],
        ];
//        wts($date_fields_to_move);
//        wts($edition_list);
//        die();
        // run deur edition list
        foreach ($edition_list as $e_id => $edition) {
            foreach ($date_fields_to_move as $datetype_id => $edition_field_array) {
//                if ((!isset($date_list[$datetype_id][$e_id])) && ($edition[$edition_field])) 
//                if ($edition[$edition_field]) {

                $date_data = array(
                    'datetype_id' => $datetype_id,
                    'date_linked_to' => "edition",
                    'linked_id' => $e_id,
                );
                foreach ($edition_field_array as $des_field => $edition_field) {
                    if (isset($edition[$edition_field])) {
                        $date_data[$des_field] = $edition[$edition_field];
                    }
                }
//                wts($date_data);
//                die();
                if (isset($date_data['date_start'])) {
                    $this->date_model->set_date("add", NULL, $date_data);
                    if (!isset($counter[$edition_field])) {
                        $counter[$edition_field] = 0;
                    }
                    $counter[$edition_field] ++;
                }
//                }
            }
        }

        echo "<b>Done</b><br>";
        if (empty($counter)) {
            echo "No dates to move";
        }
        foreach ($counter as $field => $count) {
            echo "<b>$count</b> $field dates moved<br>";
        }
    }

    // move data from old edition_description field to new edition_general_detail 
    function port_description() {
        // function to port old URLs from fields directly on Edition to URl table
        $this->load->model('admin/edition_model');
        $edition_list = $this->edition_model->get_edition_list();
        $n = 0;
        foreach ($edition_list as $e_id => $edition) {
            ;
            $this->edition_model->update_field($e_id, "edition_general_detail", $edition['edition_description']);
            $n++;
        }

        echo "Done<br>";
        echo "<b>" . $n . "</b> description fields were updated<br>";
    }

    // copy address data from start to end
    function port_address() {
        $this->load->model('admin/edition_model');
        $edition_list = $this->edition_model->get_edition_list();
        $n = 0;
        foreach ($edition_list as $e_id => $edition) {
            $this->edition_model->update_field($e_id, "edition_address_end", $edition['edition_address']);
            $n++;
        }

        echo "Done<br>";
        echo "<b>" . $n . "</b> address fields were updated<br>";
    }

    // set registration & entry type
    function set_entrytype_regtype_sponsor() {
        $this->load->model('admin/regtype_model');
        $this->load->model('admin/entrytype_model');
        $this->load->model('admin/sponsor_model');
        $this->load->model('admin/edition_model');
        $edition_list = $this->edition_model->get_edition_list_new();
        $e = $s = $r = 0;
        foreach ($edition_list as $e_id => $edition) {
            // REG TYPE
            $regtype_list = $this->regtype_model->get_edition_regtype_list($e_id);
            // if no reg_type
            if (key($regtype_list) == 0) {
                $this->regtype_model->set_edition_regtype($e_id, 3);
                $r++;
            }
            // ENTRY TYPE
            $entrytype_list = $this->entrytype_model->get_edition_entrytype_list($e_id);
            // if no entry_type
            if (key($entrytype_list) == 0) {
                $this->entrytype_model->set_edition_entrytype($e_id, 5);
                $e++;
            }
            // SPONSOR
            $sponsor_list = $this->sponsor_model->get_edition_sponsor_list($e_id);
            // if no reg_type
            if (key($sponsor_list) == 0) {
                $this->sponsor_model->set_edition_sponsor($e_id, 4);
                $s++;
            }
        }

        echo "Done<br>";
        echo "<b>" . $r . "</b> regtypes fields were updated<br>";
        echo "<b>" . $e . "</b> entrytypes fields were updated<br>";
        echo "<b>" . $s . "</b> sponsors fields were updated<br>";
    }

}
