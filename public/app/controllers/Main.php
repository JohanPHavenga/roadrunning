<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('edition_model');
        $this->load->model('race_model');
        $this->load->model('history_model');
        $this->load->model('file_model');
        $this->load->model('date_model');
        $this->load->model('entrytype_model');

        // featured events
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection, "edition_status" => [1]],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"), "edition_isfeatured " => 1],
            "limit" => "5",
        ];
        $this->data_to_views['featured_events'] = $this->race_model->add_race_info($this->date_model->add_dates($this->edition_model->get_edition_list($query_params)));
//        wts($this->data_to_views['featured_events'],1);
        // upcoming events
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection, "edition_status" => [1]],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"), "edition_date <= " => date("Y-m-d H:i:s", strtotime("9 days")), "edition_status" => 1],
            "order_by" => ["editions.edition_date" => "ASC"],
        ];
        $upcoming_events = $this->race_model->add_race_info($this->edition_model->get_edition_list($query_params));
        $this->data_to_views['upcoming_events'] = $this->chronologise_data($upcoming_events, "edition_date");
//        wts($this->data_to_views['upcoming_events'],true);
        // last edited
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection, "edition_status" => [1, 3, 9]],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"), "editions.updated_date > " => date("Y-m-d H:i:s", strtotime("-1 year"))],
            "order_by" => ["editions.updated_date" => "DESC"],
            "limit" => 10,
        ];
        $this->data_to_views['last_edited_events'] = $this->edition_model->get_edition_list($query_params);

        // history summary
        $query_params = [
            "where_in" => ["region_id" => $this->session->region_selection,],
            "order_by" => ["historysum_countmonth" => "DESC"],
            "limit" => "10",
        ];
        $this->data_to_views['history_sum_month'] = $this->history_model->get_history_summary($query_params);


        // QUOTES for banner
        $this->data_to_views['quote_arr'] = $this->get_quote_data(3);

//        $this->data_to_views['featured_events'] = $this->chronologise_data($this->data_to_views['featured_events'], "edition_date");

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('templates/banner_home', $this->data_to_views);
        $this->load->view('main/home', $this->data_to_views);
        $this->load->view($this->footer_url);
    }

    public function custom_404() {

        $this->output->set_status_header('404');
        if ($this->session->flashdata('alert') !== null) {
            $this->data_to_views['page_title'] = $this->session->flashdata('alert');
        } else {
            $this->data_to_views['page_title'] = "Page not found - 404 Error";
            $this->data_to_views['meta_description'] = "The page you are looking for could not be found";
        }
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('main/404', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function about() {
        $this->data_to_views['banner_img'] = "run_02";
        $this->data_to_views['banner_pos'] = "40%";
        $this->data_to_views['page_title'] = "About Me";
        $this->data_to_views['meta_description'] = "A little bit more about the creator of the site";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('main/about', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function faq($open = null) {
        $this->data_to_views['banner_img'] = "run_03";
        $this->data_to_views['banner_pos'] = "20%";
        $this->data_to_views['page_title'] = "Frequently Asked Questions";
        $this->data_to_views['meta_description'] = "A few frequently asked questions answered";
        $this->data_to_views['open'] = $open;
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('main/faq', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function newsletter() {
        $this->data_to_views['page_title'] = "Newsletter Subscription";
        $this->data_to_views['meta_description'] = "Subscribe to your monthly newsletter";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('main/newsletter', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function support() {
        $this->data_to_views['banner_img'] = "run_01";
        $this->data_to_views['banner_pos'] = "40%";
        $this->data_to_views['page_title'] = "Donations";
        $this->data_to_views['meta_description'] = "Show your appriciation by donating to the site via SnapScan";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('main/support', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function terms_conditions() {
        $this->data_to_views['banner_img'] = "run_04";
        $this->data_to_views['banner_pos'] = "20%";
        $this->data_to_views['companyName'] = "RoadRunningZA";
        $this->data_to_views['page_title'] = "Terms & Conditions";
        $this->data_to_views['meta_description'] = "Site use Terms & Conditions";
        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('main/terms_conditions', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    public function training_programs($race_name = null) {
        $this->data_to_views['banner_img'] = "run_05";
        $this->data_to_views['banner_pos'] = "40%";

        switch (strtolower(urldecode($race_name))) {
            case "marathon":
                $t_prog_text = "Marathon Training Program";
                $t_prog_link = "https://coachparry.com/marathon-training-roadmap/?ref=9";
                break;
            case "half-marathon":
            case "half marathon":
                $t_prog_text = "Half-Marathon Training Program";
                $t_prog_link = "https://coachparry.com/half-marathon-training-roadmap/?ref=9";
                break;
            case "10km":
            case "10km road":
            case "10km-road":
            case "10km-run":
            case "10km run":
                $t_prog_text = "10K Training Program";
                $t_prog_link = "https://coachparry.com/10k-training-roadmap/?ref=9";
                break;
            default:
                $t_prog_text = "Training Program";
                $t_prog_link = "https://coachparry.com/join-coach-parry/?ref=9";
                break;
        }

        $this->data_to_views['page_title'] = $t_prog_text;
        $this->data_to_views['meta_description'] = "Link through to " . $t_prog_text . "s from Coach Parry";
        $this->data_to_views['coach_parry_link'] = $t_prog_link;

        $this->load->view($this->header_url, $this->data_to_views);
        $this->load->view($this->banner_url, $this->data_to_views);
        $this->load->view($this->notice_url, $this->data_to_views);
        $this->load->view('main/training_programs', $this->data_to_views);
        $this->load->view($this->footer_url, $this->data_to_views);
    }

    private function get_quote_data($count) {
        $this->load->model('quote_model');
        $this->load->helper('file');
        $img_base_url = "assets/img/slider/";

        // get random quotes
        $quote_arr = $this->quote_model->get_quote_list(true, $count);
        // get random bg image
        $img_arr = get_filenames($img_base_url);
        $img_count = sizeof($img_arr);

        // set return_arr
        $rand_img_num_arr = [];
        foreach ($quote_arr as $quote_id => $quote) {
            do {
                $rand_img_num = rand(1, $img_count);
            } while (in_array($rand_img_num, $rand_img_num_arr));
            $rand_img_num_arr[] = $rand_img_num;

            $num = sprintf("%02d", $rand_img_num);
            $return_arr[$quote_id]['quote'] = $quote['quote_quote'];
            $return_arr[$quote_id]['img_url'] = base_url($img_base_url . "run_" . $num . ".webp");
        }

        return ($return_arr);
    }

    function m_pdf() {

        $mpdf = new mPDF();

// Write some HTML code:

        $mpdf->WriteHTML('Hello World');

// Output a PDF file directly to the browser
        $mpdf->Output();
    }

}
