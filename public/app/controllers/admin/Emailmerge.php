<?php

class Emailmerge extends Admin_Controller {

    private $return_url = "/admin/emailmerge/view";
    private $create_url = "/admin/emailmerge/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/emailmerge_model');
        $this->ini_array = parse_ini_file("server_config.ini", true);
    }

    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            redirect($this->return_url);
        }
    }

    public function view() {
        // load helpers / libraries
        $this->load->library('table');
        $this->data_to_view['heading'] = ["ID", "Subject", "Status", "Count", "Updated", "Actions"];
        $this->data_to_header['title'] = "List of Mail Merges";

        $this->data_to_view["emailmerge_data"] = $this->emailmerge_model->get_emailmerge_list();
        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Create Merge",
                "icon" => "envelope-open",
                "uri" => "emailmerge/wizard",
            ],
        ];
        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Email Merge" => "/admin/emailmerge",
            "List" => "",
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
        $this->load->view("/admin/emailmerge/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function wizard() {
        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('admin/emailtemplate_model');
        $this->load->model('admin/usersubscription_model');

        // set data
        $this->data_to_header['title'] = $this->data_to_view['title'] = "Setup Mail Merge";
        $this->data_to_view['action'] = "add";
        $this->data_to_view['form_url'] = "/admin/emailmerge/wizard";

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Email Merge" => "/admin/emailmerge",
            "Wizard" => "",
        ];

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

        $this->data_to_view['emailtemplate_dropdown'] = $this->emailtemplate_model->get_emailtemplate_dropdown();
        $this->data_to_view['linked_to_dropdown'] = $this->emailmerge_model->get_linked_to_dropdown(7);
        $this->data_to_view['linked_to_list'] = $this->emailmerge_model->get_linked_to_list(7);

        // unset linked to we are not using for now
        $unset_arr = [1, 3, 4, 5, 6];
        foreach ($unset_arr as $unset) {
            $name = $this->data_to_view['linked_to_list'][$unset];
            unset($this->data_to_view['linked_to_list'][$unset]);
            unset($this->data_to_view['linked_to_dropdown'][$name]);
        }

        // dynamically get drop downs using the linked_to_table
        foreach ($this->data_to_view['linked_to_list'] as $linked_to_id => $linked_to_name) {
            $dropdown = $linked_to_name . "_dropdown";
            $model = $linked_to_name . "_model";
            $method = "get_" . $linked_to_name . "_dropdown";

            $this->load->model("admin/" . $model);
            $this->data_to_view[$dropdown] = $this->$model->$method();
            $this->data_to_view[$dropdown][0] = "All";
        }

        // set validation rules
        $this->form_validation->set_rules('linked_to', 'Linked To', 'required');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view("/admin/emailmerge/wizard", $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            // SET merge
            if ($this->input->post('emailtemplate_id') > 0) {
                $emailtemplate = $this->emailtemplate_model->get_emailtemplate_detail($this->input->post('emailtemplate_id'));
            } else {
                $emailtemplate['emailtemplate_name'] = "";
                $emailtemplate['emailtemplate_body'] = "";
            }
            // get type of ID to use and get USER list
            $id_name = $this->input->post('linked_to') . "_id";
            $user_arr = $this->usersubscription_model->get_usersubscription_list($this->input->post('linked_to'), $this->input->post($id_name));
            $user_str = "";
            if (!empty($user_arr)) {
                foreach ($user_arr as $user) {
                    $user_list[] = $user['user_id'];
                }
                $user_str = implode(",", $user_list);
            }
            // create email merge
            $merge_data = array(
                "emailmerge_status" => 4,
                "emailmerge_subject" => $emailtemplate['emailtemplate_name'],
                "emailmerge_body" => $emailtemplate['emailtemplate_body'],
                "emailmerge_recipients" => $user_str,
                "emailmerge_linked_to" => $this->input->post('linked_to'),
                "linked_id" => $this->input->post($id_name),
            );
            $emailmerge_id = $this->emailmerge_model->set_emailmerge("add", 0, $merge_data);

//            wts($_POST);
//            wts($emailtemplate);
//            wts($user_list);
//            wts($merge_data);

            redirect($this->create_url . "/edit/" . $emailmerge_id);
        }
    }

    public function create($action, $id = 0) {
        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('admin/user_model');
        // set data
        $this->data_to_header['title'] = $this->data_to_view['title'] = "Mail Merge Edit";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Email Merge" => "/admin/emailmerge",
            "Edit" => "",
        ];

        $this->data_to_header['css_to_load'] = array("assets/admin/plugins/bootstrap-summernote/summernote.css",);
        $this->data_to_footer['js_to_load'] = array("assets/admin/plugins/moment.min.js", "assets/admin/plugins/bootstrap-summernote/summernote.min.js",);
        $this->data_to_footer['scripts_to_load'] = array("assets/admin/scripts/components-editors.js",);


        if ($action == "edit") {
            $this->data_to_view['emailmerge_detail'] = $this->emailmerge_model->get_emailmerge_detail($id);
            $user_arr = explode(",", $this->data_to_view['emailmerge_detail']['emailmerge_recipients']);
//            $this->data_to_view['user_dropdown'] = $this->user_model->get_user_dropdown(NULL, $user_arr);
            $this->data_to_view['user_dropdown'] = $this->user_model->get_user_dropdown();
        } else {
            die("please use wizard");
        }

        // Create test merge
        $emailmerge_data = $this->emailmerge_model->get_emailmerge_detail($this->data_to_view['emailmerge_detail']['emailmerge_id']);
        $merge_data = $this->get_merge_data(60, $emailmerge_data['emailmerge_linked_to'], $emailmerge_data['linked_id']); // 60 = info@roadrunning.co.za
        $this->data_to_view['test_merge_body'] = $this->fill_variables($emailmerge_data['emailmerge_body'], $merge_data);

        // set validation rules
        $this->form_validation->set_rules('emailmerge_subject', 'Subject', 'required');
        $this->form_validation->set_rules('emailmerge_body', 'Body', 'required');
        $this->form_validation->set_rules('emailmerge_recipients[]', 'Recipients', 'required',
                array('required' => 'You need to select at least one recipient'));

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $recipient_arr = $this->input->post('emailmerge_recipients');
            asort($recipient_arr);
            $recipient_str = implode(",", $recipient_arr);
            $data = array(
                'emailmerge_subject' => $this->input->post('emailmerge_subject'),
                'emailmerge_body' => $this->input->post('emailmerge_body'),
                'emailmerge_recipients' => $recipient_str,
            );
            $return_id = $this->emailmerge_model->set_emailmerge($action, $id, $data);

            if ($return_id) {
                $alert = "Mail Merge has been " . $action . "ed";
                $status = "success";

                // take person back to the right screen
                switch ($this->input->post('save-btn')) {
                    case "save_only":
                        $this->return_url = base_url("admin/emailmerge/create/edit/" . $return_id);
                        break;
                    case "test_merge":
                        $run_merge = $this->merge($return_id, true);
                        $this->return_url = base_url("admin/emailmerge/create/edit/" . $return_id);
                        $alert = $run_merge['alert'];
                        $msg = $run_alert['status'];
                        break;
                    case "test_html":
                        $run_merge = $this->merge($return_id, true, true);
                        break;
                    case "merge":
                        $run_merge = $this->merge($return_id);
                        $alert = $run_merge['alert'];
                        $msg = $run_alert['status'];
                        break;
                }
            } else {
                $alert = "Error committing to the database";
                $status = "danger";
            }

            $this->session->set_flashdata([
                'alert' => $alert,
                'status' => $status,
            ]);
            redirect($this->return_url);
        }
    }

    public function delete($emailmerge_id = 0) {

        if (($emailmerge_id == 0) AND ( !is_int($emailmerge_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $emailmerge_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get emailmerge detail for nice delete message
        $emailmerge_detail = $this->emailmerge_model->get_emailmerge_detail($emailmerge_id);
        // delete record
        $db_del = $this->emailmerge_model->remove_emailmerge($emailmerge_id);

        if ($db_del) {
            $msg = "Email has successfully been deleted: <b>" . $emailmerge_detail['emailmerge_name'] . "</b>";
            $status = "warning";
        } else {
            $msg = "Error in deleting the record:'.$emailmerge_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

    public function fetch_newsletter_data() {

        $this->load->model('admin/url_model');
        $this->load->model('admin/event_model');
        $newsletter_data = $this->event_model->get_event_data_newsletter();

        foreach ($newsletter_data as $period => $period_list) {
            foreach ($period_list as $year => $year_list) {
                foreach ($year_list as $month => $month_list) {
                    foreach ($month_list as $day => $edition_list) {
                        foreach ($edition_list as $id => $edition) {
                            $url_list = $this->url_model->get_url_list("edition", $id, true);
                            if (isset($url_list[5])) {
                                $edition['edition_online_entry'] = 1;
                            } else {
                                $edition['edition_online_entry'] = 0;
                            }

                            $edition_url_name = encode_edition_name($edition['edition_name']);
                            $edition['edition_url'] = base_url() . "event/" . $edition_url_name;
                            $new_newsletter_data[$period][$year][$month][$day][$id] = $edition;
                        }
                    }
                }
            }
        }
        return $new_newsletter_data;
    }

    public function formulate_newsletter_table($newsletter_data, $period, $is_newsletter = false) {
        $this->load->library('table');
        $this->load->model('admin/date_model');
        $date_list = $this->date_model->get_date_list("edition", 0, true);
        $this->table->set_template(ftable('newsletter_' . $period, $is_newsletter));
        switch ($period) {
            case "past":
                $colspan = 2;
                $headers_end = ["<b>Results loaded?</b>"];
                break;
            case "future":
                $colspan = 3;
                $headers_end = ["<b>Info Confirmed?</b>", "<b>Online entries open?</b>"];
                break;
        }
        foreach ($newsletter_data as $year => $year_list) {
            foreach ($year_list as $month => $month_list) {
                $cell = array('data' => "<b>$month</b>", 'colspan' => $colspan);
                $this->table->add_row($cell, "");
                $headers = ["<b>Date</b>", "<b>Event</b>",];
                $headers = array_merge($headers, $headers_end);
                $this->table->add_row($headers);
                foreach ($month_list as $day => $edition_list) {
                    foreach ($edition_list as $edition_id => $edition) {
                        $row['date'] = fdateDay($edition['edition_date']);
                        $row['name'] = "<a href='" . $edition['edition_url'] . "' target='_blank'>" . $edition['edition_name'] . "</a>";
                        switch ($period) {
                            case "past":
                                if ($edition['edition_info_status'] == 11) {
                                    $row['results'] = "Yes";
                                } else {
                                    $row['results'] = "No";
                                }
//                                $row['results'] = fyesNo($edition['edition_results_isloaded']);
                                break;
                            case "future":
                                if ($edition['edition_info_status'] == 16) {
                                    $row['info'] = "Yes";
                                } else {
                                    $row['info'] = "No";
                                }
                                if (isset($date_list[4][$edition_id])) {
                                    $row['entries'] = "Yes";
                                } else {
                                    $row['entries'] = "No";
                                }
//                                $row['info'] = fyesNo($edition['edition_info_isconfirmed']);
//                                $row['entries'] = fyesNo($edition['edition_online_entry']);
                                break;
                        }

                        $this->table->add_row($row);
                        unset($row);
                    }
                }
            }
        }
        return $this->table->generate();
    }

    public function fill_variables($text, $data_arr) {
        // to replace %name% with name in data_arr etc.
        $newsletter_data = $this->fetch_newsletter_data();
        $trans = array(
            "%name%" => $data_arr['name'],
            "%surname%" => $data_arr['surname'],
            "%email%" => $data_arr['email'],
            "%event_name%" => @$data_arr['edition_name'],
            "%event_name_proper%" => @$data_arr['event_name'],
            "%event_url%" => @$data_arr['edition_url'],
            "%event_date%" => @$data_arr['edition_date'],
            "%entries_close%" => @$data_arr['entries_close'],
            "%town_name%" => @$data_arr['town_name'],
            "%events_past%" => $this->formulate_newsletter_table($newsletter_data['past'], "past", true),
            "%events_future%" => $this->formulate_newsletter_table($newsletter_data['future'], "future", true),
            "%unsubscribe_url%" => $data_arr['unsubscribe_url'],
        );
        return strtr($text, $trans);
    }

    private function set_email_html($text, $merge_data) {
        $post_text = "<p>This email was sent to " . $merge_data['email'];
        if (isset($merge_data['unsubscribe_url'])) {
            $url = $merge_data['unsubscribe_url'];
            $post_text .= "<br><a href='$url'>Unsubscribe</a> from this list</p>";
        } else {
            $post_text .= "</p>";
        }        
        $html_body = $this->set_email_body($text, $post_text);
        return $html_body;
    }

    public function get_merge_data($user_id, $linked_to, $linked_id) {
        $this->load->model('admin/user_model');
        $user_data = $this->user_model->get_user_detail($user_id);
        $unsubscribe_url = $this->formulate_unsubscribe_url($user_id, $linked_to, $linked_id);
        // set main return array
        $merge_data = array(
            'id' => $user_id,
            'name' => $user_data['user_name'],
            'surname' => $user_data['user_surname'],
            'email' => $user_data['user_email'],
            'linked_to' => $linked_to,
            'linked_id' => $linked_id,
            'unsubscribe_url' => $unsubscribe_url,
        );
        // switch on the linked type
        switch ($linked_to) {
            case "newsletter":
                break;
            case "edition":
                $this->load->model('admin/edition_model');
                $this->load->model('admin/date_model');
                $edition_detail = $this->edition_model->get_edition_detail_lite($linked_id);
                $date_list=$this->date_model->get_date_list($linked_to,$linked_id,false,true);
                $merge_data['edition_name'] = $edition_detail['edition_name'];
                $merge_data['event_name'] = $edition_detail['event_name'];
                $merge_data['edition_date'] = fdateHumanFull($edition_detail['edition_date'], true);
                $merge_data['town_name'] = $edition_detail['town_name'];
                $url = $this->edition_model->get_edition_url_from_id($linked_id);
                $merge_data['edition_url'] = $url['edition_url'];
                if (isset($date_list[3])) {
                    $merge_data['entries_close'] = fdateHumanFull($date_list[3][0]['date_start'],true, true);
                }
                break;
            default:
                die("linked to not defined");
                break;
        }
        return $merge_data;
    }

    public function merge($emailmerge_id, $test = false, $stop = false) {

        // load user model
        $this->load->model('admin/emailque_model');
        // get the data
        $emailmerge_data = $this->emailmerge_model->get_emailmerge_detail($emailmerge_id);
        // get recipient list        
        if ($test) {
            $recipient_list = [60]; // 60 = info@roadrunning.co.za
        } else {
            $recipient_list = explode(",", $emailmerge_data['emailmerge_recipients']);
        }
        // loop through recipients
        foreach ($recipient_list as $user_id) {
            $merge_data = $this->get_merge_data($user_id, $emailmerge_data['emailmerge_linked_to'], $emailmerge_data['linked_id']);
            $body_text = $this->fill_variables($emailmerge_data['emailmerge_body'], $merge_data);
            $emailque_data = array(
                'emailque_subject' => $emailmerge_data['emailmerge_subject'],
                'emailque_to_address' => $merge_data['email'],
                'emailque_to_name' => $merge_data['name'] . " " . $merge_data['surname'],
                'emailque_body' => $this->set_email_html($body_text, $merge_data),
                'emailque_status' => 5,
                'emailque_from_address' => $this->ini_array['email']['from_address'],
                'emailque_from_name' => $this->ini_array['email']['from_name'],
            );

            if ($stop) {
                wts($emailque_data['emailque_body'], 1);
            }

//            wts($emailque_data);
//            die();
//            $email_list[$user_id]=$emailque_data;
            $emailque_id = $this->emailque_model->set_emailque("add", 0, $emailque_data);
        }

        if ($emailque_id) {
            $status = "success";
            if ($test) {
                $msg = "Test email successfully send";
            } else {
                $msg = "Merge was successfuly completed";
                $this->set_status($emailmerge_id, 8); // 8 = completed                 
            }
        } else {
            $msg = "Error creating merge:'.$emailmerge_id";
            $status = "danger";
        }

        $return = [
            'alert' => $msg,
            'status' => $status,
        ];

        return $return;

//        $this->session->set_flashdata('alert', $msg);
//        $this->session->set_flashdata('status', $status);
//        redirect($this->return_url);
    }

    public function set_status($emailmerge_id, $status_id) {
        $id = $this->emailmerge_model->set_emailmerge("edit", $emailmerge_id, ["emailmerge_status" => $status_id]);
    }

}
