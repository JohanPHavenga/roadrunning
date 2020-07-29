<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2><?= $edition_data['edition_name']; ?> Race Results</h2>
            </div>
        </div>
        <?php
        $this->load->view('widgets/race_meta');
        ?>      
        <div class="row m-t-30">
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
                    <p><b class='text-danger'>No results available yet.</b></p>
                    <p>
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
                $days_ago = convert_seconds(time() - strtotime($edition_data['edition_date']));
                if ($days_ago > 1) {
                    $d = "days";
                } else {
                    $d = "day";
                }

                switch ($edition_data['edition_info_status']) {
                    case 10:
                        // pending
                        ?>
                        <div class="content col-lg-9">
                            <div role="alert" class="m-t-10 m-b-20 alert alert-<?= $status_notice['state']; ?>">
                                <i class="fa fa-<?= $status_notice['icon']; ?>"></i> <b><?= $status_notice['msg']; ?></b>
                            </div>
                            <p class="text-danger"><b>Race was ran <?= $days_ago; ?> <?= $d; ?> ago.</b></p>
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
                        // add time provider info
                        $this->load->view('widgets/timingprovider');
                        // loaded
                        if (isset($results['race'])) {
                            ?>
                            <div class="content col-lg-12 m-t-30">
                                <?php
                                if ($result_list) {
                                    ?>
                                    <div class="row pricing-table colored">
                                        <?php
                                        foreach ($results['race'] as $racetype_abbr => $racetype_list) {

                                            foreach ($racetype_list as $dist => $race_result) {
//                                                wts($race_result);
                                                $url = base_url("event/" . $slug . "/results/" . $dist . "/" . $racetype_abbr);
                                                ?>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                                    <div class="plan featured">
                                                        <div class="plan-header">
                                                            <h4><?= str_replace("Results", "", $race_result['text']); ?></h4>
                                                            <p class="text-muted">Results</p>
                                                            <div class="plan-price"><sup></sup><?= $race_result['distance']; ?><span>km</span> </div>
                                                            <a class="btn btn-light btn-light btn-light-hover" href="<?= $url; ?>"><span><i class="fa fa-list-alt"></i> View</span></a> 
                                                        </div>  

                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?php
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
        <div class="row">
            <div class="col-lg-12">
                <a href="<?= base_url("event/" . $slug); ?>" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Back</a>
            </div>
        </div>
        <!-- end: Content-->
    </div>
</div>
</section>
<!-- end: Shop products -->