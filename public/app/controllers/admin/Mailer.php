<?php

class Mailer extends Admin_Controller {

    private $debug_email = false;
    private $debug_html = false;

    public function info_mail($edition_id) {
        $this->load->model('admin/edition_model');
        $this->load->model('admin/emailque_model');
        $edition_info = $this->edition_model->get_edition_detail($edition_id);        
        
        // update send flag
        $this->edition_model->update_field($edition_id, "edition_info_email_sent", true);

        if ($this->debug_email) {
            $to_email = "info@roadrunning.co.za, johan.havenga@gmail.com";
            $to_name = "Test Test";
        } else {
            $to_email = $edition_info['user_email'];
            $to_name = $edition_info['user_name'] . ' ' . $edition_info['user_surname'];
        }
        $emailque_data = array(
            'emailque_subject' => $edition_info['edition_name'] . " on RoadRunning.co.za",
            'emailque_to_address' => $to_email,
            'emailque_to_name' => $to_name,
            'emailque_body' => $this->set_email_body($this->info_mail_body($edition_info)),
            'emailque_status' => 5,
            'emailque_from_address' => $this->ini_array['email']['from_address'],
            'emailque_from_name' => $this->ini_array['email']['from_name'],
        );

        if ($this->debug_html) {
            wts($emailque_data['emailque_body']);
            wts($emailque_data);
            wts($edition_info, 1);
        }

        $emailque_id = $this->emailque_model->set_emailque("add", 0, $emailque_data);
        
        if ($emailque_id) {
            $msg = "Email added to que for: " . $edition_info['edition_name'];
            $status = "success";
        } else {
            $msg = "Error trying to set email que";
            $status = "danger";
        }

        $this->session->set_flashdata('alert', $msg);
        $this->session->set_flashdata('status', $status);
        
        redirect(base_url("admin/dashboard/view"));
    }

    public function info_mail_body($edition_info) {
        $body = "<p>Hello</p>";
        $body .= "<p>We run a listing website aiming to list all road running events in the country called "
                . "<a href='https://www.roadrunning.co.za'>RoadRunning.co.za</a>. Some basic information regarding your event, the <b>" 
                . substr($edition_info['edition_name'], 0, -5) . "</b> has already been loaded on the site. Please see the preliminary listing here:";

        $body .= "<p><a href='http://www.roadrunning.co.za/event/" . $edition_info['edition_slug'] . "'>"
                . "www.roadrunning.co.za/event/" . $edition_info['edition_slug'] . "</a></p>";

        $body .= "<p>Do you have any additional information available to add to the listing?<br>"
                . "<b>Here are some examples of the information I am looking for:</b></p>";
        $body .= "<ul><li>Entry fees</li><li>Race start times</li><li>How to enter? (on-the-day,  online etc.)</li><li>Will medals be handed out?</li><li>Route Map</li></ul>";

        $body .= "<p>Any other information, especially the event flyer (if ready), will be greatly appreciated.";
        $body .= "<p>Hope to hear from you soon.</p>";
        $body .= "<p>Kind Regards<br>Johan from RoadRunning.co.za</p>";

        return $body;
    }

}
