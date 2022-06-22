<?php
//    wts($crumbs_arr,1);
?>

<section id="page-content">
    <div class="container">
        <!-- Content-->
        <div class="content ">
            <?php
            // CRUMBS WIDGET
            $this->load->view('widgets/crumbs');
            ?>
            <h3 class="text-uppercase"><?= $page_title; ?>
                <span class="badge badge-success" style="font-size: 0.6em; position: relative; top: -2px">Beta</span>
            </h3>

            <?php
            $search_url = base_url("result/search");
            $attributes = array('class' => '', 'role' => 'form');
            echo form_open($search_url, $attributes);
            ?>
            <div class="row">

                <div class="form-group col-lg-6">
                    <?php
                    echo form_input([
                        'name' => 'result_search',
                        'id' => 'result_search',
                        'value' => set_value('result_search'),
                        'class' => 'form-control required',
                        'placeholder' => 'Search for a race',
                        'required' => '',
                    ]);
                    ?>
                </div>

                <div class="form-group col-lg-4">
                    <?php
                    $data = array(
                        'type' => 'submit',
                        'content' => 'Search',
                        'class' => 'btn',
                    );
                    echo form_button($data);
                    ?>
                </div>
            </div>
            <?php
            echo form_close();

            if ((!$race_list_no_results) && (!$race_list)) {
            ?>
                <div class="alert alert-danger m-b-15" role="alert">
                    <i class="fa fa-minus-circle" aria-hidden="true"></i> <b>No races found</b> for your search query. Please try again.
                </div>
                <?php
            } else {

                if ($race_list) {
                    echo "<h5>Click on the table row to view possible result matches</h5>";
                    $template = array(
                        'table_open' => '<table class="table table-striped table-bordered table-hover table-sm result_table">',
                        'row_start' => '<tr class="clickableRow">',
                    );

                    $this->table->set_template($template);
                    $this->table->set_heading('Date', 'Event - Race', 'Distance', 'Race Type');
                    foreach ($race_list as $race_id => $race) {
                        $url = base_url("result/list/" . $race['race_id']);
                        $row = [
                            // "<a href='$url'>" . $race['race_id'] . "</a>",
                            "<a href='$url'>" . fdateShort($race['edition_date']) . "</a>",
                            "<a href='$url'><b>" . $race['event_name'] . "</b> - " . $race['race_name'] . "</a>",
                            "<a href='$url'><span class='badge badge-" . $race['race_color'] . "'>" . round($race['race_distance'], 0) . "km</span></a>",
                            "<a href='$url'>" . $race['racetype_abbr'] . "</a>",
                        ];
                        $this->table->add_row($row);
                    }
                    echo $this->table->generate();
                } else {
                ?>
                    <div class="alert alert-warning m-b-15" role="alert">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> No races found <b class="text-uppercase">with loaded results</b> for your search query.
                    </div>
                <?php
                }
                ?>
                <h3 class="text-uppercase m-t-40">Add Result Manually
                    <span class="badge badge-danger" style="font-size: 0.6em; position: relative; top: -2px">New</span>
                </h3>
                <?php

                if ($race_list_no_results) {
                    echo "<h5>Click on the table row to MANUALLY ADD a result for yourself for the race listed</h5>
                <p style='font-size: 0.9em'>Note this is for personal record kepping purposes only. Official results used for qualifying times needs to be send from the official timekeepers website directly.";
                    $template = array(
                        'table_open' => '<table class="table table-striped table-bordered table-hover table-sm result_table">',
                        'row_start' => '<tr class="clickableRow">',
                    );

                    $this->table->set_template($template);
                    $this->table->set_heading(['Date', 'Event - Race', 'Distance', 'Race Type']);
                    $year_hold=0;
                    foreach ($race_list_no_results as $race_id => $race) {
                        $year=date('Y',strtotime($race['edition_date']));
                        if ($year!=$year_hold) {
                            $cell = array('data' => $year, 'class' => 'table-secondary', 'colspan' => 4);
                            $this->table->add_row($cell);
                            $year_hold=$year;
                        }
                        $url = base_url("result/add/" . $race['race_id']);
                        $row = [
                            // "<a href='$url'>" . $race['race_id'] . "</a>",
                            "<a href='$url'><i class='fa fa-plus-circle' style='color: #26b8f3'></i> " . fdateShort($race['edition_date']) . "</a>",
                            "<a href='$url'><b>" . $race['event_name'] . "</b> - " . $race['race_name'] . "</a>",
                            "<a href='$url'><span class='badge badge-" . $race['race_color'] . "'>" . round($race['race_distance'], 0) . "km</span></a>",
                            "<a href='$url'>" . $race['racetype_abbr'] . "</a>",
                        ];
                        $this->table->add_row($row);
                    }
                    echo $this->table->generate();
                } else {
                ?>
                    <div class="alert alert-warning m-b-15" role="alert">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> <b>No races found</b> for your search query. Please try again.
                    </div>
            <?php
                }
            }
            ?>
            <div>
                <a class="btn btn-outline" data-target="#modal" data-toggle="modal" href="#">
                    <i class="fa fa-question-circle"></i> Where is my result</a>
            </div>
        </div>
        <!-- end: Content-->

    </div>
</section>
<!-- end: My Results -->

<div class="modal fade" id="modal" tabindex="-1" role="modal" aria-labelledby="modal-label" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-uppercase" id="modal-label">Where is my result?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>
                            Not all results are made avaiable in a format we are able to pull into our site.
                            Most races are done my electronic timing means and results are avaialble on their websites directlty.</p>
                        <p>
                            Those results that are made avaialble are hand loaded into our system.
                            It generally takes less than 7 working days for results to be published. </p>
                        <p>Please <a href="/contact">contact us</a> to request a specific result be loaded.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-b" href="/contact">Request result load</a>
                <button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>