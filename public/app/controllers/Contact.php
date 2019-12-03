<?php

class Contact extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function test() {
        $data = [
            "to" => "johan.havenga@gmail.com",
            "subject" => "Contact Form TEST",
            "body" => "<p>Hello,</p>"
            . "<p>Please see below for an enquiry send from the "
            . "<b><a href = 'https://www.roadrunning.co.za/' style = 'color:#222222 !important;text-decoration:underline !important;'>RoadRunning.co.za</a></b> website "
            . "by a runner enquiring about the <b>Growthpoint Sundowner 10km 2019</b> race:"
            . "<p><b>Name:</b> Johan<br>"
            . "<b>Surname:</b> Havenga<br>"
            . "<b>Email:</b> johan.havenga@gmail.com<br>"
            . "<b>Query:</b> This is a comment</p>"
            . "<p>View the race listing <a href = 'https://www.roadrunning.co.za/' style = 'color:#222222 !important;text-decoration:underline !important;'>here</a>."
            . "<p>Please reply to this email to answer the runner's query.",
            "from" => "johnahavenga@woolworths.co.za",
            "from_name" => "Johan H",
        ];
        echo $this->set_email($data);
    }

    public function index() {
        $this->data_to_views['page_title'] = "Contact Us";
        $this->data_to_views['form_url'] = '/contact';
        $this->data_to_views['error_url'] = '/contact';

        // validation rules
        $this->form_validation->set_rules('user_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('user_surname', 'Surname', 'trim|required');
        $this->form_validation->set_rules('user_email', 'email address', 'trim|required|valid_email');

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view('contact/contact', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            // set user_data from post
            foreach ($this->input->post() as $field => $value) {
                $email_data[$field] = $value;
            }
            $mail_id = $this->send_contact_email($email_data);
            $this->data_to_views['mail_id'] = $mail_id;
            $this->data_to_views['email'] = $this->input->post('user_email');

            $this->session->set_flashdata([
                'alert' => "Email has been send",
                'status' => "success",
            ]);

            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view('contact/contact', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        }
    }

    // SEND CONFIRMATION EMAIL
    private function send_contact_email($email_data) {
        // test email
        $data = [
            "to" => $email_data['user_email'],
            "body" => "<h2>Contact Email</h2><p>"
            . "Name: " . $email_data['user_name'] . "<br>"
            . "Surname: " . $email_data['user_surname'] . "<br>"
            . "Email: " . $email_data['user_email'] . "<br>"
            . "Comment: " . $email_data['user_comment'] . "<br></p>",
            "subject" => "Contact Form",
        ];

        return $this->set_email($data);
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

        // load correct view
        if ($this->form_validation->run() === FALSE) {
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view('contact/event', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            // set user_data from post
            foreach ($this->input->post() as $field => $value) {
                $email_data[$field] = $value;
            }
            $email_data['edition_name'] = $this->data_to_views['edition_data']['edition_name'];
            $this->send_event_email($email_data, $this->data_to_views['edition_data']['user_email']);

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
    private function send_event_email($email_data, $to) {
        // test email
        $data = [
            "to" => $to,
            "body" => "<h2>Contact Email</h2><p>"
            . "Name: " . $email_data['user_name'] . "<br>"
            . "Surname: " . $email_data['user_surname'] . "<br>"
            . "Email: " . $email_data['user_email'] . "<br>"
            . "Race Name: " . $email_data['edition_name'] . "<br>"
            . "Message: " . $email_data['user_message'] . "<br></p>",
            "subject" => "Contact from RoadRunning.co.za regarding " . $email_data['edition_name'],
        ];
        // send mail to organiser
//        $this->set_email($data);
        // send mail to user
        $data['to'] = $email_data['user_email'];
        $this->set_email($data);

        return true;
    }

    // GENERIC CONFIRMATION PAGE
    function confirm() {
        if ($this->session->flashdata('confirm_msg') !== null) {
            $this->load->view($this->header_url, $this->data_to_views);
            $this->load->view('contact/confirm', $this->data_to_views);
            $this->load->view($this->footer_url, $this->data_to_views);
        } else {
            redirect(base_url());
        }
    }

}
