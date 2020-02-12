<?php

// THIS IS A MANUAL PROCESS OF THE MAIL QUE
// THE ONE RUNNING EVERY 5 MIN IS IN CRON/INTRA_DAY

class Mailer extends Frontend_Controller {

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
//        wts($mail_que);
//        die($msg);
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

}
