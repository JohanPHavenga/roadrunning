<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2>Results</h2>
            </div>
        </div>
        <div class="row m-b-20">
            <div class="col-lg-12">
                <span class="post-meta"><i class="fa fa-running"></i> <?= $edition_data['edition_name']; ?> </span>
                <span class="post-meta"><i class="fa fa-calendar"></i> <?= fdateHumanFull($edition_data['edition_date'], false); ?></span>
                <span class="post-meta"><i class="fa fa-clock"></i> 
                    <?php
                    echo ftimeSort($edition_data['race_summary']['times']['start']);
                    if ($edition_data['race_summary']['times']['end']) {
                        echo " - " . ftimeSort($edition_data['race_summary']['times']['end']);
                    }
                    ?>
                </span>
                <span class="post-meta"><a href="https://www.google.com/maps/search/?api=1&query=<?= $edition_data['edition_gps']; ?>"><i class="fa fa-map-marker"></i> <address><?= $address; ?></address></a></span>

            </div>
        </div>
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
                        // check for results
                        if ((!isset($this->data_to_views['url_list'][4])) && (!isset($this->data_to_views['file_list'][4]))) {
                            ?>
                            <p class='text-danger'><b>No results</b> are available for this race yet.</p>
                            <p>Want to get notified once results are loaded? Enter your email below or to the right.</p>
                            <?php
                        } else {
                            ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-light"><i class="fa fa-file-excel"></i>
                                        Download Race Results</button>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                    </div>

                    <!-- end: Product additional tabs -->
                </div>

            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                // SUBSCRIBE WIDGET
                $data_to_widget['title'] = "Get notified when entries open";
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