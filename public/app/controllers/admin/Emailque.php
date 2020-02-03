<?php

class Emailque extends Admin_Controller {

    private $return_url = "/admin/emailque/view";
    private $create_url = "/admin/emailque/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/emailque_model');
        $this->ini_array = parse_ini_file("server_config.ini", true);
    }

    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            redirect($this->return_url);
        }
    }

    public function view($status_id = 4) {
        // load helpers / libraries
        $this->load->library('table');

        $this->return_url = "/admin/emailque/view/" . $status_id;

        $this->data_to_view["emailque_status"] = $this->emailque_model->get_emailstatus_name($status_id);
        $this->data_to_view["emailque_data"] = $this->emailque_model->get_emailque_list(0, $status_id);
        $this->data_to_view['heading'] = ["ID", "Subject", "To Address", "To Name", "Updated", "Actions"];

        if ($status_id == 4) {
            $this->data_to_view['create_link'] = $this->create_url;
            $this->data_to_header['page_action_list'] = [
                [
                    "name" => "Compose Email",
                    "icon" => "envelope-open",
                    "uri" => "emailque/create/add",
                ],
            ];
        }
        $this->data_to_header['title'] = "List of emails in " . ucfirst($this->data_to_view["emailque_status"]) . " status";

        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Email Module" => "/admin/emailque",
            $this->data_to_view["emailque_status"]." Emails" => "",
        ];

        // set $action array in controller instead of view
        if (!(empty($this->data_to_view["emailque_data"]))) {
            foreach ($this->data_to_view["emailque_data"] as $emailque_id => $data_entry) {
                // first set normal action array
                $this->data_to_view['action_array'][$emailque_id] = [
                    [
                        "url" => "/admin/emailque/create/edit/" . $data_entry['emailque_id'],
                        "text" => "Edit",
                        "icon" => "icon-pencil",
                    ],
                    [
                        "url" => "/admin/emailque/delete/" . $data_entry['emailque_id']."/".$data_entry['emailque_status'],
                        "text" => "Delete",
                        "icon" => "icon-close",
                        "confirmation_text" => "<b>Are you sure?</b>",
                    ],
                ];

                switch ($data_entry['emailque_status']) {
                    case 4: // draft
                        break;
                    case 5: // pending
                        unset($this->data_to_view['action_array'][$emailque_id]);
                        $this->data_to_view['action_array'][$emailque_id][0] = [
                            "url" => "/admin/emailque/status/$emailque_id/4",
                            "text" => "Cancel send",
                            "icon" => "icon-ban",
                        ];
                        break;
                    case 6: // send
                        $this->data_to_view['action_array'][$emailque_id][0] = [
                            "url" => "/admin/emailque/resend/" . $data_entry['emailque_id'],
                            "text" => "Resend Email",
                            "icon" => "icon-envelope",
                        ];
                        break;
                    case 7: // failed
                        $this->data_to_view['action_array'][$emailque_id][0] = [
                            "url" => "/admin/emailque/resend/" . $data_entry['emailque_id'],
                            "text" => "Resend Email",
                            "icon" => "icon-envelope",
                        ];
                        break;
                    default:
                        break;
                }
            }
        }

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
        $this->load->view("/admin/emailque/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id = 0) {
        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');
        // set data
        $this->data_to_header['title'] = "Email Compose";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        $this->data_to_header['css_to_load'] = array("assets/admin/plugins/bootstrap-summernote/summernote.css",);
        $this->data_to_footer['js_to_load'] = array("assets/admin/plugins/moment.min.js", "assets/admin/plugins/bootstrap-summernote/summernote.min.js",);
        $this->data_to_footer['scripts_to_load'] = array("assets/admin/scripts/components-editors.js",);

        if ($action == "edit") {
            $this->data_to_view['emailque_detail'] = $this->emailque_model->get_emailque_detail($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {
            $this->data_to_view['emailque_detail']['emailque_status'] = 4;
        }
        // set return URL
        $this->return_url = "/admin/emailque/view/" . $this->data_to_view['emailque_detail']['emailque_status'];
        // set validation rules
        $this->form_validation->set_rules('emailque_subject', 'Subject', 'required');
        $this->form_validation->set_rules('emailque_to_address', 'To Address', 'required|valid_email');
        $this->form_validation->set_rules('emailque_body', 'Email body', 'required');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            switch ($this->input->post('save-btn')) {
                case "save_only":
                case "save_close":
                    $mail_status = 4;
                    break;
                case "send_mail":
                    $mail_status = 5;
                    break;
            }
            $data = array(
                'emailque_subject' => $this->input->post('emailque_subject'),
                'emailque_to_address' => $this->input->post('emailque_to_address'),
                'emailque_to_name' => $this->input->post('emailque_to_name'),
                'emailque_body' => $this->input->post('emailque_body'),
                'emailque_status' => $mail_status,
                'emailque_from_address' => $this->ini_array['email']['from_address'],
                'emailque_from_name' => $this->ini_array['email']['from_name'],
            );
            $set = $this->emailque_model->set_emailque($action, $id, $data);
            if ($set) {
                $alert = "Email has been " . $action . "ed";
                $status = "success";
            } else {
                $alert = "Error committing to the database";
                $status = "danger";
            }

            // take person back to the right screen
            switch ($this->input->post('save-btn')) {
                case "save_only":
                    $this->return_url = base_url("admin/emailque/create/edit/" . $id);
                    break;
                case "send_mail":
                    $this->return_url = base_url("admin/emailque/view/5");
                    $alert = "Email set to pending";
                    $status = "warning";
                    break;
            }

            $this->session->set_flashdata([
                'alert' => $alert,
                'status' => $status,
            ]);
            redirect($this->return_url);
        }
    }

    public function delete($emailque_id=0,$status_id=4) {

        $this->return_url=$this->return_url."/".$status_id;
        
        if (($emailque_id == 0) AND ( !is_int($emailque_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $emailque_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get emailque detail for nice delete message
        $emailque_detail = $this->emailque_model->get_emailque_detail($emailque_id);
        // delete record
        $db_del = $this->emailque_model->remove_emailque($emailque_id);

        if ($db_del) {
            $msg = "Email has successfully been deleted: " . $emailque_detail['emailque_subject'];
            $status = "warning";
        } else {
            $msg = "Error in deleting the record:'.$emailque_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

    // change status with URL call
    public function status($emailque_id, $emailque_status) {
        $range = [4, 5, 6, 7];
        $valid_id = $this->emailque_model->check_id($emailque_id);
        if ((in_array($emailque_status, $range)) && ($valid_id)) {
            $status_update = $this->emailque_model->set_emailque_status($emailque_id, $emailque_status);
            if ($status_update) {
                $msg = "Email status successfully updated";
                $status = "success";
                $this->return_url = base_url("admin/emailque/view/$emailque_status");
            } else {
                $msg = "Update to database failed";
                $status = "danger";
                $this->return_url = base_url("admin/emailque/view/4");
            }
        } else {
            $msg = "Status not in range or invalid ID";
            $status = "danger";
            $this->return_url = base_url("admin/emailque/view/4");
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

    // resend email by copy
    public function resend($emailque_id) {
        $new_id = $this->emailque_model->copy_email($emailque_id);
        $status_update = $this->emailque_model->set_emailque_status($new_id, 4);
        if ($status_update) {
            $msg = "Email successfully copied";
            $status = "success";
            $this->return_url = base_url("admin/emailque/create/edit/$new_id");
        } else {
            $msg = "Email copy failed";
            $status = "danger";
            $this->return_url = base_url("admin/emailque/view/4");
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

}
