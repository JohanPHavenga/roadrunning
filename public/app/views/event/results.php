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
            <div class="content col-lg-9">

                <!-- add box -->
                <div class="row m-b-30">
                    <div class="col-lg-12">
                        <div style='height: 90px; width: 100%; background: #ccc;'>Ad</div>
                    </div>
                </div>

                <div class="row m-b-40">
                    <div class="col-lg-12">
                        <?php
                        $mailing_list_notice = "<p>If you would like to be notified once results are loaded, "
                                . "please enter your email below or to the right to be added to the "
                                . "<a href='" . base_url('event/' . $slug . '/subscribe') . "' title='Add yourself to the mailing list'>mailing list</a> for this race.</p>";
                        // if date in future
                        if (!$in_past) {
                            $days_from_now = date("d", strtotime($edition_data['edition_date']) - time());
                            ?>
                            <p><b class='text-danger'>No results available yet.</b><br>
                                This race is scheduled to take place on 
                                <b><?= fdateHumanFull($edition_data['edition_date']); ?></b>, 
                                <?= $days_from_now; ?> days from now.</p>
                            <p>Once the race has run, depending on the timing method used, results can take <u>up to 7 working days</u> to be released.
                                As soon as results are published by the organisers I will load it here.</p>                            
                            <?php
                            echo $mailing_list_notice;
                        } else {
                            $days_ago = date("d", time() - strtotime($edition_data['edition_date']));
                            switch ($edition_data['edition_info_status']) {
                                case 10:
                                    // pending
                                    ?>
                                    <div role="alert" class="m-b-30 alert alert-<?= $status_notice['state']; ?>">
                                        <i class="fa fa-<?= $status_notice['icon']; ?>"></i> <b><?= $status_notice['msg']; ?></b>
                                    </div>
                                    <p class="text-danger"><b>Race was ran <?= $days_ago; ?> day(s) ago.</b></p>
                                    <p><b>Please note:</b> Results can take <u>up to 7 working days</u> to be released. 
                                        As soon as results are published by the organisers I will load it here.</p>
                                    <?php
                                    echo $mailing_list_notice;
                                    break;
                                case 11;
                                    // loaded
                                    ?>
                                    <div role="alert" class="m-b-30 alert alert-<?= $status_notice['state']; ?>">
                                        <i class="fa fa-<?= $status_notice['icon']; ?>"></i> <b>Results has been loaded</b></div>
                                    <?php
                                    if (isset($results['edition'])) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="<?= $results['edition']['url']; ?>" class="btn btn-light"><i class="fa fa-<?= $results['edition']['icon']; ?>"></i>
                                                    <?= $results['edition']['text']; ?></a>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    if (isset($results['race'])) {
                                        ?>
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
                                        <?php
                                    }
//                                    wts($results);
                                    break;
                                case 12:
                                    // no results expected
                                    ?>
                                    <div role="alert" class="m-b-30 alert alert-<?= $status_notice['state']; ?>">
                                        <i class="fa fa-<?= $status_notice['icon']; ?>"></i> <b><?= $status_notice['msg']; ?></b>
                                    </div>
                                    <a class="btn btn-light" href="<?= base_url('event/' . $slug . '/contact'); ?>"><i class="fa fa-envelope"></i>
                                        Contact race organisers</a>
                                    <?php
                                    break;
                                default:
                                    ?>
                                    <p>No results available</p>
                                    <?php
                                    break;
                            }
                        }
                        ?>

                    </div>

                    <!-- end: Product additional tabs -->
                </div>
                <?php
//                echo $edition_data['edition_info_status'];
//                wts($edition_data);
                ?>
            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                // SUBSCRIBE WIDGET
                $data_to_widget['title'] = "Get notified when results are loaded";
                $this->load->view('widgets/subscribe', $data_to_widget);

                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<!-- end: Shop products -->

