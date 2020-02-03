<?php

class Contact extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('edition_model');
        $this->data_to_header['section'] = "contact";
    }

    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->view($method, $params);
        }
    }

    public function view() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->data_to_header['title'] = "Contact Us";

        // set validation rules
        $this->form_validation->set_rules('dname', 'Name', 'required', 'Please enter your name');
        $this->form_validation->set_rules('demail', 'Email', 'required|valid_email');
//        $this->form_validation->set_rules('dphone', 'Phone', 'alpha_numeric_spaces');
        $this->form_validation->set_rules('dmsg', 'Comment', 'required');
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');

        $this->form_validation->set_message('required', 'Please complete the <b>{field}</b> field');
//        wts($this->input->post());

        // set title bar
        $this->data_to_header["title_bar"] = $this->render_topbar_html(
                [
                    "crumbs" => ["Contact Us" => "/contact", "Home" => "/"],
        ]);
        
        $this->data_to_footer['scripts_to_load']=array(
                "https://www.google.com/recaptcha/api.js"
            );
        
        // get edition dropdown
        $this->data_to_view['edition_dropdown'] = $this->edition_model->get_edition_dropdown(true);

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->data_to_view['form_data'] = $_POST;
            $this->data_to_view['email_send'] = false;
            if (empty($this->data_to_view['form_data']['dto'])) {
                $this->data_to_view['form_data']['dto'] = "info@roadrunning.co.za";
            }
            

            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view('contact/view', $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            $this->load->library('email');

            $config['mailtype'] = 'html';
            $config['smtp_host'] = 'dandelion.aserv.co.za';
            $config['smtp_port'] = '465';
            $config['smtp_crypto'] = "ssl";
            $config['charset'] = 'iso-8859-1';
            $this->email->initialize($config);

            $this->email->set_newline("\r\n");
            $this->email->from("info@roadrunning.co.za", $this->input->post('dname'));
            $this->email->reply_to($this->input->post('demail'), $this->input->post('dname'));
            if ($this->input->post('dto')) {
                $this->email->to($this->input->post('dto'));
                if ($this->input->post('dto') != 'info@roadrunning.co.za') {
                    $this->email->cc('info@roadrunning.co.za');
                }
            } else {
                $this->email->to('info@roadrunning.co.za');
            }
            $this->email->bcc($this->input->post('demail'));

            $this->email->subject($this->input->post('devent') . ' - RoadRunning.co.za enquiry #'.mktime());
            $msg_arr[] = "<p>This is an enquiry send from the RoadRunning.co.za website by an user:<br>";
            $msg_arr[] = "<b>Name:</b> " . $this->input->post('dname');
            $msg_arr[] = "<b>Email:</b> " . $this->input->post('demail');
            $msg_arr[] = "<b>Event:</b> " . $this->input->post('devent');
            $msg_arr[] = "<b>Comment:</b> " . $this->input->post('dmsg');
            $msg_arr[] = "</p>";
            $msg = implode("<br>", $msg_arr);

            $this->email->message($msg);

//            wts($this->email);
//            wts($this->input->post());
//            die();
            $this->email->send();

            $this->data_to_view['email_send'] = true;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view('contact/view', $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        }
    }

    public function recaptcha($str="") {
        $google_url="https://www.google.com/recaptcha/api/siteverify";
        $secret='6LcxdoYUAAAAAFphXeYMlOL2w5ysa9ovdOdCLJyP';
        $ip=$_SERVER['REMOTE_ADDR'];
        $url=$google_url."?secret=".$secret."&response=".$str."&remoteip=".$ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res= json_decode($res, true);
        //reCaptcha success check
        if($res['success'])
        {
          return TRUE;
        }
        else
        {
          $this->form_validation->set_message('recaptcha', 'The <b>reCAPTCHA</b> field is telling me that you are a robot. Shall we give it another try?');
          return FALSE;
        }
    }

}
