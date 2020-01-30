<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2>Route Maps</h2>
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
                        if (empty($route_maps)) {
                            $mailing_list_notice = "<p>If you would like to be notified once route maps are loaded, "
                                    . "please enter your email below or to the right to be added to the "
                                    . "<a href='" . base_url('event/' . $slug . '/subscribe') . "' title='Add yourself to the mailing list'>mailing list</a> for this race.</p>";
                            if (!$in_past) {
                                $msg = "No Route Maps has been made available for this race yet";
                            } else {
                                $msg = "No Route Maps were made available for this race";
                            }
                            ?>
                            <div role="alert" class="m-b-30 alert alert-warning">
                                <i class="fa fa-info-circle"></i> <b><?= $msg; ?></b></div>
                            <?php
                            if (!$in_past) {
                                ?>
                                <p>
                                    <?= $mailing_list_notice; ?>
                                </p>
                                <p>
                                    <a href="<?= base_url("event/" . $edition_data['edition_slug'] . "/contact"); ?>" class="btn btn-light">
                                        <i class="fa fa-envelope-open" aria-hidden="true"></i>&nbsp;Contact Race Organisers</a>

                                    <a href="<?= base_url("event/" . $edition_data['edition_slug'] . "/accommodation"); ?>" class="btn btn-light">
                                        <i class="fa fa-bed"></i> Get Accommodation</a>

                                </p>
                                <?php
                            }
                            ?>
                            <?php
                        } else {
                            ?>
                            <div role="alert" class="m-b-30 alert alert-success">
                                <i class="fa fa-<?= $status_notice['icon']; ?>"></i> <b>Route Maps has been loaded! Click below to view</b></div>
                            <?php
                            if (isset($route_maps['edition'])) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="<?= $route_maps['edition']['url']; ?>" class="btn btn-light">
                                            <i class="fa fa-<?= $route_maps['edition']['icon']; ?>"></i> <?= $route_maps['edition']['text']; ?></a>
                                    </div>
                                </div>
                                <?php
                            }
                            if (isset($route_maps['race'])) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        foreach ($route_maps['race'] as $race_map) {
                                            ?>
                                            <a href="<?= $race_map['url']; ?>" class="btn btn-light"><i class="fa fa-<?= $race_map['icon']; ?>"></i>
                                                <?= $race_map['text']; ?></a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
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
                $data_to_widget['title'] = "Get notified when more information gets loaded";
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

