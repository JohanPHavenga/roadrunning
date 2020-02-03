<?php

class Subscription extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
        $this->data_to_header['section'] = "subscription";
    }

    public function unsubscribe($crypt) {
        // get data
        $str= my_decrypt($crypt);
        $data=explode("|", $str);
        $user_id=$data[0];
        $linked_to=$data[1];
        $linked_id=$data[2];        
        // load moadels        
        $this->load->model('usersubscription_model');
        // set negative return msg
        $this->data_to_view['alert_heading']="Failed";
        $this->data_to_view['alert_msg']="Subscription not found. Please contact the site administrator.";
        // check if the subscription exists
        if ($this->usersubscription_model->exists($user_id,$linked_to,$linked_id)) {
            $remove=$this->usersubscription_model->remove_usersubscription($user_id,$linked_to,$linked_id);
            if ($remove) {
                $this->data_to_view['alert_heading']="Success";
                $this->data_to_view['alert_msg']="You have been successfully unsubscribed.";
            }
        }     
        $this->data_to_header['title'] = "Unsubscribe";
       
        // set title bar
        $this->data_to_header["title_bar"] = $this->render_topbar_html([
            "crumbs" => ["Unsubscribe" => "", "Home" => "/"],
        ]);

        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view('subscription/unsubscribe', $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
        
    }

    public function recaptcha($str = "") {
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = '6LcxdoYUAAAAAFphXeYMlOL2w5ysa9ovdOdCLJyP';
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $google_url . "?secret=" . $secret . "&response=" . $str . "&remoteip=" . $ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        //reCaptcha success check
        if ($res['success']) {
            return TRUE;
        } else {
            $this->form_validation->set_message('recaptcha', 'The <b>reCAPTCHA</b> field is telling me that you are a robot. Shall we give it another try?');
            return FALSE;
        }
    }

}
