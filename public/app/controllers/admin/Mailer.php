<?php
class Mailer extends Admin_Controller {
    
    private $debug=false;
    
    public function info_mail($edition_id) {
        $this->load->model('admin/edition_model');
        $edition_info=$this->edition_model->get_edition_detail($edition_id);
        // update send flag
        $params_arr=[
            "edition_info_email_sent"=>1,
            "user_id"=>$edition_info['user_id'],
            "edition_status"=>$edition_info['edition_status']
            ];
        $update_email_send_flag=$this->edition_model->set_edition("edit",$edition_id,$params_arr,false);        
        // send email to myself for debug
        if ($this->debug) { $to_email="info@roadrunning.co.za"; } else { $to_email=$edition_info['user_email']; }       
        $data=[
            "to_email"=>$to_email,
            "to_name"=>$edition_info['user_name'].' '.$edition_info['user_surname'],
            "subject"=>$edition_info['edition_name']." on RoadRunning.co.za",
            "body"=>$this->info_mail_body($edition_info),
            "redirect_url"=>"/admin",
        ];
        
//        wts($data);        
//        wts($edition_info);
//        exit();
        // send the email
        $this->send_mail($data);        
    }
    
    public function info_mail_body($edition_info) {
        $body="<p>Hi there</p>";
        $body.="<p>Hope you are well. I represent a listing website aiming to list all road running events in the Western, Northern and Eastern Cape called RoadRunning.co.za.<br>"
                . "We loaded some basic information for your event, the <b>".substr($edition_info['edition_name'],0,-5)."</b> already. See the preliminary listing here:";

        $body.="<p><a href='http://www.roadrunning.co.za/event/".encode_edition_name($edition_info['edition_name'])."'>"
                . "www.roadrunning.co.za/event/".encode_edition_name($edition_info['edition_name'])."</a></p>";

        $body.="<p>Do you have any additional information we can add to the listing? Even if it is still awaiting approval.<br>"
                . "<b>The following information is what we are looking for:</b></p>";
        $body.="<ul><li>Entry fees</li><li>Race start times</li><li>How to enter (on-the-day or online)</li><li>Route Map</li></ul>";

        $body.="<p>Any other information, especially the event flyer (if ready), will be greatly appreciated.";
        $body.="<p>Hope to hear from you soon.</p>";
        $body.="<p>Kind Regards<br>Johan from RoadRunning.co.za</p>";
        
        return $body;
    }

    private function send_mail($data)
    {
        $this->load->library('email');
        $config['mailtype'] = 'html';
        $config['smtp_host'] = 'dandelion.aserv.co.za';
        $config['smtp_port'] = '465';
        $this->email->initialize($config);

        $this->email->from("info@roadrunning.co.za", "Johan from RoadRunning.co.za");
        $this->email->to($data['to_email'], $data['to_name']);
        $this->email->bcc('info@roadrunning.co.za');

        $this->email->subject($data['subject']);
        $this->email->message($data['body']);
        
        $this->email->send();
        
        $this->session->set_flashdata([
                'alert'=>"Email has been send",
                'status'=>"success",
                ]);

        redirect($data['redirect_url']);
    }


}
