<?php

/**
 * Controller to run all cronjobs from
 * Functions for intra_day, daily
 *
 */
class Cron extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('edition_model');
        $this->load->helper('date');
    }

    // ========================================================================
    // FUNCTIONS TO CALL FROM CRONS
    // ========================================================================

    public function intra_day() {
        // set to run every 5 min
        $this->process_mail_que();
    }

    public function daily() {
        // set to run at midnight
        $this->history_summary();
        $this->update_event_info_status();
    }

    // ========================================================================
    // LOGGING SCRIPTS
    // ========================================================================

    private function log_runtime($log_data) {
        $log_data['runtime_start'] = $log_data['start']->format('Y-m-d\TH:i:s');
        $log_data['runtime_end'] = $log_data['end']->format('Y-m-d\TH:i:s');
        $log_data['runtime_duration'] = $log_data['start']->diff($log_data['end'])->format("%s");
        unset($log_data['start']);
        unset($log_data['end']);
        $log = $this->edition_model->log_runtime($log_data);
    }

    private function get_date() {
        return new DateTime();
    }

    // ========================================================================
    // THE ACTUAL JOBS TO RUN
    // ========================================================================  

    private function process_mail_que() {
        // process the mail que and sends out emails        
        $log_data['runtime_jobname'] = __FUNCTION__;
        $log_data['start'] = $this->get_date();

        echo "<p><b>PROCESS EMAIL QUE</b></p>";
        $this->load->model('emailque_model');

        $mail_que = $this->emailque_model->get_emailque_list($this->ini_array['emailque']['que_size'], 5);
        if ($mail_que) {
            foreach ($mail_que as $mail_id => $mail_data) {
                $mail_sent = $this->send_mail($mail_data);
                echo $mail_data['emailque_to_address'] . ": " . fyesNo($mail_sent) . "<br>";
                if ($mail_sent) {
                    $status_id = 6;
                } else {
                    $status_id = 7;
                }
                $this->emailque_model->set_emailque_status($mail_id, $status_id);
            }
            echo "<br>Mailqueue has processed <b>" . sizeof($mail_que) . "</b> mail(s): " . date("Y-m-d H:i:s");
        } else {
            echo "Nothing to process in mailqueue: " . date("Y-m-d H:i:s");
        }
        // LOG RUNTIME DATA
        $log_data['end'] = $this->get_date();
        $this->log_runtime($log_data);
    }

    private function history_summary() {
        // takes hisotry data collected in the history table, and summarizes it into a history_summary table
        $log_data['runtime_jobname'] = __FUNCTION__;
        $log_data['start'] = $this->get_date();

        echo "<p><b>SET HISTORY SUMMARY</b></p>";
        $this->load->model('edition_model');
        $this->load->model('history_model');

        // most visited events of a year
        $history_list_year = $this->history_model->get_most_visited_url_list(date("Y-m-d H:i:s", strtotime("-1 year")));
//        wts($history_list_year);
        foreach ($history_list_year as $url) {
            $url_sections = explode("/", $url['history_url']);
            $edition_info = $this->edition_model->get_edition_id_from_slug($url_sections[4]);

            // tel al die edition sub-pages by mekaar om die count vir die edition te kry
            if (isset($count_list_year[$edition_info['edition_id']])) {
                $count = $url['url_count'] + $count_list_year[$edition_info['edition_id']]['count'];
                $count_list_year[$edition_info['edition_id']]['count'] = $count;
            } else {
                $count_list_year[$edition_info['edition_id']]['count'] = $url['url_count'];
                $count_list_year[$edition_info['edition_id']]['url'] = $url['history_url'];
                $count_list_year[$edition_info['edition_id']]['lastvisited'] = $url['lastvisited'];
                $edition_id_list[$edition_info['edition_id']] = $edition_info['edition_id'];
            }
        }
        $query_params = [
            "where_in" => ["editions.edition_id" => $edition_id_list],
            "order_by" => ["edition_date" => "DESC"],
        ];
        $most_visited_events = $this->edition_model->get_edition_list($query_params);
        $this->history_model->set_history_summary($most_visited_events, $count_list_year);

        // get counts for the last month
        $history_list_month = $this->history_model->get_most_visited_url_list(date("Y-m-d H:i:s", strtotime("-1 month")));
        foreach ($history_list_month as $url) {
            $url_sections = explode("/", $url['history_url']);
            $edition_info = $this->edition_model->get_edition_id_from_slug($url_sections[4]);

            // tel al die edition sub-pages by mekaar om die count vir die edition te kry
            if (isset($count_list_month[$edition_info['edition_id']])) {
                $count = $url['url_count'] + $count_list_month[$edition_info['edition_id']];
            } else {
                $count = $url['url_count'];
            }
            $count_list_month[$edition_info['edition_id']] = $count;
        }
        $this->history_model->update_history_counts($count_list_month, "historysum_countmonth");


        // get counts for the last week
        $history_list_week = $this->history_model->get_most_visited_url_list(date("Y-m-d H:i:s", strtotime("-1 week")));
        foreach ($history_list_week as $url) {
            $url_sections = explode("/", $url['history_url']);
            $edition_info = $this->edition_model->get_edition_id_from_slug($url_sections[4]);
            // tel al die edition sub-pages by mekaar om die count vir die edition te kry
            if (isset($count_list_week[$edition_info['edition_id']])) {
                $count = $url['url_count'] + $count_list_week[$edition_info['edition_id']];
            } else {
                $count = $url['url_count'];
            }
            $count_list_week[$edition_info['edition_id']] = $count;
        }
        $this->history_model->update_history_counts($count_list_week, "historysum_countweek");

        // LOG RUNTIME DATA
        $log_data['end'] = $this->get_date();
        $this->log_runtime($log_data);

        echo "History summary set: <b>" . date("Y-m-d H:i:s") . "</b>";
    }

    private function update_event_info_status() {
        // script to move the event_info_status flag alog once an event has completed        
        $log_data['runtime_jobname'] = __FUNCTION__;
        $log_data['start'] = $this->get_date();

        echo "<p><b>UPDATE EVENT INFO STATUS</b></p>";

        $query_params = [
            "where" => ["edition_info_status" => 16, "edition_date <= " => date("Y-m-d H:i:s", strtotime("yesterday"))],
        ];
//        wts($query_params);
        $edition_list_to_update = $this->edition_model->get_edition_list($query_params);
        if ($edition_list_to_update) {
            foreach ($edition_list_to_update as $edition_id => $edition) {
                $this->edition_model->update_field($edition_id, "edition_info_status", 10);
                echo "Updated " . $edition['edition_name'] . " to pending results status<br>";
            }
        } else {
            echo "No editions with info status verified in the past";
        }

        // LOG RUNTIME DATA
        $log_data['end'] = $this->get_date();
        $this->log_runtime($log_data);
    }

}
