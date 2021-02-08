<?php

// THIS IS A MANUAL PROCESS OF THE MAIL QUE
// THE ONE RUNNING EVERY 5 MIN IS IN CRON/INTRA_DAY

class Mailer extends Frontend_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('emailque_model');
    }

    public function index()
    {
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view('mailer/mailer', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    // there is another function in the core controller to kick off mailque purge from an internal function
    public function process_que()
    {
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

    private function fetch_mail_que($top = 0)
    {
        $emailque = $this->emailque_model->get_emailque_list($top, 5);
        return $emailque;
    }

    private function update_mail_status($id, $sent)
    {
        if ($sent) {
            $status_id = 6;
        } else {
            $status_id = 7;
        }
        $this->emailque_model->set_emailque_status($id, $status_id);
    }

    public function auto($emailtemplate_id = 0, $edition_id = 0)
    {
        $this->auto_mailer($emailtemplate_id, $edition_id);
    }

    public function test()
    {
        $user_data['user_email'] = "johan.havenga@gmail.com";
        $url="http://www.roadrunning.co.za/eqw";
        $data = [
            "to" => $user_data['user_email'],
            "subject" => "Password Reset for RoadRunning.co.za",
            "body" => "<h2>Password Reset</h2>"
                . "<p>We have received a password reset request on "
                . "<a href = 'https://www.roadrunning.co.za/' style = 'color:#222222 !important;text-decoration:underline !important;'>RoadRunning.co.za</a> for the email address "
                . "<b>" . $user_data['user_email'] . "</b>."
                . "<p>Please click on the link below to confirm this was you, and set a new password:</p>"
                . "<p style='padding-left: 15px; border-left: 4px solid #ccc;'><b>Click to confirm:</b><br><a href='$url' style = 'color:#222222 !important;text-decoration:underline !important;'>$url</a></p>"
                . "<p>If this was not you, you can safely ignore this email.</p>",
            "from" => "noreply@roadrunning.co.za",
            "from_name" => "noreply@roadrunning.co.za",
        ];

        $this->set_email($data);        
        $this->poke_mail_que();
        echo "Email send to: ".$user_data['user_email'];
    }
}
