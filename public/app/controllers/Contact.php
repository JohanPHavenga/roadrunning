<?php

class Contact extends MY_Controller {

    public function __construct() {
        parent::__construct();
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

}
