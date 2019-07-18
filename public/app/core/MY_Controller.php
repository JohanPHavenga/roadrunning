<?php

class MY_Controller extends CI_Controller {

    public $data_to_views = [];
    public $header_url = "/templates/header";
    public $footer_url = "/templates/footer";

    function __construct() {
        parent::__construct();
        // load global models
        $this->load->model('province_model');
        // check login
        $this->data_to_views['user_logged_in'] = false;
        if ($this->session->has_userdata('user_logged_in')) {
            $this->data_to_views['user_logged_in'] = true;
        }

        $this->data_to_views['province_dropdown'] = $this->province_model->get_province_dropdown_data();
    }

    function CallAPI($method, $url, $data = false) {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

}
