<?php

/**
 * Controller to run all cronjobs from
 * Functions for intra_day, daily
 *
 */
class Cron extends Frontend_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('edition_model');
    $this->load->model('emailque_model');
    $this->load->helper('date');
    $this->output->set_content_type('text/plain', 'utf-8');
  }

  // ========================================================================
  // FUNCTIONS TO CALL FROM CRONS
  // ========================================================================

  public function intra_day()
  {
    // set to run every 5 min and continue until que empty
    while ($this->have_mail_in_mailque()) {
      $this->process_mail_que($quiet = true);
    }
  }

  public function daily()
  {
    // set to run at midnight
    $this->history_purge();
    $this->update_event_info_status();
    $this->autoemails_closing_date();
    $this->runtime_log_purge();
    $this->history_summary();
  }

  // ========================================================================
  // LOGGING SCRIPTS
  // ========================================================================

  private function log_runtime($log_data)
  {
    $log_data['runtime_start'] = $log_data['start']->format('Y-m-d\TH:i:s');
    $log_data['runtime_end'] = $log_data['end']->format('Y-m-d\TH:i:s');
    // $log_data['runtime_duration'] = $log_data['start']->diff($log_data['end'])->format("%s");

    $log_data['runtime_duration'] = $log_data['start']->diff($log_data['end'])->format("%h:%i:%s");

    // wts($log_data,1);
    unset($log_data['start']);
    unset($log_data['end']);
    $log = $this->edition_model->log_runtime($log_data);
  }

  private function get_date()
  {
    return new DateTime();
  }

  // ========================================================================
  // THE ACTUAL JOBS TO RUN
  // ========================================================================

  private function have_mail_in_mailque()
  {
    // if anything is returned, return true
    return $this->emailque_model->get_emailque_list(1, 5);
  }

  public function process_mail_que($quiet = false)
  {
    // process the mail que and sends out emails
    $log_data['runtime_jobname'] = __FUNCTION__;
    $log_data['start'] = $this->get_date();

    if (!$quiet) {
      echo "** PROCESS EMAIL QUE\n";
    }
    $this->load->model('emailque_model');
    $mail_que = [];
    $mail_que = $this->emailque_model->get_emailque_list($this->ini_array['emailque']['que_size'], 5);
    if ($mail_que) {
      $log_data['runtime_count'] = count($mail_que);
      foreach ($mail_que as $mail_id => $mail_data) {
        $mail_sent = $this->send_mail($mail_data);
        //                $mail_sent=1;
        if (!$quiet) {
          echo $mail_data['emailque_to_address'] . ": " . fyesNo($mail_sent) . "\n";
        }
        if ($mail_sent) {
          $status_id = 6;
        } else {
          $status_id = 7;
        }
        $this->emailque_model->set_emailque_status($mail_id, $status_id);
      }
      if (!$quiet) {
        echo "Mailqueue has processed " . sizeof($mail_que) . " mail(s): " . date("Y-m-d H:i:s") . "\n\r";
      }
    } else {
      if (!$quiet) {
        echo "Nothing to process in mailqueue: " . date("Y-m-d H:i:s") . "\n\r";
      }
    }
    // LOG RUNTIME DATA
    $log_data['end'] = $this->get_date();
    $this->log_runtime($log_data);
  }


  private function history_summary()
  {
    // takes hisotry data collected in the history table, and summarizes it into a history_summary table
    $log_data['runtime_jobname'] = __FUNCTION__;
    $log_data['start'] = $this->get_date();

    echo "** SET HISTORY SUMMARY\n";
    echo "History summary start: " . date("Y-m-d H:i:s") . "\n";
    $this->load->model('edition_model');
    $this->load->model('history_model');

    // most visited events of a year
    $history_list_year = $this->history_model->get_most_visited_url_list(date("Y-m-d H:i:s", strtotime("-1 year")));

    // wts($history_list_year,1);
    // nie nodig as ons nie edition id gebruik nie
    foreach ($history_list_year as $url) {
      $url_sections = explode("/", $url['history_url']);
      // skip if not an event
      if (isset($url_sections[4])) {
        $edition_info = $this->edition_model->get_edition_id_from_slug($url_sections[4]);
        if ($edition_info) {
          if (isset($count_list_year[$edition_info['edition_id']])) {
            $count_list_year[$edition_info['edition_id']]['url_count'] += $url['url_count'];
          } else {
            $count_list_year[$edition_info['edition_id']] = $url;
          }
          $count_list_year[$edition_info['edition_id']]['edition_name'] = $edition_info['edition_name'];
        }
      }
    }
    // --
    // wts($count_list_year,1);
    $this->history_model->set_history_summary($count_list_year);

    // get counts for the last month
    $history_list_month = $this->history_model->get_most_visited_url_list(date("Y-m-d H:i:s", strtotime("-1 month")));
    $count_list_month = [];
    foreach ($history_list_month as $url) {
      $url_sections = explode("/", $url['history_url']);
      // skip if not an event
      if (isset($url_sections[4])) {
        $edition_info = $this->edition_model->get_edition_id_from_slug($url_sections[4]);
        if ($edition_info) {
          if (isset($count_list_month[$edition_info['edition_id']])) {
            $count_list_month[$edition_info['edition_id']] += $url['url_count'];
          } else {
            $count_list_month[$edition_info['edition_id']] = $url['url_count'];
          }
        }
      }
    }
    // wts($count_list_month,1);
    $this->history_model->update_history_counts($count_list_month, "historysum_countmonth");

    // get counts for the last week
    $history_list_week = $this->history_model->get_most_visited_url_list(date("Y-m-d H:i:s", strtotime("-1 week")));
    $count_list_week = [];
    foreach ($history_list_week as $url) {
      $url_sections = explode("/", $url['history_url']);
      // skip if not an event
      if (isset($url_sections[4])) {
        $edition_info = $this->edition_model->get_edition_id_from_slug($url_sections[4]);
        if ($edition_info) {
          if (isset($count_list_week[$edition_info['edition_id']])) {
            $count_list_week[$edition_info['edition_id']] += $url['url_count'];
          } else {
            $count_list_week[$edition_info['edition_id']] = $url['url_count'];
          }
        }
      }
    }
    $this->history_model->update_history_counts($count_list_week, "historysum_countweek");

    // LOG RUNTIME DATA
    $log_data['end'] = $this->get_date();
    $this->log_runtime($log_data);

    echo "History summary set: " . date("Y-m-d H:i:s") . "\n\r";
  }

  private function history_summary_old()
  {
    // takes hisotry data collected in the history table, and summarizes it into a history_summary table
    $log_data['runtime_jobname'] = __FUNCTION__;
    $log_data['start'] = $this->get_date();

    echo "** SET HISTORY SUMMARY\n";
    $this->load->model('edition_model');
    $this->load->model('history_model');

    // most visited events of a year
    $history_list_year = $this->history_model->get_most_visited_url_list(date("Y-m-d H:i:s", strtotime("-1 year")));
    // wts($history_list_year);
    foreach ($history_list_year as $url) {
      $url_sections = explode("/", $url['history_url']);
      $edition_info = $this->edition_model->get_edition_id_from_slug($url_sections[4]);

      if (!isset($url_sections[5])) {
        // tel al die edition sub-pages by mekaar om die count vir die edition te kry
        if (isset($count_list_year[$edition_info['edition_id']])) {
          $count = $url['url_count'] + $count_list_year[$edition_info['edition_id']]['count'];
          $count_list_year[$edition_info['edition_id']]['count'] = $count;
        } else {
          $count_list_year[$edition_info['edition_id']]['count'] = $url['url_count'];
          $count_list_year[$edition_info['edition_id']]['url'] = $url['history_url'];
          $count_list_year[$edition_info['edition_id']]['lastvisited'] = $url['lastvisited'];
          $edition_id_list[$edition_info['edition_id']] = $url_sections[4];
        }
      }
    }
    wts($edition_id_list);
    wts($count_list_year, 1);

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
      if (!isset($url_sections[5])) {
        if (isset($count_list_month[$edition_info['edition_id']])) {
          $count = $url['url_count'] + $count_list_month[$edition_info['edition_id']];
        } else {
          $count = $url['url_count'];
        }
        $count_list_month[$edition_info['edition_id']] = $count;
      }
    }
    $this->history_model->update_history_counts($count_list_month, "historysum_countmonth");


    // get counts for the last week
    $history_list_week = $this->history_model->get_most_visited_url_list(date("Y-m-d H:i:s", strtotime("-1 week")));
    foreach ($history_list_week as $url) {
      $url_sections = explode("/", $url['history_url']);
      $edition_info = $this->edition_model->get_edition_id_from_slug($url_sections[4]);
      // tel al die edition sub-pages by mekaar om die count vir die edition te kry
      if (!isset($url_sections[5])) {
        if (isset($count_list_week[$edition_info['edition_id']])) {
          $count = $url['url_count'] + $count_list_week[$edition_info['edition_id']];
        } else {
          $count = $url['url_count'];
        }
        $count_list_week[$edition_info['edition_id']] = $count;
      }
    }
    $this->history_model->update_history_counts($count_list_week, "historysum_countweek");

    // LOG RUNTIME DATA
    $log_data['end'] = $this->get_date();
    $this->log_runtime($log_data);

    echo "History summary set: " . date("Y-m-d H:i:s") . "\n\r";
  }

  private function history_purge()
  {
    // removes history data older than a year
    $log_data['runtime_jobname'] = __FUNCTION__;
    $log_data['start'] = $this->get_date();

    echo "** HISTORY PURGE\n";
    $this->load->model('edition_model');
    $this->load->model('history_model');

    // remove hisroty records older than a year
    $log_data['runtime_count'] = $this->history_model->remove_old_history(date("Y-m-d", strtotime("-1 year")));

    // LOG RUNTIME DATA
    $log_data['end'] = $this->get_date();
    $this->log_runtime($log_data);

    echo "History purge complete with " . $log_data['runtime_count'] . " records removed - " . date("Y-m-d H:i:s") . "\n\r";
  }

  private function update_event_info_status()
  {
    // script to move the event_info_status flag alog once an event has completed        
    $log_data['runtime_jobname'] = __FUNCTION__;
    $log_data['start'] = $this->get_date();

    echo "** UPDATE EVENT INFO STATUS\n";

    $query_params = [
      "where" => ["edition_info_status" => 16, "edition_date <= " => date("Y-m-d H:i:s", strtotime("yesterday"))],
    ];
    //        wts($query_params);
    $edition_list_to_update = $this->edition_model->get_edition_list($query_params);
    if ($edition_list_to_update) {
      foreach ($edition_list_to_update as $edition_id => $edition) {
        $this->edition_model->update_field($edition_id, "edition_info_status", 10);
        echo "Updated " . $edition['edition_name'] . " to pending results status\n\r";
      }
      $log_data['runtime_count'] = count($edition_list_to_update);
    } else {
      echo "No editions with info status verified in the past\n\r";
      $log_data['runtime_count'] = 0;
    }

    // LOG RUNTIME DATA
    $log_data['end'] = $this->get_date();
    $this->log_runtime($log_data);
  }

  private function autoemails_closing_date()
  {
    $log_data['runtime_jobname'] = __FUNCTION__;
    $log_data['start'] = $this->get_date();

    echo "** AUTO EMAILS on CLOSING DATE\n";

    $this->load->model('edition_model');
    $this->load->model('date_model');

    $query_params = [
      "where_in" => ["region_id" => $this->session->region_selection, "edition_status" => [1, 3, 4, 17]],
      "where" => ["edition_date >= " => date("Y-m-d H:i:s"), "edition_date <= " => date("Y-m-d H:i:s", strtotime("3 months"))],
    ];
    $edition_list = $this->date_model->add_dates($this->edition_model->get_edition_list($query_params));

    $n = 0;
    foreach ($edition_list as $edition_id => $edition) {
      if (isset($edition['date_list'][3][0]['date_end'])) {
        $online_close_date = strtotime($edition['date_list'][3][0]['date_end']);
      } else {
        $online_close_date = 0;
      }
      if (($online_close_date > time()) && ($online_close_date < strtotime("3 days"))) {
        if ($this->auto_mailer(4, $edition_id)) {
          $n++;
        }
      }
    }

    $log_data['runtime_count'] = $n;
    echo "$n Auto Emails Set: " . date("Y-m-d H:i:s") . "\n\r";

    // LOG RUNTIME DATA
    $log_data['end'] = $this->get_date();
    $this->log_runtime($log_data);
  }

  private function runtime_log_purge()
  {
    // removes history data older than a year
    $log_data['runtime_jobname'] = __FUNCTION__;
    $log_data['start'] = $this->get_date();

    echo "** RUNTIME LOG PURGE\n";
    $this->load->model('history_model');

    $log_data['runtime_count'] = $this->history_model->runtime_log_cleanup(date("Y-m-d", strtotime("-1 year")));

    // LOG RUNTIME DATA
    $log_data['end'] = $this->get_date();
    $this->log_runtime($log_data);

    echo "Runtime purge complete with " . $log_data['runtime_count'] . " records removed - " . date("Y-m-d H:i:s") . "\n\r";
  }
}
