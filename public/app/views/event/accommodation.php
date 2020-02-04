<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2>Accommodation Options</h2>
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
                        <?php
                        // LANDSCAPE ADS WIDGET
                        $this->load->view('widgets/horizontal_ad');
                        ?>
                    </div>
                </div>

                <div class="row m-b-40">
                    <div class="col-lg-12">
                        <?php
                        if ($in_past) {
                            ?>
                            <p>This race has already taken place. No accommodation options available in the past.</p>
                            <?php
                        } else {
                            ?>
                            <p>Use the interactive map below to find accommodation close to the race.</p>
                            <iframe src="https://www.stay22.com/embed/gm?aid=roadrunning&lat=<?= $gps['lat']; ?>&lng=<?= $gps['long']; ?>&checkin=<?= $edition_date_minus_one; ?>&maincolor=26B8F3&venue=<?= $edition_data['edition_address']; ?>" id="stay22-widget" width="100%" height="560" frameborder="0"></iframe>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<!-- end: Shop products -->