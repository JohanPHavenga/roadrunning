<?php

class File extends Frontend_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('download');
        $this->load->model('file_model');
        $this->load->model('edition_model');
        $this->load->model('race_model');
    }

    public function _remap($method, $params = array())
    {
        if (method_exists(__CLASS__, $method)) {
            $this->$method($params);
        } else {
            $this->handler($method, $params);
        }
    }

    public function handler($linked_to, $params = [])
    {
        if (($linked_to == "index") || (empty($params))) {
            redirect("/race-calendar");
        }
        $edition_slug = $params[0];
        $file_id = $params[1];
        $filetype_name = str_replace(" ", "_", urldecode($params[1]));
        $file_name = $params[2];

        switch ($linked_to) {
            case "edition":
                $file_id = null;
                $edition_info = $this->edition_model->get_edition_id_from_slug($edition_slug);
                $file_list = $this->file_model->get_file_list("edition", $edition_info['edition_id'], true);
                $filetype_list = $this->file_model->get_filetype_list();
                $filetype_id = $filetype_list[$filetype_name];
                if ($file_list[$filetype_id]) {
                    foreach ($file_list[$filetype_id] as $key => $file_detail) {
                        if ($file_detail['file_name'] == $file_name) {
                            $file_id = $file_detail['file_id'];
                        }
                    }
                }
                break;
            case "race":
                $race_name = $params[2];
                $file_name = $params[3];

                $file_id = null;
                $edition_info = $this->edition_model->get_edition_id_from_slug($edition_slug);
                $this->data_to_views['race_list'] = $this->race_model->get_race_list(["where" => ["races.edition_id" => $edition_info['edition_id']]]);

                foreach ($this->data_to_views['race_list'] as $race_id => $race) {
                    if ($race_name == url_title($race['race_name'])) {
                        break;
                    }
                }
                //                echo $race_id;
                $file_list = $this->file_model->get_file_list("race", $race_id, true);
                $filetype_list = $this->file_model->get_filetype_list();
                $filetype_id = $filetype_list[$filetype_name];
                foreach ($file_list[$filetype_id] as $key => $file_detail) {
                    if ($file_detail['file_name'] == $file_name) {
                        $file_id = $file_detail['file_id'];
                    }
                }
                break;
            default:
                // decrypt the file ID
                $file_id = my_decrypt($file_id);
                break;
        }
        //        wts($file_id);
        //        wts($params);
        //        wts($file_list);
        //        wts($filetype_list);
        //        echo $filetype_id;
        //        die();
        // check for INT
        if (!preg_match('/^\d+$/', $file_id)) {
            $this->show_my_404("File could not be found", "danger");
        }
        // Get details
        $file_detail = $this->file_model->get_file_detail($file_id);
        //        wts($file_detail,1);
        // If there is no details
        if (!$file_detail) {
            $this->show_my_404("No file found with that file ID", "danger");
        }
        // get ID type
        $id_type = $file_detail['file_linked_to'];
        $id = $file_detail['linked_id'];
        // add race id section here
        // set path
        $path = "./uploads/" . $id_type . "/" . $id . "/" . $file_detail['file_name'];

        if (file_exists($path)) {
            switch ($file_detail['file_ext']) {
                case ".xls":
                case ".xlsx":
                    //                    die("oops");
                    header("X-Robots-Tag: noindex, nofollow", true);
                    // We'll be outputting a XLS
                    header('Content-Type: application/xls');
                    // It will be called filename
                    header('Content-Disposition: attachment; filename="' . $file_detail['file_name'] . '"');
                    // The file source
                    readfile($path);
                    break;
                default:
                    $this->display($file_detail['file_type'], $path);
                    break;
            }
        } else {
            $this->show_my_404("File could not be found", "danger");
        }
    }

    public function download($path)
    {
        force_download($path, NULL);
    }

    public function display($content_type, $path)
    {
        //        $path = base_url($path);
        $this->output
            ->set_content_type($content_type)
            ->set_output(file_get_contents($path));
        //        die($path);
    }
}
