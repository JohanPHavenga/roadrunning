<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2>Race day information</h2>
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
                        if ((strlen($edition_data['edition_general_detail']) > 10) || ($edition_data['edition_info_medals'])) {
                            ?>
                            <ul>
                                <?php
                                // MEDALS
                                if ($edition_data['edition_info_medals']) {
                                    echo "<li><b>MEDALS:</b> ";
                                    if (!empty($edition_data['edition_info_medals_text'])) {
                                        echo $edition_data['edition_info_medals_text'];
                                    } else {
                                        echo "Medals will be awarded to all finishers within cut-off times";
                                    }
                                    echo "</li>";
                                }

                                // PRIZE-GIVING
                                if (!is_null($edition_data['edition_info_prizegizing'])) {
                                    if ($edition_data['edition_info_prizegizing'] != "00:00:00") {
                                        echo "<li><b>PRIZE-GIVING:</b> Scheduled to start at " . ftimeMil($edition_data['edition_info_prizegizing']) . "</li>";
                                    }
                                }

                                // LUCKY DRAWS
                                if ($edition_data['edition_info_luckydraw']) {
                                    echo "<li><b>LUCKY DRAWS:</b> Many lucky draws will be available</li>";
                                }

                                // TOG BAG
                                if ($edition_data['edition_info_togbag']) {
                                    echo "<li><b>TOG BAG:</b> Tog bag facilities will be available, used at own risk</li>";
                                }

                                // REFRESHMENTS
                                if ($edition_data['edition_info_refreshments']) {
                                    echo "<li><b>REFRESHMENTS:</b> Refreshments will be on sale at the event</li>";
                                }

                                // SOCIAL WALKERS
                                if ($edition_data['edition_info_socialwalkers']) {
                                    echo "<li><b>SOCIAL WALKERS:</b> Social walkers are welcome</li>";
                                }

                                // HEADPHONES
                                if ($edition_data['edition_info_headphones']) {
                                    echo "<li><b>HEADPHONES:</b> The use of music players with headphones is not allowed and may result in disqualification</li>";
                                }
                                ?>
                            </ul>
                            <?php
                            // GENERAL INFORMATION
                            echo $edition_data['edition_general_detail'];
                        } else {
                            ?>
                            <p class='text-info'><b>No information</b> regarding this race has been released yet.</p>
                            <p>Want to get notified once information is loaded? Enter your email below or to the right.</p>
                            <?php
                        }
                        ?>
                        <h4 class="text-uppercase">Map</h4>
                        <iframe
                            width="100%"
                            height="350"
                            frameborder="0" style="border:0; margin-bottom: 10px;"
                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBERO5xjCtTOmjQ_zSSUvlp5YN_l-4yKQw&q=<?= $address_nospaces; ?>" allowfullscreen>
                        </iframe>
                        <p>
                            <a href="https://www.google.com/maps/search/?api=1&query=<?= $edition_data['edition_address'] . "," . $edition_data['town_name']; ?>" class="btn btn-light">
                                <i class="fa fa-map"></i> Get Directions</a></p>
                    </div>

                    <!-- end: Product additional tabs -->
                </div>

            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
<?php
// SUBSCRIBE WIDGET
$data_to_widget['title'] = "Want to get notified when info is made available?";
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