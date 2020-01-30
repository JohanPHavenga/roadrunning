<?php

class Contact extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function test() {
        $data = [
            "to" => "johan.havenga@gmail.com",
            "subject" => "Contact Form TESTé",
            "body" => "<p>Hello,</p>"
            . "<p>Please see below for an enquiry send from the "
            . "<b><a href = 'https://www.roadrunning.co.za/' style = 'color:#222222 !important;text-decoration:underline !important;'>RoadRunning.co.za</a></b> website "
            . "by a runner enquiring about the <b>Growthpoint Sundowner 10km 2019</b> race:"
            . "<p><b>Name:</b> Johan<br>"
            . "<b>Surname:</b> Havenga<br>"
            . "<b>Email:</b> johan.havenga@gmail.com<br>"
            . "<b>Query:</b> This is a comment ôéêº</p>"
            . "<p>View the race listing <a href = 'https://www.roadrunning.co.za/' style = 'color:#222222 !important;text-decoration:underline !important;'>here</a>."
            . "<p>Please reply to this email to answer the runner's query.",
            "from" => "johnahavenga@woolworths.co.za",
            "from_name" => "Johan Hé",
        ];
        echo $this->set_email($data);
    }

    public function index() {
        $this->data_to_views['page_title'] = "Contact Us";
        $this->data_to_views['form_url'] = '/contact';
        $this->data_to_views['error_url'] = '/contact';
        
        $this->data_to_views['scripts_to_load']=["https://www.google.com/recaptcha/api.js"];
        
        // validation rules
        $this->form_validation->set_rules('user_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('user_surname', 'Surname', 'trim|required');
        $this->form_validation->set_rules('user_email', 'email address', 'trim|required|valid_email');
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view($this->notice_url, $this->data_to_views);
            $this->load->view('contact/contact', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            // set user_data from post
            foreach ($this->input->post() as $field => $value) {
                $email_data[$field] = $value;
            }
            $mail_id = $this->send_contact_email($email_data);
            
            $this->session->set_flashdata([
                'alert' => "Your contact email has been send",
                'status' => "success",
                'icon' => "check-circle",
                'confirm_msg' => 'Thank you for contacting me. I will get back to you as soon as I can!',
                'confirm_btn_txt' => 'Return',
                'confirm_btn_url' => base_url(),
            ]);

            redirect(base_url("contact/confirm"));
            
            
//            $this->data_to_views['mail_id'] = $mail_id;
//            $this->data_to_views['email'] = $this->input->post('user_email');
//
//            $this->session->set_flashdata([
//                'alert' => "Email has been send",
//                'status' => "success",
//            ]);
//
//            $this->load->view($this->header_url, $this->data_to_views);
//            $this->load->view('contact/contact_confirm', $this->data_to_views);
//            $this->load->view($this->footer_url, $this->data_to_views);
        }
    }

    // SEND CONFIRMATION EMAIL
    private function send_contact_email($email_data) {
        // send to info@roadrunning
        $data = [
            "to" => $this->ini_array['email']['from_address_server'],
            "body" => "<h3>Contact form</h3><p>"
            . "<b>Name:</b> " . $email_data['user_name'] . " " . $email_data['user_surname'] . "<br>"
            . "<b>Email:</b> " . $email_data['user_email'] . "</p>"
            . "<p style='padding-left: 15px; border-left: 4px solid #ccc;'><b>Comment:</b><br>" . nl2br($email_data['user_message']) . "</p>",
            "subject" => "Enquiry from RoadRunning.co.za #". uniqid(),
            "from" => $email_data['user_email'],
            "from_name" => $email_data['user_name']." ".$email_data['user_surname'],
        ];        
        $this->set_email($data);
        
        // send mail to user
        $data['to'] = $email_data['user_email'];
        $this->set_email($data);

        return true;
    }

    public function event($slug = "") {
        $this->load->model('edition_model');
        $edition_sum = $this->edition_model->get_edition_id_from_slug($slug);

        if (empty($edition_sum)) {
            redirect("/contact");
        } else {
            $this->data_to_views['contact_url'] = base_url("contact/event/" . $slug);
            $this->data_to_views['cancel_url'] = base_url("event/" . $slug);
        }
        $this->data_to_views['scripts_to_load']=["https://www.google.com/recaptcha/api.js"];

        $this->data_to_views['edition_data'] = $this->edition_model->get_edition_detail($edition_sum['edition_id']);

        $this->data_to_views['crumbs_arr'][$slug] = $this->data_to_views['cancel_url'];

//        wts($edition_sum);
//        wts($edition_data, true);

        $this->data_to_views['page_title'] = "Contact Event Organisers";
        $this->data_to_views['form_url'] = $this->data_to_views['error_url'] = '/contact/event/' . $slug;

        // validation rules
        $this->form_validation->set_rules('user_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('user_surname', 'Surname', 'trim|required');
        $this->form_validation->set_rules('user_email', 'email address', 'trim|required|valid_email');
        $this->form_validation->set_rules('user_message', 'Message', 'trim|required');
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view($this->notice_url, $this->data_to_views);
            $this->load->view('contact/event', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            // set user_data from post
            foreach ($this->input->post() as $field => $value) {
                $email_data[$field] = $value;
            }
            $this->send_event_email($email_data, $this->data_to_views['edition_data']);

            $this->session->set_flashdata([
                'alert' => "Your email has been send to the organisers",
                'status' => "success",
                'icon' => "check-circle",
                'confirm_msg' => 'Thank you for using the race contact functionality. A contact email has been send to the organisers of this race.',
                'confirm_btn_txt' => 'Return for Race',
                'confirm_btn_url' => $this->data_to_views['cancel_url'],
            ]);

            redirect(base_url("contact/confirm"));
        }
    }

    // SEND EVENT EMAIL
    private function send_event_email($email_data, $edition_data) {
        $data = [
            "to" => $edition_data['user_email'].",".$this->ini_array['email']['from_address_server'],
//            "cc" => $this->ini_array['email']['from_address_server'],
            "subject" => $edition_data['annual_name']. " - Enquiry from RoadRunning.co.za #". uniqid(),
            "body" => "<p>Hello,</p>"
            . "<p>Please see below for an enquiry send from the "
            . "<a href = 'https://www.roadrunning.co.za/' style = 'color:#222222 !important;text-decoration:underline !important;'>RoadRunning.co.za</a> website "
            . "by a runner enquiring about the <b>".$edition_data['edition_name']."</b> race:"
            . "<p><b>Name:</b> " . $email_data['user_name'] . " " . $email_data['user_surname'] . "<br>"
            . "<b>Email:</b> " . $email_data['user_email'] . "</p>"
            . "<p style='padding-left: 15px; border-left: 4px solid #ccc;'><b>Query:</b><br> " . nl2br($email_data['user_message']) . "</p>"
            . "<p>View the race listing <a href = 'https://www.roadrunning.co.za/event/".$edition_data['edition_slug']."' style = 'color:#222222 !important;text-decoration:underline !important;'>here</a>."
            . "<br>Please reply to this email to answer the runner's query.</p>",
            "from" => $email_data['user_email'],
            "from_name" => $email_data['user_name']." ".$email_data['user_surname'],
        ];
        // send mail to organiser
        $this->set_email($data);
        return true;
    }

    // GENERIC CONFIRMATION PAGE
    function confirm() {
        if ($this->session->flashdata('confirm_msg') !== null) {
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view($this->notice_url, $this->data_to_views);
            $this->load->view('contact/confirm', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            redirect(base_url());
        }
    }

}
