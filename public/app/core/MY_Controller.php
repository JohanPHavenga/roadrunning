<?php

//------------------------------------------------------------------------------
// CENTRAL MY CONTROLLER w. Admin and Frontend sections
//------------------------------------------------------------------------------
class MY_Controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        // make ini file content available 
        $this->ini_array = parse_ini_file("server_config.ini", true);

        // keep last 5 URLs
        if (!isset($_SESSION['last_5_urls'])) {
            $_SESSION['last_5_urls'] = [current_url()];
        } else {
            if ($_SESSION['last_5_urls'][0] != current_url()) {
                $skip_controllers = ["login", "logout", "register", "forgot-password", "404", "assets", "file", "favicon.ico"];
                $url_bits = explode("/", uri_string());
                if (!in_array($url_bits[0], $skip_controllers)) {
                    array_unshift($_SESSION['last_5_urls'], current_url());
                    if (count($_SESSION['last_5_urls']) > 5) {
                        array_pop($_SESSION['last_5_urls']);
                    }
                }
            }
        }
    }

    public function set_email_body($body, $post_text = null)
    {
        $year = date("Y");
        $html = <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv = "Content-Type" content = "text/html; charset=utf-8" />
<meta name = "viewport" content = "width=device-width, initial-scale=1.0"/>
<style>a, a[x-apple-data-detectors] { color:inherit!important;
font-family:inherit!important;
font-size:inherit!important;
font-weight:inherit!important;
line-height:inherit!important;
text-decoration:none!important;
}</style>
</head>
<body style = "background-color:#FFFFFF;margin:0;padding:0;">
<table cellpadding = "0" cellspacing = "0" border = "0" width = "100%" bgcolor = "#FFFFFF" style = "margin:0;"><tbody><tr><td style = "padding:0;" align = "center">
<table cellpadding = "0" cellspacing = "0" border = "0" align = "center" bgcolor = "#FFFFFF" style = "margin:0; max-width: 600px; width: 100%;"><tbody><tr><td style = "padding:0 20px;" align = "left">
<div style = "margin:0 0 25px 0;"><img alt = "RoadRunningZA" width = "72" height = "72" src = "https://www.roadrunning.co.za/img/favicon/android-icon-72x72.png" /></div>

<div style = "color:#000000;font-family:Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:14px;line-height:22px;text-align:left;">
$body
</div>

<div style = "border-top: 2px solid #E5E5E5;color:#111111;font-family:Open Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:11px;line-height:14px;margin:30px 0;">
<div style = "margin:20px 0;"><a href='https://www.roadrunning.co.za/' title='Go to RoadRunningZA'><img alt = "RoadRunningZA" width = "110" height = "22" src = "https://www.roadrunning.co.za/img/logo-vec-22.png" /></a></div>
<div style = "margin:20px 0;" > Copyright &copy;
$year RoadRunningZA. All rights reserved.<br>
Note that RoadRunning.co.za does not have any affiliation with the race organisers. <u><a href='https://www.roadrunning.co.za/disclaimer' title='Go to RoadRunningZA'>Full disclaimer</a></u>
</div>
$post_text
<p><a href='https://pos.snapscan.io/qr/LAzMFdGZ'><img src='https://www.roadrunning.co.za/assets/img/SnapCode_LAzMFdGZ.png' width='100' style='margin-bottom: 10px; width: 100px'></a><br>Please consider supporting my website via SnapScan to help keep the info flowing</p>
</div>
</td></tr></tbody></table>
</td></tr></tbody></table>
</body>
</html>
EOT;
        return $html;
    }

    private function has_auto_mail_been_send($emailtemplate_id, $edition_id)
    {
        $this->load->model('autoemail_model');
        if ($this->autoemail_model->exists($emailtemplate_id, $edition_id)) {
            return true;
        } else {
            return false;
        }
    }

    public function auto_mailer($emailtemplate_id = 0, $edition_id = 0)
    {
        $this->load->model('autoemail_model');
        $this->load->model('edition_model');
        $this->load->model('admin/emailtemplate_model');
        $this->load->model('admin/emailmerge_model');
        $this->load->model('admin/usersubscription_model');
        // get edition detail
        $edition_detail = $this->edition_model->get_edition_detail($edition_id);
        // get email temaplte list
        $emailtemplate_list = $this->emailtemplate_model->get_emailtemplate_list();

        // security check
        if ((!array_key_exists($emailtemplate_id, $emailtemplate_list)) || (!$edition_detail)) {
            $this->session->set_flashdata([
                'alert' => " Mmmmm, somthing is not right.",
                'status' => "danger",
            ]);
            redirect(base_url("404"), 'refresh', 404);
            die();
        }
        // check of mails mag gestuur word
        if ($edition_detail['edition_no_auto_mail']) {
            return false;
        }
        // doen check of mail alreeds gestuur is
        if ($this->has_auto_mail_been_send($emailtemplate_id, $edition_id)) {
            return false;
        }
        // main code
        $user_arr = $this->usersubscription_model->get_usersubscription_list("edition", $edition_id);
        if (!$user_arr) {
            return false;
        } else {
            foreach ($user_arr as $user) {
                $user_list[] = $user['user_id'];
            }
        }
        $user_str = implode(",", $user_list);
        $emailtemplate = $this->emailtemplate_model->get_emailtemplate_detail($emailtemplate_id);

        // create email merge
        $merge_data = array(
            "emailmerge_status" => 4,
            "emailmerge_subject" => $emailtemplate['emailtemplate_name'] . " " . $edition_detail['edition_name'],
            "emailmerge_body" => $emailtemplate['emailtemplate_body'],
            "emailmerge_recipients" => $user_str,
            "emailmerge_linked_to" => "edition",
            "linked_id" => $edition_id,
        );
        $emailmerge_id = $this->emailmerge_model->set_emailmerge("add", 0, $merge_data);
        $autoemail_id = $this->autoemail_model->set_autoemail($emailtemplate_id, $edition_id);

        return true;
    }

    public function chronologise_data($data_arr, $date_field)
    {
        $return_data = [];
        if ($data_arr) {
            foreach ($data_arr as $id => $row) {
                $year = date("Y", strtotime($row[$date_field]));
                $month = date("F", strtotime($row[$date_field]));
                $day = date("d", strtotime($row[$date_field]));

                $return_data[$year][$month][$day][$id] = $row;
            }
        }
        return $return_data;
    }

    // get unsubscribe URL
    public function formulate_unsubscribe_url($user_id, $linked_to, $linked_id)
    {
        $crypt = my_encrypt($user_id . "|" . $linked_to . "|" . $linked_id);
        $url = base_url("user/unsubscribe/" . $crypt);
        return $url;
    }


    public function send_mail($data)
    {
        $this->load->library('email');

        $config['mailtype'] = 'html';
        $config['smtp_host'] = $this->ini_array['email']['smtp_server'];
        $config['smtp_port'] = $this->ini_array['email']['smtp_port'];
        $config['smtp_crypto'] = $this->ini_array['email']['smtp_crypto'];
        $config['charset'] = $this->ini_array['email']['email_charset'];
        $config['useragent'] = $this->ini_array['email']['useragent'];
        $config['smtp_user'] = $this->ini_array['email']['smtp_user'];
        $config['smtp_pass'] = $this->ini_array['email']['smtp_pass'];

        $this->email->initialize($config);

        $this->email->from($data['emailque_from_address'], $data['emailque_from_name']);
        $this->email->to($data['emailque_to_address'], $data['emailque_to_name']);
        if ($data['emailque_cc_address']) {
            $this->email->cc($data['emailque_cc_address']);
        }
        if ($data['emailque_bcc_address']) {
            $bcc_arr[$data['emailque_bcc_address']] = $data['emailque_bcc_address'];
        }
        $bcc_arr[$this->ini_array['email']['bcc_address']] = $this->ini_array['email']['bcc_address'];
        $this->email->bcc($bcc_arr);
        $this->email->subject($data['emailque_subject']);
        $this->email->message($data['emailque_body']);

        //        wts($data);
        //        wts($this->email,1);

        $send = $this->email->send();

        return $send;
    }

    // ====================================================
    // MAILER STUFF TO CALL FROM INTERNAL FUNCTIONS - returns true/false
    // ====================================================

    public function poke_mail_que()
    {
        while ($this->have_mail_in_que()) {
            $this->process_poke_que();
        }
        return true;
    }
    public function have_mail_in_que()
    {
        $this->load->model('emailque_model');
        return $this->emailque_model->get_emailque_list(1, 5);
    }
    public function process_poke_que()
    {
        $this->load->model('emailque_model');
        $mail_que = [];
        $mail_que = $this->emailque_model->get_emailque_list($this->ini_array['emailque']['que_size'], 5);

        if ($mail_que) {
            foreach ($mail_que as $mail_id => $mail_data) {
                $mail_sent = $this->send_mail($mail_data);
                if ($mail_sent) {
                    $status_id = 6;
                } else {
                    $status_id = 7;
                }
                $this->emailque_model->set_emailque_status($mail_id, $status_id);
            }
        }
        return true;
    }

    // =========================================================

    public function int_phone($phone)
    {
        $p_replace = str_replace("-", "", str_replace(" ", "", str_replace("  ", "", trim($phone))));
        return preg_replace('/^(?:\+?27|0)?/', '+27', $p_replace);
    }

    // API CALL
    public function url_get_contents($Url)
    {
        if (!function_exists('curl_init')) {
            die('CURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    // ==============================================================================================
    // SESSION CHECKS
    // ==============================================================================================  
    public function check_if_user_is_logged_in($type = "web")
    {
        // check of user ingelog is. set view variable to user
        if (isset($_SESSION['user']['logged_in'])) {
            if ($type == "admin") {
                // check for Admin is role list
                if (in_array(1, $_SESSION['user']['role_list'])) {
                    return $_SESSION['user'];
                } else {
                    return false;
                }
            } else {
                return $_SESSION['user'];
            }
        } else {
            return $user['user']['logged_in'] = false;
        }
    }

    public function check_value_refresh()
    {
        // check if data was last retrieved a day ago or more, then unsets data to be retrieved again
        if ((!$this->session->has_userdata('session_value_refresh')) || ($this->session->session_value_refresh < strtotime($this->ini_array['session']['static_values_expiry']))) {
            $this->session->set_userdata("session_value_refresh", time());
            return true;
        } else {
            return false;
        }
    }
}

//------------------------------------------------------------------------------
//  FRONT END CONTROLLER
//------------------------------------------------------------------------------
class Frontend_Controller extends MY_Controller
{

    public $data_to_views = [];
    public $header_url = "/templates/header";
    public $banner_url = "/templates/banner";
    public $notice_url = "/templates/notice";
    public $footer_url = "/templates/footer";
    public $logged_in_user = [];
    public $crumb_arr = [];

    function __construct()
    {
        parent::__construct();
        // doen checks en set session vars
        $this->data_to_views['logged_in_user'] = $this->logged_in_user = $this->check_if_user_is_logged_in();
        $this->data_to_views['history'] = $this->check_history();
        $this->data_to_views['crumbs_arr'] = $this->set_crumbs();
        $this->data_to_views['where'] = "my";
        if ($this->logged_in_user) {
            $this->data_to_views['user_menu'] = $this->get_user_menu();
        }

        // check of weer moet laai
        if ($this->check_value_refresh()) {
            $this->session->set_userdata("web_data", $this->get_web_data());
            $this->session->set_userdata("calendar_date_list", $this->get_date_list());
            $this->session->set_userdata("static_pages", $this->get_static_pages());
            $this->session->set_userdata("province_pages", $this->get_province_pages());
            $this->session->set_userdata("region_pages", $this->get_region_pages());
            $this->session->set_userdata("most_viewed_pages", $this->get_most_viewed_pages());
            $this->session->set_userdata("most_searched", $this->get_most_searched());
        }

        // set cookies
        $this->data_to_views['rr_cookie']['sub_email'] = get_cookie("sub_email");
        $this->data_to_views['rr_cookie']['feedback'] = get_cookie("feedback");

        // new history for transition 
        $new_count = 0;
        foreach ($this->data_to_views['history'] as $page) {
            if (strpos($page, "/new/") === false) {
                //do nothing
            } else {
                $new_count++;
            }
        }
        $this->data_to_views['new_page_count'] = $new_count;
    }

    public function show_my_404($msg, $status)
    {
        //Using 'location' not work well on some windows systems
        $this->session->set_flashdata([
            'alert' => $msg,
            'status' => $status,
        ]);
        redirect('404');
    }

    // ==============================================================================================
    // HISTORY
    // ============================================================================================== 
    private function check_history()
    {

        // check current session history
        if (!isset($_SESSION['history'])) {
            $_SESSION['history'] = [];
        } else {
            $this->history_purge();
        }
        // check if cookie exists
        if (is_null(get_cookie('session_token'))) {
            set_cookie("session_token", session_id(), 604800);
            $session_token = session_id();
        } else {
            $session_token = get_cookie('session_token');
        }

        // check if the url not already in session
        if (!in_array(current_url(), $_SESSION['history'])) {
            // set session variable
            $_SESSION['history'][time()] = current_url();

            // chcek if uri not in exclusion list
            if (!$this->segment_exclusion_list(uri_string())) {
                // check if url has already been counted today for this session. If not add to DB
                $this->load->model('history_model');
                // $this->load->model('edition_model');
                $history_exists = $this->history_model->check_history($session_token, current_url());
                if (!$history_exists) {
                    $history_data = [
                        "history_session_id" => $session_token,
                        "history_url" => current_url(),
                    ];
                    // if edition page, add the edition ID to the history table to make the summary easier
                    $url_sections = explode("/", current_url());
                    if ($url_sections[3] == "event") {;
                        $history_data['history_baseurl'] = base_url() . "event/" . $url_sections[4];
                    }

                    if (isset($_SESSION['user']['user_id'])) {
                        $history_data['user_id'] = $_SESSION['user']['user_id'];
                    }
                    // wts($history_data);
                    // set DB
                    $this->history_model->set_history($history_data);
                }
            }
        }
        return $_SESSION['history'];
    }

    private function segment_exclusion_list($uri_string)
    {
        $seg = explode("/", $uri_string);
        if ((in_array($uri_string, $this->ini_array['history']['exclusion'])) || (in_array($seg[0] . "/*", $this->ini_array['history']['exclusion']))) {
            return true;
        } else {
            return false;
        }
    }

    private function history_purge()
    {
        foreach ($_SESSION['history'] as $timestamp => $url) {
            if ($timestamp < strtotime($this->ini_array['history']['purge_period'])) {
                unset($_SESSION['history'][$timestamp]);
            }
        }
    }

    private function get_most_viewed_pages()
    {
        $this->load->model('history_model');
        $query_params = [
            "order_by" => ["historysum_countweek" => "DESC"],
            "limit" => 5,
        ];
        return $this->history_model->get_history_summary($query_params);
    }

    private function get_most_searched()
    {
        $this->load->model('history_model');
        $query_params = [
            "where" => ["updated_date >" => date('Y-m-d', strtotime("1 month ago"))],
            "order_by" => ["search_count" => "DESC"],
            "limit" => 5,
        ];
        return $this->history_model->get_most_searched($query_params);
    }

    // ==============================================================================================
    // CENTRAL MAIL FUNCTIONS
    // ==============================================================================================
    public function set_email($data, $post_text = null)
    {
        // THIS FUNCTION ONLY TAKES EMAIL FIELDS AND ADD THEM TO THE EMAIL QUE TABLE
        // load emailque_model
        $this->load->model('emailque_model');
        $required_fields = ['to', 'subject', 'body'];
        if (array_keys_exists($required_fields, $data)) {
            if (isset($data['from'])) {
                $from = $data['from'];
            } else {
                $from = $this->ini_array['email']['from_address'];
            }
            if (isset($data['from_name'])) {
                $from_name = $data['from_name'];
            } else {
                $from_name = $this->ini_array['email']['from_name'];
            }

            $emailque_data = array(
                'emailque_subject' => $data['subject'],
                'emailque_to_address' => $data['to'],
                'emailque_body' => $this->set_email_body($data['body'], $post_text),
                'emailque_status' => 5,
                'emailque_from_address' => $from,
                'emailque_from_name' => $from_name,
                'emailque_bcc_address' => $this->ini_array['email']['bcc_address'],
            );
            if (isset($data['to_name'])) {
                $emailque_data['emailque_to_name'] = $data['to_name'];
            }
            if (isset($data['cc'])) {
                $emailque_data['emailque_cc_address'] = $data['cc'];
            }
            $params = [
                "action" => "add",
                "data" => $emailque_data,
                "id" => false,
            ];
            //            echo $emailque_data['emailque_body'];
            //            die();
            return $this->emailque_model->set_emailque($params);
        } else {
            die("Missing required fields to send email: MY_Controller->send_mail");
        }
    }



    public function recaptcha($str = "")
    {
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = '6LcxdoYUAAAAAFphXeYMlOL2w5ysa9ovdOdCLJyP';
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $google_url . "?secret=" . $secret . "&response=" . $str . "&remoteip=" . $ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        //reCaptcha success check
        if ($res['success']) {
            return TRUE;
        } else {
            $this->form_validation->set_message('recaptcha', 'The <b>reCAPTCHA</b> field is telling me that you are a robot. Shall we give it another try?');
            return FALSE;
        }
    }

    // ==============================================================================================
    // SITEMAP / MENU INFO
    // ==============================================================================================

    public function get_static_pages()
    {
        $static_pages = [
            "home" => [
                "display" => "Home",
                "loc" => base_url(),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 day")),
                "priority" => 1,
                "changefreq" => "daily",
            ],
            "races" => [
                "display" => "Races",
                "loc" => base_url("calendar"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                "priority" => 1,
                "changefreq" => "daily",
                "sub-menu" => [
                    "virtual" => [
                        "display" => "Virtual",
                        "loc" => base_url("race/virtual"), //calendar
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 1,
                        "changefreq" => "daily",
                        "badge" => "POPULAR",
                    ],
                    "upcoming" => [
                        "display" => "Upcoming",
                        "loc" => base_url("race/upcoming"), //calendar
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 1,
                        "changefreq" => "daily",
                        //                        "badge" => "POPULAR",
                    ],
                    "featured" => [
                        "display" => "Featured",
                        "loc" => base_url("race/featured"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 1,
                        "changefreq" => "daily",
                    ],
                    "top10" => [
                        "display" => "Top 10 most viewed",
                        "loc" => base_url("race/most-viewed"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 1,
                        "changefreq" => "daily",
                    ],
                    "history" => [
                        "display" => "History",
                        "loc" => base_url("race/history"), //calendar/past
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 0.8,
                        "changefreq" => "daily",
                    ],
                    "province" => [
                        "display" => "Per Province",
                        "loc" => base_url("province/list"), //calendar/past
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-2 day")),
                        "priority" => 0.8,
                        "changefreq" => "daily",
                    ],
                    "parkrun" => [
                        "display" => "parkrun",
                        "loc" => base_url("race/parkrun"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                        "priority" => 0.3,
                        "changefreq" => "yearly",
                    ],
                ],
            ],
            "results" => [
                "display" => "Results",
                "loc" => base_url("results"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                "priority" => 0.8,
                "changefreq" => "weekly",
                "sub-menu" => [
                    "results" => [
                        "display" => "Race Results",
                        "loc" => base_url("race/results"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                    "my-results" => [
                        "display" => "My Results",
                        "loc" => "",
                        "loc" => base_url("my-results"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                        "badge" => "BETA",
                    ],
                ],
            ],
            "resources" => [
                "display" => "Resources",
                "loc" => base_url("faq"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                "priority" => 0.8,
                "changefreq" => "weekly",
                "sub-menu" => [
                    "faq" => [
                        "display" => "FAQ",
                        "loc" => base_url("faq"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                        "priority" => 0.5,
                        "changefreq" => "monthly",
                    ],
                    "training" => [
                        "display" => "Training Programs",
                        "loc" => base_url("training-programs"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                        "priority" => 0.5,
                        "changefreq" => "monthly",
                    ],
                ],
            ],
            "about" => [
                "display" => "About",
                "loc" => base_url("about"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                "priority" => 0.5,
                "changefreq" => "monthly",
            ],
            "contact" => [
                "display" => "Contact",
                "loc" => base_url("contact"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.8,
                "changefreq" => "yearly",
                "sub-menu" => [
                    "contact-us" => [
                        "display" => "Contact Us",
                        "loc" => base_url("contact"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                        "priority" => 0.8,
                        "changefreq" => "yearly",
                    ],
                    "support" => [
                        "display" => "Support the site",
                        "loc" => base_url("support"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                        "priority" => 0.8,
                        "changefreq" => "yearly",
                    ],
                    "newsletter" => [
                        "display" => "Newsletter",
                        "loc" => base_url("newsletter"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                        "priority" => 0.6,
                        "changefreq" => "yearly",
                        "badge" => "POPULAR",
                    ],
                ],
            ],
            "switch-region" => [
                "display" => "Switch Region",
                "loc" => base_url("region/switch"), //version
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.5,
                "changefreq" => "yearly",
            ],
            "featured-regions" => [
                "display" => "Show all regions",
                "loc" => base_url("region"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 month")),
                "priority" => 0.8,
                "changefreq" => "monthly",
                "sub-menu" => [
                    "cape-town" => [
                        "display" => "Cape Town",
                        "loc" => base_url("region/capetown"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 1,
                        "changefreq" => "weekly",
                        "badge" => "POPULAR",
                    ],
                    "gauteng" => [
                        "display" => "Gauteng",
                        "loc" => base_url("region/gauteng"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                    "kzn-coast" => [
                        "display" => "KZN Coast",
                        "loc" => base_url("region/kzn-coast"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                    "garden-route" => [
                        "display" => "Garden Route",
                        "loc" => base_url("region/garden-route"),
                        "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-5 day")),
                        "priority" => 0.8,
                        "changefreq" => "weekly",
                    ],
                ],
            ],
            "login" => [
                "display" => "Login",
                "loc" => base_url("login"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 1,
                "changefreq" => "yearly",
            ],
            "add-listing" => [
                "display" => "Add Race Listing",
                "loc" => base_url("event/add"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.6,
                "changefreq" => "yearly",
            ],
            "search" => [
                "display" => "Search",
                "loc" => base_url("search"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 week")),
                "priority" => 0.8,
                "changefreq" => "weekly",
            ],
            "sitemap" => [
                "display" => "Sitemap",
                "loc" => base_url("sitemap"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 week")),
                "priority" => 0.5,
                "changefreq" => "daily",
            ],
            "terms" => [
                "display" => "Terms & Conditions",
                "loc" => base_url("terms-conditions"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.2,
                "changefreq" => "yearly",
            ],
            "disclaimer" => [
                "display" => "Disclaimer",
                "loc" => base_url("disclaimer"),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 year")),
                "priority" => 0.2,
                "changefreq" => "yearly",
            ],
        ];

        $this->session->set_userdata("static_pages", $static_pages);
        return $static_pages;
    }

    public function get_province_pages()
    {
        $this->load->model('event_model');
        // get province list from event model to only return those provinces that is in use
        $province_list = $this->event_model->get_province_list();

        foreach ($province_list as $province_id => $province) {
            $p_arr[$province_id] = [
                "display" => $province['province_name'],
                "loc" => base_url("province/" . $province['province_slug']),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 week")),
                "priority" => 0.9,
                "changefreq" => "daily",
            ];
        }
        return $p_arr;
    }

    public function get_region_pages()
    {
        $this->load->model('event_model');
        $region_list = $this->event_model->get_region_list();

        foreach ($region_list as $region_id => $region) {
            $r_arr[$region_id] = [
                "display" => $region['region_name'],
                "loc" => base_url("region/" . $region['region_slug']),
                "lastmod" => date('Y-m-d\TH:i:s' . '+02:00', strtotime("-1 week")),
                "priority" => 0.9,
                "changefreq" => "daily",
            ];
        }
        return $r_arr;
    }

    public function get_web_data()
    {
        $this->load->model('webdata_model');
        return $this->webdata_model->get_webdata();
    }

    public function get_date_list()
    {
        $dates_to_fetch = [
            "1 month ago",
            "today",
            "+1 month",
            "+2 month",
            "+3 month",
            "+4 month",
            //            "+5 month",
        ];
        foreach ($dates_to_fetch as $strtotime) {
            $date_list[date("Y", strtotime($strtotime))][date("m", strtotime($strtotime))] = date("F Y", strtotime($strtotime));
        }
        return $date_list;
    }

    // ==============================================================================================
    // EVENT PAGE MENU
    // ==============================================================================================
    public function get_event_menu($slug, $event_id, $edition_id, $in_past)
    {

        $menu_arr = [
            "summary" => [
                "display" => "Summary",
                "loc" => base_url("event/" . $slug),
            ],
            "results" => [
                "display" => "Results",
                "loc" => base_url("event/" . $slug . "/results"),
            ],
            "entries" => [
                "display" => "How to enter",
                "loc" => base_url("event/" . $slug . "/entries"),
            ],
            "race_day" => [
                "display" => "Race day info",
                "loc" => base_url("event/" . $slug . "/race-day-information"),
            ],
            "route_maps" => [
                "display" => "Route Maps",
                "loc" => base_url("event/" . $slug . "/route-maps"),
            ],
            "contact" => [
                "display" => "Race Contact",
                "loc" => base_url("event/" . $slug . "/contact"),
            ],
            "accom" => [
                "display" => "Accommodation",
                "loc" => base_url("event/" . $slug . "/accommodation"),
            ],
            "more" => [
                "display" => "More",
                "sub_menu" => [
                    "results" => [
                        "display" => "Results",
                        "loc" => base_url("event/" . $slug . "/results"),
                    ],
                    "distances" => [
                        "display" => "Distances",
                        "loc" => base_url("event/" . $slug . "/distances"),
                    ],
                    "entries" => [
                        "display" => "How to enter",
                        "loc" => base_url("event/" . $slug . "/entries"),
                    ],
                    "subscribe" => [
                        "display" => "Get Notifications",
                        "loc" => base_url("event/" . $slug . "/subscribe"),
                    ],
                    "sponsors" => [
                        "display" => "Sponsors",
                        "loc" => base_url("event/" . $slug . "/sponsors"),
                    ],
                    "club" => [
                        "display" => "More events by this Club",
                        "loc" => base_url("event/" . $slug . "/club-other-races"),
                    ],
                    "previous" => [
                        "display" => "Previous year's edition",
                        "loc" => base_url("event/" . $slug . "/"),
                    ],
                    "next" => [
                        "display" => "Next year's edition",
                        "loc" => base_url("event/" . $slug . "/"),
                    ],
                    "print" => [
                        "display" => "Print",
                        "loc" => base_url("event/" . $slug . "/print"),
                    ],
                ],
            ],
        ];

        // get event history to know if links should be in menu
        $event_history = $this->get_event_history($event_id, $edition_id);
        // previous
        if (isset($event_history['past'])) {
            $menu_arr['more']['sub_menu']['previous']['loc'] = base_url("event/" . $event_history['past']['edition_slug']);
        } else {
            unset($menu_arr['more']['sub_menu']['previous']);
        }
        //next
        if (isset($event_history['future'])) {
            $menu_arr['more']['sub_menu']['next']['loc'] = base_url("event/" . $event_history['future']['edition_slug']);
        } else {
            unset($menu_arr['more']['sub_menu']['next']);
        }

        // check if in past else hide to hide accommodation link
        if ($in_past) {
            unset($menu_arr['accom']);
            unset($menu_arr['entries']);
            unset($menu_arr['more']['sub_menu']['results']);
        } else {
            unset($menu_arr['results']);
            unset($menu_arr['more']['sub_menu']['entries']);
        }

        // check for route maps
        //        if ((!isset($this->data_to_views['url_list'][8])) && (!isset($this->data_to_views['file_list'][7]))) {
        //            unset($menu_arr['route_maps']);
        //        }
        // to use later
        unset($menu_arr['more']['sub_menu']['sponsors']);
        unset($menu_arr['more']['sub_menu']['club']);
        unset($menu_arr['more']['sub_menu']['print']);
        return $menu_arr;
    }

    public function get_event_history($event_id, $edition_id)
    {
        $this->load->model('edition_model');
        $return = [];
        // get list of editions linked to this event
        $query_params = [
            "where" => ["event_id" => $event_id],
        ];
        $edition_list = $this->edition_model->get_edition_list($query_params);

        // remove the one you are looking at
        $current_year = date("Y", strtotime($edition_list[$edition_id]['edition_date']));
        unset($edition_list[$edition_id]);

        if ($edition_list) {
            foreach ($edition_list as $edition) {
                $edition['edition_year'] = fdateYear($edition['edition_date']);
                if ($edition['edition_year'] < $current_year) {
                    if (isset($return['past'])) {
                        if ($edition['edition_year'] > $return['past']['edition_year']) {
                            $return['past'] = $edition;
                        }
                    } else {
                        $return['past'] = $edition;
                    }
                } elseif ($edition['edition_year'] > $current_year) {
                    if (isset($return['future'])) {
                        if ($edition['edition_year'] < $return['future']['edition_year']) {
                            $return['future'] = $edition;
                        }
                    } else {
                        $return['future'] = $edition;
                    }
                }
            }
        }
        return $return;
    }

    // ==============================================================================================
    // USER PAGE MENU
    // ==============================================================================================
    public function get_user_menu()
    {

        $menu_arr = [
            "dashboard" => [
                "display" => "Dashboard",
                "loc" => base_url("user"),
                "icon" => "icon-clipboard21",
            ],
            "profile" => [
                "display" => "My Profile",
                "loc" => base_url("user/profile"),
                "icon" => "icon-user11",
            ],
            "results" => [
                "display" => "My Results",
                "loc" => base_url("user/my-results"),
                "icon" => "icon-clock21",
            ],
            "subscriptions" => [
                "display" => "My Subscriptions",
                "loc" => base_url("user/my-subscriptions"),
                "icon" => "icon-mail",
            ],
            "regions" => [
                "display" => "My Regions",
                "loc" => base_url("region/switch"),
                "icon" => "icon-settings1",
            ],
            "donate" => [
                "display" => "Donate",
                "loc" => base_url("user/donate"),
                "icon" => "icon-clipboard21",
            ],
            "logout" => [
                "display" => "Logout",
                "loc" => base_url("logout"),
                "icon" => "icon-log-out",
            ],
        ];
        return $menu_arr;
    }

    // ==============================================================================================
    // CENTRAL FUNCTIONS
    // ==============================================================================================

    public function formulate_status_notice($edition_data)
    {
        $return = [];;
        //        echo $event_detail['edition_status'];
        //        die();
        switch ($edition_data['edition_status']) {
            case 2:
                $msg = "<b>This event is set to DRAFT mode.</b> All detail has not yet been confirmed";
                $short_msg = "DRAFT";
                $state = "danger";
                $icon = "minus-circle";
                break;
            case 3:
                if (isset($edition_data['user_email'])) {
                    $email = $edition_data['user_email'];
                } else {
                    $email = '';
                }
                $msg = "<strong>This event has been CANCELLED.</strong> Please contact the event organisers for more detail on: <a href='mailto:$email' title='Email organisers'>$email</a>";
                $short_msg = "CANCELLED";
                $state = "danger";
                $icon = "times-circle";
                break;
            case 9:
                if (isset($edition_data['user_email'])) {
                    $email = $edition_data['user_email'];
                } else {
                    $email = '';
                }
                $msg = "<strong>This event has been POSTPONED until further notice.</strong> Please contact the event organisers for more detail on: <a href='mailto:$email' title='Email organisers'>$email</a><br>"
                    . "Please consider <b><a href='#subscribe'>subscribing</a></b> to the event below to receive an email once a new date is set";
                $short_msg = "POSTPONED";
                $state = "warning";
                $icon = "minus-circle";
                break;
            default:
                switch ($edition_data['edition_info_status']) {
                    case 13:
                        $msg = "<strong>PLEASE NOTE</strong> - Dates and race times has <u>not yet been confirmed</u> by the race organisers";
                        $short_msg = "Dates not confirmed yet";
                        $state = "danger";
                        $icon = "info-circle";
                        break;
                    case 14:
                        $msg = "<b>INFO UNCONFIRMED</b> - Waiting for race information from the organisers";
                        $short_msg = "Dates confirmed. Awaiting more info from organisers";
                        $state = "warning";
                        $icon = "info-circle";
                        break;
                    case 15:
                        $msg = "<b>INFO INCOMPLETE</b> - All information loaded has been confirmed as correct, but listing is not complete";
                        $short_msg = "Listing almost complete";
                        $state = "info";
                        $icon = "info-circle";
                        break;
                    case 16:
                        $msg = "<b>LISTING VERIFIED</b> - All information below has been confirmed";
                        $short_msg = "Listing complete";
                        $state = "success";
                        $icon = "check-circle";
                        break;
                    case 10:
                        $msg = "<b>RESULTS PENDING</b> - Waiting for results to be released by organisers. Note this can take up to a week";
                        $short_msg = "Waiting for results";
                        $state = "info";
                        $icon = "info-circle";
                        break;
                    case 11:
                        $slug = $edition_data['edition_slug'];
                        $msg = "<b>RESULTS LOADED</b> - Click to <a href='" . base_url("event/$slug/results") . "'>view results</a>";
                        $short_msg = "Results loaded";
                        $state = "success";
                        $icon = "check-circle";
                        break;
                    case 12:
                        $msg = "<b>NO RESULTS EXPECTED</b> - No official results will be released for this event";
                        $short_msg = "No results expexted";
                        $state = "warning";
                        $icon = "minus-circle";
                        break;
                }
                break;
        }

        $return['msg'] = $msg;
        $return['short_msg'] = $short_msg;
        $return['state'] = $state;
        $return['icon'] = $icon;
        return $return;
    }

    public function set_crumbs()
    {
        // setup auto crumbs from URI
        $segs = $this->uri->segment_array();
        $crumb_uri = substr(base_url(), 0, -1);
        $total_segments = $this->uri->total_segments();
        $crumbs['Home'] = base_url();
        for ($x = 1; $x <= $total_segments; $x++) {

            if (($x == $total_segments) || ($x == 3)) {
                $crumb_uri = "";
            } else {
                $crumb_uri .= "/" . $segs[$x];
            }

            // make controller prural for event and overwrite URI
            if (($x == 1) && ($segs[$x] == "event")) {
                $segs[$x] = "race";
            }
            // make controller prural for display purposes
            if (in_array($segs[$x], ["race"])) {
                $segs[$x] = $segs[$x] . "s";
            }

            $segs[$x] = str_replace("_", " ", $segs[$x]);
            $segs[$x] = str_replace("-", " ", $segs[$x]);
            $crumbs[ucwords($segs[$x])] = $crumb_uri;

            if ($x == 3) {
                break;
            }
        }

        return $crumbs;
    }

    public function subscribe_user($user_data, $linked_to, $linked_id)
    {
        // this function will add a user to a subscription        
        $this->load->model('user_model');
        $this->load->model('role_model');
        $this->load->model('usersubscription_model');

        // get user id
        $user_id = $this->user_model->get_user_id($user_data['user_email']);
        // new user
        if (!$user_id) {
            $params = [
                "action" => "add",
                "user_data" => $user_data,
                "role_arr" => [2],
            ];
            $user_id = $this->user_model->set_user($params);
            //            $user_id = $this->user_model->set_user("add", 0, $user_data, true);
        } else {
            // check if role 2 exist
            $role_list = $this->role_model->get_role_list_per_user($user_id);
            if (!in_array(2, $role_list)) {
                $this->role_model->set_user_role($user_id, 2);
            }
        }

        // check if subscription exists
        $sub_exists = $this->usersubscription_model->exists($user_id, $linked_to, $linked_id);
        if ($sub_exists) {
            $alert = "We found a subsciption already existed for the email address entered. If you believe this to be an error please <a href='mailto:info@roadrunning.co.za'>contact me</a>.";
            $status = "warning";
            $icon = "info-circle";
        } else {
            $usersubscription_data = array(
                'user_id' => $user_id,
                'linked_to' => $linked_to,
                'linked_id' => $linked_id,
            );

            $add = $this->usersubscription_model->set_usersubscription("add", $usersubscription_data);
            if ($add) {
                $email = $this->set_subscribe_confirmation_email($usersubscription_data);
                switch ($linked_to) {
                    case "edition":
                    case "event":
                        $alert = "Thank you. You have been added to the mailing list for this race";
                        break;
                    case "newsletter":
                        $alert = "Thank you. You have successfully been subscribed to the newsletter";
                        break;
                    default:
                        $alert = "Thank you. You have successfully been subscribed";
                        break;
                }
                $status = "success";
                $icon = "check-circle";
            } else {
                $alert = "Failed to add subsciprtion. Please contact the site administrator";
                $status = "danger";
                $icon = "minus-circle";
            }
        }
        // set session flash data
        $this->session->set_flashdata([
            'alert' => $alert,
            'status' => $status,
            'icon' => $icon,
        ]);
    }

    private function set_subscribe_confirmation_email($usersub_data)
    {
        $this->load->model('user_model');
        $this->load->model('emailque_model');
        $this->load->model('edition_model');
        // get user data
        $user_data = $this->user_model->get_user_detail($usersub_data['user_id']);
        // get edition_data
        if (($usersub_data['linked_to'] == "edition") || ($usersub_data['linked_to'] == "event")) {
            $this->load->model('edition_model');
            $edition_data = $this->edition_model->get_edition_sum($usersub_data['linked_id']);
        }
        // set body
        switch ($usersub_data['linked_to']) {
            case "newsletter":
                $switch = " our <strong>monthly newletter</strong>.";
                $subject = "Newsletter subscription successful";
                break;
            case "edition":
            case "event":
                $switch = " updates regarding the <strong>" . $edition_data['edition_name'] . "</strong> event.";
                $subject = "Added to " . $edition_data['edition_name'] . " mailing list";
                break;
        }
        $body_arr[] = "Hi " . $user_data['user_name'] . ",<br>";
        $body_arr[] = "This is a courtesy email to confirm you have been subscribed to receive " . $switch . "<br>";
        $body_arr[] = "If <u>this was not you</u> subscribing yourself to this awesome service, please reply to this email to be removed.<br>";
        $body_arr[] = "Kind Regards";
        $body_arr[] = "Johan from RoadRunning.co.za";
        $body = implode("<br>", $body_arr);

        $data = [
            "to" => $user_data['user_email'],
            "subject" => $subject,
            "body" => $body,
            "from" => $this->ini_array['email']['from_address_server'],
            "from_name" => $this->ini_array['email']['from_name_server'],
        ];


        $unsubscribe_url = $this->formulate_unsubscribe_url($usersub_data['user_id'], $usersub_data['linked_to'], $usersub_data['linked_id']);
        $post_text = "<p>This email was sent to " . $user_data['user_email'] . "<br><a href='$unsubscribe_url' style='text-decoration:underline'>Unsubscribe</a> from this list</p>";
        $mail_id = $this->set_email($data, $post_text);
        return $mail_id;
    }
}

//------------------------------------------------------------------------------
//  ADMIN CONTROLLER
//------------------------------------------------------------------------------
class Admin_Controller extends MY_Controller
{

    public $data_to_header = [];
    public $data_to_view = [];
    public $data_to_footer = [];
    public $view_url = "/admin/list";
    public $header_url = "/templates/admin/header";
    public $footer_url = "/templates/admin/footer";
    public $profile_url = "/admin/dashboard/profile";
    public $logout_url = "/login/logout";
    public $upload_path = "./uploads/admin/";

    function __construct()
    {
        parent::__construct();

        // Check login, load back end dependencies
        if (!$this->logged_in_user = $this->check_if_user_is_logged_in("admin")) {
            $this->session->set_flashdata([
                'alert' => "You are not logged in as an Admin. Please log in to continue.",
                'status' => "danger",
            ]);
            redirect('/login', 'refresh');
            exit();
        }

        // setup auto crumbs from URI
        $segs = $this->uri->segment_array();
        $crumb_uri = substr(base_url(), 0, -1);
        $total_segments = $this->uri->total_segments();
        for ($x = 1; $x <= $total_segments; $x++) {

            if (($x == $total_segments) || ($x == 3)) {
                $crumb_uri = "";
            } else {
                $crumb_uri .= "/" . $segs[$x];
            }

            if ($segs[$x] == "admin") {
                $segs[$x] = "home";
            }
            if ($segs[$x] == "dashboard") {
                continue;
            }
            if ($segs[$x] == "delete") {
                $this->data_to_header['crumbs'] = [];
                break;
            }

            $segs[$x] = str_replace("_", " ", $segs[$x]);
            $this->data_to_header['crumbs'][ucwords($segs[$x])] = $crumb_uri;

            if ($x == 3) {
                break;
            }
        }

        $this->data_to_header['menu_array'] = $this->set_admin_menu_array();
    }

    function url_disect()
    {
        $url_info = [];
        $url_info["base_url"] = base_url();
        $url_info["url_string"] = uri_string();
        $url_info["url_string_arr"] = explode("/", uri_string());

        return $url_info;
    }

    function csv_handler($file_path)
    {
        $csv = array_map('str_getcsv', file($file_path));
        array_walk($csv, function (&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);
        return $csv;
    }

    function csv_flat_table_import($file_data)
    {
        foreach ($file_data as $entity) {
            //reset($entity);

            $id = array_shift($entity);
            foreach ($entity as $key => $value) {
                if (!empty($value)) {
                    $user_data[$key] = $value;
                }
            }
            // get ID - set action
            if ($id > 0) {
                $action = "edit";
            } else {
                $action = "add";
                $id = 0;
                if (isset($sum_data[$action])) {
                    $id = max(array_keys($sum_data[$action])) + 1;
                }
            }

            $sum_data[$action][$id] = $user_data;
            unset($user_data);
        }

        return $sum_data;
    }

    //CHECK AND CREATE UPLOAD FOLDER
    public function check_upload_folder($linked_to, $id)
    {
        $upload_path = "./uploads/" . $linked_to . "/" . $id;
        if (!file_exists($upload_path)) {
            if (!mkdir($upload_path, 0777, true)) {
                return false;
            }
        }
        return $upload_path;
    }

    public function set_results_flag($linked_to, $id)
    {
        $this->load->model('admin/url_model');
        $this->load->model('admin/file_model');
        $this->load->model('admin/race_model');

        // chcek if there is a results URL
        $has_results_url = $this->url_model->check_urltype_exists($linked_to, $id, 4);
        $has_results_file = $this->file_model->check_filetype_exists($linked_to, $id, 4);

        //this method is in MY_MODEL
        if ($has_results_url || $has_results_file) {
            $flag = true;
        } else {
            $flag = false;
        }
        // get edition info if the results is on race level
        if ($linked_to == "race") {
            $id = $this->race_model->get_edition_id($id);
            $linked_to = "edition";
        }
        // set the flag
        $set = $this->url_model->set_results_flag($linked_to, $id, $flag);
        if ($flag) {
            $this->auto_mailer(2, $id);
        }
    }

    function set_admin_menu_array()
    {
        return [
            // Dashboard
            [
                "text" => "Dashboard",
                "url" => 'admin',
                "icon" => "home",
                "seg0" => ['dashboard'],
                "submenu" => [
                    [
                        "text" => "Dashboard",
                        "url" => 'admin/dashboard',
                        "icon" => "bar-chart",
                    ],
                    [
                        "text" => "Yearly Edition Audit",
                        "url" => 'admin/dashboard/audit/' . date("Y"),
                        "icon" => "bulb",
                    ],
                    [
                        "text" => "Result Audit",
                        "url" => 'admin/dashboard/result_audit',
                        "icon" => "trophy",
                    ],
                    [
                        "text" => "Bulk Copy",
                        "url" => 'admin/edition/bulk_copy',
                        "icon" => "share-alt",
                    ],
                    [
                        "text" => "Search",
                        "url" => 'admin/dashboard/search',
                        "icon" => "magnifier",
                    ]
                    //                    [
                    //                        "text" => "Export Events",
                    //                        "url" => 'admin/event/export',
                    //                        "icon" => "arrow-down",
                    //                    ]
                ],
            ],
            // Events
            [
                "text" => "Events Info",
                "url" => 'admin/event',
                "icon" => "calendar",
                "seg0" => ['event', 'edition', 'race'],
                "submenu" => [
                    [
                        "text" => "Events",
                        "url" => 'admin/event',
                        "icon" => "rocket",
                    ],
                    [
                        "text" => "Editions",
                        "url" => 'admin/edition',
                        "icon" => "calendar",
                    ],
                    [
                        "text" => "Races",
                        "url" => 'admin/race',
                        "icon" => "speedometer",
                    ],
                ],
            ],
            // Other info
            [
                "text" => "Other Info",
                "url" => 'admin/town/search',
                "icon" => "settings",
                "seg0" => ['town', 'file', 'url', 'quote'],
                "submenu" => [
                    [
                        "text" => "Towns",
                        "url" => 'admin/town',
                        "icon" => "home",
                    ],
                    [
                        "text" => "Files",
                        "url" => 'admin/file',
                        "icon" => "folder-alt",
                    ],
                    [
                        "text" => "URLs",
                        "url" => 'admin/url',
                        "icon" => "link",
                    ],
                    [
                        "text" => "Venues",
                        "url" => 'admin/venue',
                        "icon" => "pin",
                    ],
                    [
                        "text" => "ASA Licence Fees",
                        "url" => 'admin/asafee',
                        "icon" => "credit-card",
                    ],
                    [
                        "text" => "Tags",
                        "url" => 'admin/tag',
                        "icon" => "tag",
                    ],
                    [
                        "text" => "Dates",
                        "url" => 'admin/date',
                        "icon" => "calendar",
                    ],
                    [
                        "text" => "Quotes",
                        "url" => 'admin/quote',
                        "icon" => "speech",
                    ],
                ],
            ],
            // Mail Queue
            [
                "text" => "Email Module",
                "url" => 'admin/emailque/view/4',
                "icon" => "envelope",
                "seg0" => ['emailque', 'emailmerge', 'emailtemplate'],
                "submenu" => [
                    [
                        "text" => "Email Merges",
                        "url" => 'admin/emailmerge',
                        "icon" => "envelope-open",
                    ],
                    [
                        "text" => "Email Templates",
                        "url" => 'admin/emailtemplate',
                        "icon" => "envelope-letter",
                    ],
                    [
                        "text" => "Drafts",
                        "url" => 'admin/emailque/view/4',
                        "icon" => "pencil",
                    ],
                    [
                        "text" => "Pending",
                        "url" => 'admin/emailque/view/5',
                        "icon" => "login",
                    ],
                    [
                        "text" => "Sent",
                        "url" => 'admin/emailque/view/6',
                        "icon" => "like",
                    ],
                    [
                        "text" => "Failed",
                        "url" => 'admin/emailque/view/7',
                        "icon" => "dislike",
                    ],
                ],
            ],
            // STATIC INFO
            [
                "text" => "Static",
                "url" => '',
                "icon" => "puzzle",
                "seg0" => ['asamember', 'role', 'racetype', 'filetype', 'urltype', 'area', 'province'],
                "submenu" => [
                    [
                        "text" => "ASA Members",
                        "url" => 'admin/asamember',
                        "icon" => "umbrella",
                    ],
                    [
                        "text" => "ASA Regulations",
                        "url" => 'admin/asareg',
                        "icon" => "notebook",
                    ],
                    [
                        "text" => "Timing Providers",
                        "url" => 'admin/timingprovider',
                        "icon" => "clock",
                    ],
                    [
                        "text" => "Roles",
                        "url" => 'admin/role',
                        "icon" => "user",
                    ],
                    [
                        "text" => "Entry Types",
                        "url" => 'admin/entrytype',
                        "icon" => "flag",
                    ],
                    [
                        "text" => "Registration Types",
                        "url" => 'admin/regtype',
                        "icon" => "bell",
                    ],
                    [
                        "text" => "Date Types",
                        "url" => 'admin/datetype',
                        "icon" => "calendar",
                    ],
                    [
                        "text" => "Race Types",
                        "url" => 'admin/racetype',
                        "icon" => "compass",
                    ],
                    [
                        "text" => "File Types",
                        "url" => 'admin/filetype',
                        "icon" => "folder",
                    ],
                    [
                        "text" => "URL Types",
                        "url" => 'admin/urltype',
                        "icon" => "link",
                    ],
                    [
                        "text" => "Tag Types",
                        "url" => 'admin/tagtype',
                        "icon" => "tag",
                    ],
                    [
                        "text" => "Areas",
                        "url" => 'admin/area',
                        "icon" => "map",
                    ],
                    [
                        "text" => "Regions",
                        "url" => 'admin/region',
                        "icon" => "map",
                    ],
                    [
                        "text" => "Provinces",
                        "url" => 'admin/province',
                        "icon" => "globe-alt",
                    ],
                ],
            ],
            // Users
            [
                "text" => "Users",
                "url" => 'admin/user',
                "icon" => "users",
                "seg0" => ['user', 'usersubscription'],
                "submenu" => [
                    [
                        "text" => "Search users",
                        "url" => 'admin/user/search',
                        "icon" => "users",
                    ],
                    [
                        "text" => "User Subscriptions",
                        "url" => 'admin/usersubscription',
                        "icon" => "present",
                    ],
                ],
            ],
            // Results
            [
                "text" => "Results",
                "url" => 'admin/result',
                "icon" => "trophy",
                "seg0" => ['result'],
                "submenu" => [
                    [
                        "text" => "Search Results",
                        "url" => 'admin/result/search',
                        "icon" => "magnifier",
                    ],
                    [
                        "text" => "Races with NO results",
                        "url" => 'admin/race/no_result',
                        "icon" => "speedometer",
                    ],
                    [
                        "text" => "List Results",
                        "url" => 'admin/result/view',
                        "icon" => "list",
                    ],
                    [
                        "text" => "Import Result Set",
                        "url" => 'admin/result/import',
                        "icon" => "login",
                    ],
                ],
            ],
            // Clubs
            [
                "text" => "Clubs",
                "url" => 'admin/club',
                "icon" => "badge",
                "seg0" => ['club'],
            ],
            // Sponsors
            [
                "text" => "Sponsors",
                "url" => 'admin/sponsor',
                "icon" => "wallet",
                "seg0" => ['sponsor'],
            ],
            // Parkruns
            [
                "text" => "Parkruns",
                "url" => 'admin/parkrun',
                "icon" => "direction",
                "seg0" => ['parkrun'],
            ],
        ];
    }

    function get_event_field_list()
    {
        return ['event_id', 'event_name', 'town_id'];
    }

    function get_edition_field_list()
    {
        return ['edition_id', 'edition_name', 'edition_date', 'latitude_num', 'longitude_num', 'edition_url', 'edition_address'];
    }

    function get_race_field_list()
    {
        return ['race_id', 'race_name', 'race_distance', 'race_time_start', 'racetype_id'];
    }

    function get_contact_field_list()
    {
        return ['user_id', 'user_name', 'user_surname', 'user_email'];
    }

    function get_asa_member_field_list()
    {
        return ['asa_member_id'];
    }

    public function get_race_name_from_status($race_name, $race_distance, $racetype_name, $race_status)
    {
        // set return as race_name
        $return_name = $race_name;
        // check for empty
        if (empty($return_name)) {
            switch (true) {
                case $race_distance > 42.2:
                    $return_name = "Ultra Marathon";
                    break;
                case $race_distance == 42.2:
                    $return_name = "Marathon";
                    break;
                case $race_distance == 21.1:
                    $return_name = "Half-Marathon";
                    break;
                case $race_distance < 10:
                    if (strpos($racetype_name, 'Run') !== false) {
                        $return_name = fraceDistance($race_distance) . " Fun Run";
                    } else {
                        $return_name = fraceDistance($race_distance) . " " . $racetype_name;
                    }
                    break;
                default:
                    $return_name = fraceDistance($race_distance) . " " . $racetype_name;
                    break;
            }
        }
        switch ($race_status) {
            case 2:
                $return_name = $return_name . " - DRAFT";
                break;
            case 3:
                $return_name = $return_name . " - CANCELLED";
                break;
        }
        return $return_name;
    }

    public function race_fill_blanks($race_data, $edition_info)
    {
        $this->load->model('admin/asareg_model');
        $this->load->model('admin/asafee_model');
        $this->load->model('admin/racetype_model');

        // check for emtpy race_name 
        if (empty($race_data['race_name'])) {
            if (!isset($race_data['racetype_name'])) {
                $racetype_data = $this->racetype_model->get_racetype_detail($race_data['racetype_id']);
                $racetype_name = $racetype_data['racetype_name'];
            } else {
                $racetype_name = $race_data['racetype_name'];
            }
            $race_data['race_name'] = $this->get_race_name_from_status($race_data['race_name'], $race_data['race_distance'], $racetype_name, $race_data['race_status']);
        }
        // check for empty minimum age
        if (empty($race_data['race_minimum_age'])) {
            // get asa_reg_id
            $asareg_id = $this->asareg_model->get_asareg_id_from_distance($race_data['race_distance']);
            // get asa_reg list
            $asareg_list = $this->asareg_model->get_asareg_list();
            $race_data['race_minimum_age'] = $asareg_list[$asareg_id]['asa_reg_minimum_age'];
        }

        // check senior fees
        if (isset($race_data['race_fee_senior_licenced'])) {
            if (($race_data['race_fee_senior_licenced'] > 0) && ($race_data['race_fee_senior_unlicenced'] == 0)) {

                $licence_fee = $this->asafee_model->get_asafee_from_distance($edition_info['edition_asa_member'], fdateYear($edition_info['edition_date']), $race_data['race_distance']);
                if ($licence_fee > 0) {
                    $race_data['race_fee_senior_unlicenced'] = $race_data['race_fee_senior_licenced'] + $licence_fee;
                }
            }

            // check junior fees
            if (($race_data['race_fee_junior_licenced'] > 0) && ($race_data['race_fee_junior_unlicenced'] == 0)) {
                $licence_fee = $this->asafee_model->get_asafee_from_distance($edition_info['edition_asa_member'], fdateYear($edition_info['edition_date']), $race_data['race_distance'], "asa_fee_jnr");
                if ($licence_fee > 0) {
                    $race_data['race_fee_junior_unlicenced'] = $race_data['race_fee_junior_licenced'] + $licence_fee;
                }
            }
        }

        return $race_data;
    }

    public function set_tags($edition_id, $edition_data, $race_data)
    {
        $this->load->model('admin/tag_model');
        $this->load->model('admin/tagtype_model');

        $tagtype_list = $this->tagtype_model->get_tagtype_list();
        foreach ($tagtype_list as $tagtype_id => $tagtype) {
            $tagtype_arr[$tagtype['tagtype_name']] = $tagtype_id;
        }
        // wts($race_data,1);
        // SET TAGS
        // get race distance tags
        foreach ($race_data as $race_id => $race) {
            $tags[trim($race['race_name'])] = $tagtype_arr['race_name'];
            $distance = $race['race_distance'] + 0;
            $tags[$distance . "km"] = $tagtype_arr['race_distance'];
        }
        unset($tagtype_arr['race_distance']);
        unset($tagtype_arr['race_name']);

        // edition_year
        $tags[fdateYear($edition_data['edition_date'])] = $tagtype_arr['edition_year'];
        unset($tagtype_arr['edition_year']);

        // edition_month
        $tags[fdateMonth($edition_data['edition_date']) . " " . fdateYear($edition_data['edition_date'])] = $tagtype_arr['edition_month'];
        unset($tagtype_arr['edition_month']);

        // REST of fields on flat edition_data array
        foreach ($tagtype_arr as $tagfield_name => $tagfield_id) {
            $tags[$edition_data[$tagfield_name]] = $tagfield_id;
        }

        // CLEAR EDITION TAGS
        $stats['new_tag'] = 0;
        $stats['edition_tag_link'] = 0;
        $this->tag_model->clear_edition_tags($edition_id);
        foreach ($tags as $tag => $tagtype_id) {
            // CHECK IF TAGS EXISTS, ELSE ADD
            $tag = trim($tag);
            if (!empty($tag)) {
                $tag_id = $this->tag_model->exists($tag);
                if (!$tag_id) {
                    $data = [
                        'tag_name' => $tag,
                        'tagtype_id' => $tagtype_id,
                        'tag_status' => 1,
                    ];
                    $tag_id = $this->tag_model->set_tag("add", 0, $data);
                    $stats['new_tag']++;
                }
                // ADD TAG
                $this->tag_model->set_edition_tag($edition_id, $tag_id);
                $stats['edition_tag_link']++;
            }
        }
        //        wts($tagtype_arr);
        //        wts($tags);
        //        echo $edition_id;
        //        wts($race_data);
        //        wts($edition_data, true);
        return ($stats);
    }
}
