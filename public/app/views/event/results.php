<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2>Results</h2>
            </div>
        </div>
        <?php
        $this->load->view('widgets/race_meta');
        ?>      
        <div class="row">
            <!-- Content-->
            <?php
            $mailing_list_notice = "<p>If you would like to be <b>notified once results are loaded</b>, "
                    . "please enter your email below or to the right to be added to the "
                    . "<a href='" . base_url('event/' . $slug . '/subscribe') . "' title='Add yourself to the mailing list'>mailing list</a> for this race.</p>";
            // if date in future
            if (!$in_past) {
                $days_from_now = date("d", strtotime($edition_data['edition_date']) - time());
                ?>
                <div class="content col-lg-9">
                    <p><b class='text-danger'>No results available yet.</b><br>
                        This race is scheduled to take place on 
                        <b><?= fdateHumanFull($edition_data['edition_date']); ?></b>, 
                        <?= $days_from_now; ?> days from now.</p>
                    <p>Once the race has run, depending on the timing method used, results can take <u>up to 7 working days</u> to be released.
                        As soon as results are published by the organisers I will load it here.</p>
                    <?= $mailing_list_notice; ?>
                </div>
                <!-- Sidebar-->
                <div class="sidebar col-lg-3">  
                    <?php
                    // SUBSCRIBE WIDGET
                    $data_to_widget['title'] = "Add yourself to the race mailing list";
                    $this->load->view('widgets/subscribe', $data_to_widget);

                    // ADS WIDGET
                    $this->load->view('widgets/side_ad');
                    ?>
                </div>
                <!-- end: Sidebar-->
                <?php
            } else {
                $days_ago = date("j", time() - strtotime($edition_data['edition_date']));
                switch ($edition_data['edition_info_status']) {
                    case 10:
                        // pending
                        ?>
                        <div class="content col-lg-9">
                            <div role="alert" class="m-t-10 m-b-20 alert alert-<?= $status_notice['state']; ?>">
                                <i class="fa fa-<?= $status_notice['icon']; ?>"></i> <b><?= $status_notice['msg']; ?></b>
                            </div>
                            <p class="text-danger"><b>Race was ran <?= $days_ago; ?> day(s) ago.</b></p>
                            <p><b>Please note:</b> Results can take <u>up to 7 working days</u> to be released. 
                                As soon as results are published by the organisers I will load it here.</p>
                            <?= $mailing_list_notice; ?>
                        </div>
                        <!-- Sidebar-->
                        <div class="sidebar col-lg-3">  
                            <?php
                            // SUBSCRIBE WIDGET
                            $data_to_widget['title'] = "Add yourself to the race mailing list";
                            $this->load->view('widgets/subscribe', $data_to_widget);

                            // ADS WIDGET
                            $this->load->view('widgets/side_ad');
                            ?>
                        </div>
                        <!-- end: Sidebar-->
                        <?php
                        break;
                    case 11;
                        // loaded
                        if (isset($results['race'])) {
                            ?>
                            <div class="content col-lg-12">
                                <?php
                                if ($result_list) {
                                    ?>
                                    <div class="tabs tabs-folder m-t-10">
                                        <ul class="nav nav-tabs" id="resultsTab" role="tablist">
                                            <?php
                                            foreach ($results['race'] as $key => $race_result) {
                                                if ($key === array_key_first($results['race'])) {
                                                    $a = "active";
                                                } else {
                                                    $a = "";
                                                }
                                                $tab = url_title("result-".$race_result['text']);
                                                ?>
                                                <li class="nav-item">
                                                    <a class="nav-link <?= $a; ?> show" id="<?= $tab . "-tab"; ?>" data-toggle="tab" href="#<?= $tab; ?>" role="tab" aria-controls="<?= $tab; ?>" aria-selected="true"><?= $race_result['text']; ?></a>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                        <div class="tab-content" id="resultsTabContent">
                                            <?php
                                            foreach ($results['race'] as $key => $race_result) {
                                                if ($key === array_key_first($results['race'])) {
                                                    $a = "active";
                                                } else {
                                                    $a = "";
                                                }
                                                $tab = url_title("result-".$race_result['text']);
                                                $race_id = $race_result['race_id'];
                                                $race_info = $race_list[$race_id];
                                                ?>
                                                <div class="tab-pane fade <?= $a; ?> show" id="<?= $tab; ?>" role="tabpanel" aria-labelledby="<?= $tab . "-tab"; ?>">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <h3 class="text-uppercase m-b-0"><span class="badge badge-<?= $race_info['race_color']; ?>"><?= round($race_info['race_distance'], 0); ?>km</span></h3>
                                                            <h4 class="text-uppercase m-b-0"><?= $race_info['edition_name']; ?></h4>
                                                            <h5 class="text-uppercase" style="color: #999;"><?= fdateHumanFull($race_info['edition_date'], true); ?></h5>
                                                        </div>
                                                        <div class="col-md-4 text-right">
                                                            <a href="<?= $race_result['url']; ?>" class="btn btn-light">
                                                                <i class="fa fa-file-excel"></i> Download Result File</a>
                                                        </div>
                                                    </div>
                                                    <hr >
                                                    <?php
                                                    if (isset($result_list[$race_id])) {

                                                        $template = array(
                                                            'table_open' => '<table class="datatable table table-striped table-bordered table-hover table-sm result_table">',
                                                        );

                                                        $this->table->set_template($template);
                                                        $this->table->set_heading('Pos', 'Name', 'Surname', 'Club', 'Age', 'Cat', 'Time');
                                                        foreach ($result_list[$race_id] as $result_id => $result) {
                                                            $url = base_url("result/claim/" . $result_id);
                                                            $ref = "href='' data-href='' data-toggle='modal' data-target='#confirm-claim-" . $result_id . "'";
                                                            $row = [
                                                                "<a $ref>$result[result_pos]</a>",
                                                                "<a $ref>$result[result_name]</a>",
                                                                "<a $ref>$result[result_surname]</a>",
                                                                "<a $ref>$result[result_club]</a>",
                                                                "<a $ref>$result[result_age]</a>",
                                                                "<a $ref>$result[result_cat]</a>",
                                                                "<a $ref>$result[result_time]</a>",
                                                            ];
                                                            $this->table->add_row($row);
                                                        }
                                                        echo $this->table->generate();
                                                    } else {
                                                        ?>
                                                        <p>No listed results available for the selected race. Please download the results file by clicking on the button above.</p>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
//                                    wts($result_list);
                                } else {
                                    // old school race file load without data in the db
                                    ?>
                                    <div class="content col-lg-12">
                                        <div role="alert" class="m-t-10 m-b-20 alert alert-<?= $status_notice['state']; ?>">
                                            <i class="fa fa-<?= $status_notice['icon']; ?>"></i> <b>RESULTS LOADED</b>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                foreach ($results['race'] as $race_result) {
                                                    ?>
                                                    <a href="<?= $race_result['url']; ?>" class="btn btn-light"><i class="fa fa-<?= $race_result['icon']; ?>"></i>
                                                        <?= $race_result['text']; ?></a>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
//                            wts($results['race']);
//                                        wts($result_list);
                        } elseif (isset($results['edition'])) {
                            // old school edition file loaded results
                            ?>
                            <div class="content col-lg-12">
                                <div role="alert" class="m-t-10 m-b-20 alert alert-<?= $status_notice['state']; ?>">
                                    <i class="fa fa-<?= $status_notice['icon']; ?>"></i> <b>RESULTS LOADED</b>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        foreach ($results['edition'] as $edition_result) {
                                            ?>
                                            <a href="<?= $edition_result['url']; ?>" class="btn btn-light"><i class="fa fa-<?= $edition_result['icon']; ?>"></i>
                                                <?= $edition_result['text']; ?></a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }

                        break;
                    case 12:
                        // no results expected
                        ?>
                        <div class="content col-lg-9">
                            <div role="alert" class="m-t-10 m-b-20 alert alert-<?= $status_notice['state']; ?>">
                                <i class="fa fa-<?= $status_notice['icon']; ?>"></i> <b><?= $status_notice['msg']; ?></b>
                            </div>
                            <a class="btn btn-light" href="<?= base_url('event/' . $slug . '/contact'); ?>"><i class="fa fa-envelope"></i>
                                Contact race organisers</a>
                        </div>
                        <!-- Sidebar-->
                        <div class="sidebar col-lg-3">  
                            <?php
                            // ADS WIDGET
                            $this->load->view('widgets/side_ad');
                            ?>
                        </div>
                        <!-- end: Sidebar-->
                        <?php
                        break;
                    default:
                        ?>
                        <div class="content col-lg-12">
                            <div role="alert" class="m-t-10 m-b-20 alert alert-danger">
                                <i class="fa fa-minus-circle"></i> <b>No results available for this event</b>
                            </div>
                            <p><a class="btn btn-light" href="<?= base_url('event/' . $slug . '/contact'); ?>"><i class="fa fa-envelope"></i>
                                    Contact race organisers</a></p>
                        </div>
                        <?php
                        break;
                }
            }
            ?>
        </div>
        <!-- end: Content-->
    </div>
</div>
</section>
<!-- end: Shop products -->

