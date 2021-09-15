<?php

// NOTE: Main history checks is done in the Frontend_Controller
// This Controller is used to aggegate the data into the history summary page via a cronjob
defined('BASEPATH') or exit('No direct script access allowed');

class History extends Frontend_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('history_model');
    }

    public function summary()
    {
        $this->load->model('edition_model');

        // most visited events of a year
        $history_list_year = $this->history_model->get_most_visited_url_list(date("Y-m-d H:i:s", strtotime("-1 year")));
        foreach ($history_list_year as $url) {
            $url_sections = explode("/", $url['history_url']);
            $edition_info = $this->edition_model->get_edition_id_from_slug($url_sections[4]);
            $count_list_year[$edition_info['edition_id']]['count'] = $url['url_count'];
            $count_list_year[$edition_info['edition_id']]['url'] = $url['history_url'];
            $edition_id_list[$edition_info['edition_id']] = $edition_info['edition_id'];
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
            $count_list_month[$edition_info['edition_id']] = $url['url_count'];
        }
        $this->history_model->update_history_counts($count_list_month, "historysum_countmonth");


        // get counts for the last week
        $history_list_week = $this->history_model->get_most_visited_url_list(date("Y-m-d H:i:s", strtotime("-1 week")));
        foreach ($history_list_week as $url) {
            $url_sections = explode("/", $url['history_url']);
            $edition_info = $this->edition_model->get_edition_id_from_slug($url_sections[4]);
            $count_list_week[$edition_info['edition_id']] = $url['url_count'];
        }
        $this->history_model->update_history_counts($count_list_week, "historysum_countweek");
    }

    function add_baseurl($limit = 1000)
    {
        $history_list = $this->history_model->get_history_list_limit("history_control", 0, $limit);
        $n = 0;
        foreach ($history_list as $history_item) {
            /// TO DO
            $url_sections = explode("/", $history_item->history_url);
            if (!isset($url_sections[4])) {
                $url_sections[4] = '';
            }
            // wts($url_sections);
            $secion_4_exclusions = ["add", "ics"];
            $history_data = [
                "history_control" => 1
            ];
            if (($url_sections[3] == "event") && (!in_array($url_sections[4], $secion_4_exclusions))) {
                $history_base_url = base_url("event/" . $url_sections[4]);
                $history_data["history_baseurl"] = $history_base_url;
                $n++;
            }
            $this->history_model->update_history($history_data, $history_item->history_id);
        }

        echo "Done<br>";
        echo "<b>" . $n . "/".$limit."</b> history records were updated with a <b>baseurl</b> field<br>";

        $control_count=$this->history_model->get_history_control_count();
        echo "<b>" . $control_count . "</b> records remain";
    }
}
