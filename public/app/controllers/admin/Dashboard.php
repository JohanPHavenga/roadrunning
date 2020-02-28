<?php
class Dashboard extends Admin_Controller {

    
    // check if method exists, if not calls "view" method
    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->view();
        }
    }

    public function view($page = 'dashboard') {
        if (!file_exists(APPPATH . 'views/admin/dashboard/' . $page . '.php')) {
            // Whoops, we don't have a page for that!
            show_404();
        }
        // unset edition return url session, should it exists
        $this->session->unset_userdata('edition_return_url');

        $this->view_url = "/admin/dashboard/" . $page;

        $this->data_to_header['title'] = ucfirst($page);
        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Dashboard" => "",
        ];

        $this->data_to_footer['js_to_load'] = array(
            "assets/admin/plugins/bootstrap-confirmation/bootstrap-confirmation.js",
        );

        if ($page == "dashboard") {
            $this->load->library('table');
            $this->load->model('admin/history_model');
            $this->load->model('admin/event_model');
            $this->load->model('admin/edition_model');
            $this->load->model('admin/race_model');
            $this->load->model('admin/date_model');
            $this->load->model('admin/emailmerge_model');

            $history_count = $this->history_model->record_count();
            $event_count = $this->event_model->record_count();
            $edition_count = $this->edition_model->record_count();
//            $race_count = $this->race_model->record_count();
            $merge_count = $this->emailmerge_model->count_draft();

            $this->data_to_view['dashboard_stats_list'] = [
                [
                    "text" => "Most visited URLs",
                    "number" => $history_count,
                    "font-color" => "yellow-gold",
                    "icon" => "icon-hourglass",
                    "uri" => "/admin/dashboard/history/summary",
                ],
                [
                    "text" => "Draft Email Merges",
                    "number" => $merge_count,
                    "font-color" => "red-haze",
                    "icon" => "icon-mail",
                    "uri" => "/admin/emailmerge/view",
                ],
                [
                    "text" => "Number of Events",
                    "number" => $event_count,
                    "font-color" => "green-sharp",
                    "icon" => "icon-rocket",
                    "uri" => "/admin/event/view",
                ],
                [
                    "text" => "Number of Editions",
                    "number" => $edition_count,
                    "font-color" => "blue-sharp",
                    "icon" => "icon-calendar",
                    "uri" => "/admin/edition/view",
                ],
                    //font-purple-soft
            ];

            // get list of editions that need attention
            $params = [
                'only_active' => 1,
                'info_status' => [13,14,15],
                'date_from' => date("Y-m-d"),
                'date_to' => date("Y-m-d", strtotime("+3 months")),
            ];
            $this->data_to_view['event_list_unconfirmed'] = $this->event_model->get_event_list_summary("date_range", $params);

            // set dashbaord_return_url for editions to return here
            $this->session->set_userdata('dashboard_return_url', "/" . uri_string());

            // get list of editions that has no results
            $params = [
                'info_status' => [10],
                'date_from' => date("Y-m-d", strtotime("-1 month")),
                'date_to' => date("Y-m-d"),
            ];
            $this->data_to_view['event_list_noresults'] = $this->event_model->get_event_list_summary("date_range", $params);

            // get list of editions where the entry closing dates is near
            $params = [
                'date_from' => date("Y-m-d"),
                'entry_date' => date("Y-m-d", strtotime("1 week")),
                'only_active' => 1,
            ];
            $entry_date_close_data = $this->event_model->get_event_list_summary("date_range", $params);
            $date_list = $this->date_model->get_date_list("edition",0,true);
            $entry_data = [];
//            wts($entry_date_close_data);
//            wts($date_list);
            foreach ($entry_date_close_data as $year => $year_list) {
                foreach ($year_list as $month => $month_list) {
                    foreach ($month_list as $day => $edition_list) {
                        foreach ($edition_list as $edition_id => $edition) {
                            $entry_data[$edition_id]['name'] = "<a href='/admin/edition/create/edit/" . $edition['edition_id'] . "'>" . $edition['edition_name'] . "</a>";
                            $entry_data[$edition_id]['merge_url'] = '<a href="/admin/emailmerge/wizard" class="btn btn-xs blue">Mail Merge</a>';
                            $entry_data[$edition_id]['entry_close'] = $date_list[3][$edition_id]['date_end'];
                        }
                    }
                }
            }
//            wts($entry_data,1);
            
            // sort array
            uasort($entry_data, function ($item1, $item2) {
                return $item1['entry_close'] <=> $item2['entry_close'];
            });
            $this->data_to_view['event_list_entry_date'] = $entry_data;

            // actions on the toolbar
            $this->data_to_header['page_action_list'] = [
                [
                    "name" => "Add Event",
                    "icon" => "rocket",
                    "uri" => "event/create/add",
                ],
                [
                    "name" => "Add Edition",
                    "icon" => "calendar",
                    "uri" => "edition/create/add",
                ],
                [
                    "name" => "Add Race",
                    "icon" => "trophy",
                    "uri" => "race/create/add",
                ],
            ];
        }

        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view($this->view_url, $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function audit($year=0) {
        if (!is_numeric($year)) { die("That is not a year"); }
        if ($year>date("Y")) { die("Year too big"); }
        if ($year<2016) { die("Year too small"); }
        $previous_year = $year - 1;

        $this->load->library('table');
        $this->load->model('admin/event_model');

        $this->data_to_header['title'] = "Data Audit";
        $this->data_to_header['crumbs'] = [
            "Home" => "/admin",
            "Dashboard" => "/admin/dashboard",
            "Audit" => "",
        ];
        
        $this->data_to_header['css_to_load'] = array(
            "assets/admin/plugins/datatables/datatables.min.css",
            "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css",
        );

        $this->data_to_footer['js_to_load'] = array(
            "assets/admin/scripts/datatable.js",
            "assets/admin/plugins/datatables/datatables.min.js",
            "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js",
            "assets/admin/plugins/bootstrap-confirmation/bootstrap-confirmation.js",
        );

        $this->data_to_footer['scripts_to_load'] = array(
            "assets/admin/scripts/table-datatables-managed.js",
        );

        $this->data_to_view['year'] = $year;
        $event_info = $this->event_model->get_missing_editions($year);

        foreach ($event_info as $event_id => $event) {
            if (isset($event[$previous_year]) && (!isset($event[$year]))) {
                $this->data_to_view['missing_editions'][$event_id] = $event;
            }
        }

        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/admin/dashboard/audit", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

    public function history($view_type) {

        $this->load->library('table');
        $this->load->model('admin/history_model');

        if ($view_type == "summary") {
            $this->data_to_header['title'] = "History Summary";
            $this->data_to_header['crumbs'] = [
                "Home" => "/admin",
                "Dashboard" => "/admin/dashboard",
                "History Summary" => "",
            ];

            $query_params = [
                "order_by" => ["historysum_countweek" => "DESC", "historysum_countmonth" => "DESC", "historysum_countyear" => "DESC"],
            ];
            $this->data_to_view['history_summary'] = $this->history_model->get_history_summary($query_params);

            $this->data_to_header['css_to_load'] = array(
                "assets/admin/plugins/datatables/datatables.min.css",
                "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css",
            );

            $this->data_to_footer['js_to_load'] = array(
                "assets/admin/scripts/datatable.js",
                "assets/admin/plugins/datatables/datatables.min.js",
                "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js",
            );

            $this->data_to_footer['scripts_to_load'] = array(
                "assets/admin/scripts/table-datatables-managed.js",
            );

            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view("/admin/dashboard/history_summary", $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        } else {
            show_404();
        }
    }

    public function search() {
        $page = "search";
        $this->data_to_header['title'] = ucfirst($page);

        $this->load->library('table');
        $this->load->model('admin/event_model');

        if ($this->input->get('query')) {
            $params = ["ss" => $this->input->get('query'), "inc_all" => true, "inc_non_active" => true];
            $this->data_to_view['search_results'] = $this->event_model->get_event_list_summary($from = "search", $params);
            $this->data_to_view['msg'] = "<p>We could <b>not find</b> any event matching your search.<br>Please try again.</p>";
        } else {
            $this->data_to_view['msg'] = "Please use the <b>search box</b> above to seach for a race.";
        }

//        wts($this->data_to_view['search_results']);
//        die();

        $this->data_to_view['heading'] = ["ID", "Edition Name", "Status", "Edition Date", "Event Name", "Actions"];

        $this->data_to_header['css_to_load'] = array(
            "assets/admin/plugins/datatables/datatables.min.css",
            "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css",
        );

        $this->data_to_footer['js_to_load'] = array(
            "assets/admin/scripts/datatable.js",
            "assets/admin/plugins/datatables/datatables.min.js",
            "assets/admin/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js",
            "assets/admin/plugins/bootstrap-confirmation/bootstrap-confirmation.js",
        );

        $this->data_to_footer['scripts_to_load'] = array(
            "assets/admin/scripts/table-datatables-managed.js",
        );

        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view("/admin/dashboard/search", $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

}
