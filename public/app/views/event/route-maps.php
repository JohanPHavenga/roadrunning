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

                <!-- ad box -->
                <div class="row m-b-30">
                    <div class="col-lg-12">
                        <?php
                        // LANDSCAPE ADS WIDGET
                        $this->load->view('widgets/horizontal_ad');
                        ?>
                    </div>
                </div>
                <?php
                // ROUTE MAP CONTENT
                $this->load->view('event/content/route-maps');
                ?>
                <!-- end: Content-->
                <?php
                if (!$in_past) {
                ?>
                    <p>
                        <a href="<?= base_url("event/" . $edition_data['edition_slug'] . "/contact"); ?>" class="btn btn-light">
                            <i class="fa fa-envelope-open" aria-hidden="true"></i>&nbsp;Contact Race Organisers</a>

                        <a href="<?= base_url("event/" . $edition_data['edition_slug'] . "/accommodation"); ?>" class="btn btn-light">
                            <i class="fa fa-bed"></i> Get Accommodation</a>

                    </p>
                <?php
                }
                ?>
            </div>

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