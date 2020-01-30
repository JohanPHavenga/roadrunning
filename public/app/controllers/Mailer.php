<?php

// public mailer class to get list from mailques table and send it out
class Mailer extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('emailque_model');
    }

    public function index() {
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('mailer/mailer', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function process_que() {
        $mail_que = $this->fetch_mail_que($this->ini_array['emailque']['que_size']);
        if ($mail_que) {
            foreach ($mail_que as $mail_id => $mail_data) {
                $mail_sent = $this->send_mail($mail_data);
                echo $mail_data['emailque_to_address'] . ": " . fyesNo($mail_sent) . "<br>";
                $this->update_mail_status($mail_id, $mail_sent);
            }
            die("<br>Mailqueue has processed " . sizeof($mail_que) . " mails: " . date("Y-m-d H:i:s"));
        } else {
            die("Nothing to process in mailqueue: " . date("Y-m-d H:i:s"));
        }
        wts($mail_que);
        die($msg);
    }

    private function fetch_mail_que($top = 0) {
        $emailque = $this->emailque_model->get_emailque_list($top, 5);
        return $emailque;
    }

    private function update_mail_status($id, $sent) {
        if ($sent) {
            $status_id = 6;
        } else {
            $status_id = 7;
        }
        $this->emailque_model->set_emailque_status($id, $status_id);
    }

    private function send_mail($data = "") {
        $this->load->library('email');
        
        $config['mailtype'] = 'html';
        $config['smtp_host'] = $this->ini_array['email']['smtp_server'];
        $config['smtp_port'] = $this->ini_array['email']['smtp_port'];
        $config['smtp_crypto'] = $this->ini_array['email']['smtp_crypto'];
        $config['charset'] = $this->ini_array['email']['email_charset'];
        $config['useragent'] = $this->ini_array['email']['useragent'];
        
        $this->email->initialize($config);        
        
        $this->email->from($data['emailque_from_address'], $data['emailque_from_name']);
        $this->email->to($data['emailque_to_address'], $data['emailque_to_name']);
        if ($data['emailque_cc_address']) {
            $this->email->cc($data['emailque_cc_address']);
        }
        if ($data['emailque_bcc_address']) {
            $this->email->bcc($data['emailque_bcc_address']);
        }
        $this->email->subject($data['emailque_subject']);
        $this->email->message($data['emailque_body']);
        

        $send = $this->email->send();

        return $send;
    }

    public function test() {
        // test email
        $data = [
            "to" => "johan.havenga@gmail.com",
            "body" => "test êmail",
            "subject" => "test email from coyote",
            "from" => "tech@roadrunning.co.za",
            "from_name" => "Coyote Test Mailer Bôt",
        ];
        $this->set_email($data);

        echo "mail sent";
        
        $this->process_que();
    }

}
