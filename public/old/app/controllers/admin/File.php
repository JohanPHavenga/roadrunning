<?php

class File extends Admin_Controller {

    private $return_url = "/admin/file";
    private $create_url = "/admin/file/create";

    public function __construct() {
        parent::__construct();
        $this->load->model('file_model');
    }

    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->view($params);
        }
    }

    public function view() {
        // load helpers / libraries
        $this->load->library('table');
        // unset edition return url session
        $this->session->unset_userdata('edition_return_url');

        $this->data_to_view["file_data"] = $this->file_model->get_file_list();
        $this->data_to_view['heading'] = ["ID", "Filename", "Filetype", "Linked To", "ID", "Actions"];

        $this->data_to_view['create_link'] = $this->create_url;
        $this->data_to_header['title'] = "List of Files";
        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Files" => "/admin/file",
            "List" => "",
        ];

        $this->data_to_header['page_action_list'] = [
            [
                "name" => "Add File",
                "icon" => "folder-alt",
                "uri" => "file/create/add",
            ],
        ];

        $this->data_to_view['url'] = $this->url_disect();

        $this->data_to_header['css_to_load'] = array(
            "plugins/datatables/datatables.min.css",
            "plugins/datatables/plugins/bootstrap/datatables.bootstrap.css",
        );

        $this->data_to_footer['js_to_load'] = array(
            "scripts/admin/datatable.js",
            "plugins/datatables/datatables.min.js",
            "plugins/datatables/plugins/bootstrap/datatables.bootstrap.js",
            "plugins/bootstrap-confirmation/bootstrap-confirmation.js",
        );

        $this->data_to_footer['scripts_to_load'] = array(
            "scripts/admin/table-datatables-managed.js",
        );

        // load view
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/admin/file/view", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function create($action, $id=0, $linked_type=NULL) {
        
        // set return url to session should it exists
        if ($this->session->has_userdata('edition_return_url')) {
            $this->return_url = $this->session->edition_return_url . "#file_list";            
        }
      
        // additional models
        $this->load->model('filetype_model');

        // load helpers / libraries
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('upload');

        // set data
        $this->data_to_header['title'] = "File Input Page";
        $this->data_to_view['action'] = $action;
        $this->data_to_view['form_url'] = $this->create_url . "/" . $action;

        $this->data_to_header['css_to_load'] = array(
            "plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css",
            "plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css",
            "plugins/bootstrap-summernote/summernote.css",
        );

        $this->data_to_header['js_to_load'] = array(
            "plugins/moment.min.js",
            "plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js",
            "plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js",
            "plugins/bootstrap-summernote/summernote.min.js",
        );

        $this->data_to_footer['scripts_to_load'] = array(
            "scripts/admin/components-date-time-pickers.js",
            "scripts/admin/components-editors.js",
            "scripts/admin/linked_to_hide_show.js",
        );

        $this->data_to_view['filetype_dropdown'] = $this->filetype_model->get_filetype_dropdown();
        $this->data_to_view['linked_to_dropdown'] = $this->filetype_model->get_linked_to_dropdown();
        $this->data_to_view['linked_to_list'] = $this->filetype_model->get_linked_to_list();
        
        // dynamically get drop downs using the linked_to_table
        foreach ($this->data_to_view['linked_to_list'] as $linked_to_id => $linked_to_name) {
            $dropdown = $linked_to_name . "_dropdown";
            $model = $linked_to_name . "_model";
            $method = "get_" . $linked_to_name . "_dropdown";

            $this->load->model($model);
            $this->data_to_view[$dropdown] = $this->$model->$method();
        }

        if ($action == "edit") {
            $this->data_to_view['file_detail'] = $this->file_model->get_file_detail($id);
            $this->data_to_view['form_url'] = $this->create_url . "/" . $action . "/" . $id;
        } else {
            $this->data_to_view['file_detail'] = [];
            if ($id>0) {
                $this->data_to_view['file_detail']['linked_id']=$id;
                $this->data_to_view['file_detail']['file_linked_to']=$linked_type;
            }
        }

        // set validation rules
        $this->form_validation->set_rules('filetype_id', 'Filetype', 'required|numeric|greater_than[0]', ["greater_than" => "Please select a valid Filetype"]);

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['return_url'] = $this->return_url;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view($this->create_url, $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            // UPDLOAD THE FILE and stuff            
            $alert = "File has been successfully " . $action . "ed";
            $status = "success";
            $file_db_w=false;


            if ($_FILES['file_upload']['error'] == 4) {
                // no file upload attempted
                $file_upload = false;
            } else {
                $id_type = $this->input->post("file_linked_to") . "_id";
                $id = $this->input->post($id_type);

                $ul_params = [
                    "linked_to" => $this->input->post("file_linked_to"),
                    "id_type" => $id_type,
                    $id_type => $id,
                    "form_field" => "file_upload"
                ];

                $file_upload = $this->upload_file($ul_params);
//                wts($_FILES);
//                wts($_POST);
//                wts($ul_params);
//                wts($file_upload);
//                die();
                // if all went well, write info to db | 2= flyer
                if ($file_upload['success']) {
                    $params = [
                        "id_type" => $id_type,
                        "id" => $id,
                        "filetype_id" => $this->input->post("filetype_id"),
                        "file_linked_to" => $this->input->post("file_linked_to"),
                        "data" => $file_upload['data'],
                        "debug" => false,
                    ];
                    $file_db_w = $this->file_model->set_file($params);
                } else {
                    $alert = $file_upload['alert_text'];
                    $status = $file_upload['alert_status'];
                }
                
                $results_link_arr=['edition','race'];
                if (in_array($this->input->post("file_linked_to"),$results_link_arr)) {
                    $id_type = $this->input->post("file_linked_to") . "_id";
                    $linked_id = $this->input->post($id_type);
                    $set = $this->set_results_flag($this->input->post("file_linked_to"), $linked_id);
                }
            }

            if ($file_db_w) {
                $alert = "File has been " . $action . "ed";
                $status = "success";
            } elseif (empty($alert)) {
                $alert = "Error committing to the database";
                $status = "danger";
            }

            $this->session->set_flashdata([
                'alert' => $alert,
                'status' => $status,
            ]);

            // save_only takes you back to the edit page.
            if (array_key_exists("save_only", $_POST)) {
                $this->return_url = base_url("admin/file/create/edit/" . $id);
            }

            redirect($this->return_url);
        }
    }

    private function upload_file($params) {
        $file_data = $_FILES['file_upload'];
        // set default return
        $return['success'] = false;
        $return['alert_status'] = "warning";
        $return['alert_text'] = "Error uploading a file: " . $file_data['name'];

        // check folder
        $upload_path = $this->check_upload_folder($params['linked_to'], $params[$params['id_type']]);

        // set upload config
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = "pdf|docx|doc|xls|xlsx|jpg|gif|jpeg|png|ods";
        $config['max_size'] = "0";
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($params['form_field'])) {
            $error = array('error' => $this->upload->display_errors());
            $return['alert_text'] = "<b>Warning:</b> " . strip_tags($error['error']);
        } else {
            $return['success'] = true;
            $return['data'] = $this->upload->data();
            $return['filename'] = $file_data['name'];
            $return['alert_text'] = "File has been uploaded";
            $return['alert_status'] = "success";
        }
        return $return;
    }

    public function delete($file_id = 0) {
        
        // set return url to session should it exists
        if ($this->session->has_userdata('edition_return_url')) {
            $this->return_url = $this->session->edition_return_url;
        }

        if (($file_id == 0) || (!is_numeric($file_id))) {
            $this->session->set_flashdata('alert', 'Cannot delete record: ' . $file_id);
            $this->session->set_flashdata('status', 'danger');
            redirect($this->return_url);
            die();
        }

        // get file detail for nice delete message
        $file_detail = $this->file_model->get_file_detail($file_id);
        
        // delete record        
        $file_path="./uploads/".$file_detail['file_linked_to']."/".$file_detail['linked_id']."/".$file_detail['file_name'];
        $db_del = $this->file_model->remove_file($file_id,$file_path);
        
        // check results flag
        $results_link_arr=['edition','race'];
        if (in_array($file_detail['file_linked_to'],$results_link_arr)) {
            $set = $this->set_results_flag($file_detail['file_linked_to'], $file_detail['linked_id']);
        }

        if ($db_del) {
            $msg = "File has successfully been deleted: " . $file_detail['file_name'];
            $status = "success";
        } else {
            $msg = "Error in deleting the record:'.$file_id";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        redirect($this->return_url);
    }

}
