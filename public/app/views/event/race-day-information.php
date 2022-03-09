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

                <!-- ad box -->
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
                        if (!in_array(3, $edition_data['regtype_list'])) {
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-uppercase">Registration / Number Collection</h3>
                                    <ul>
                                        <?php
                                        // OTD Reg
                                        if (isset($edition_data['regtype_list'][1])) {
                                            echo "<li>Registration will take place <b>on the day</b> from <b>" .
                                            ftimeMil($date_list[9][0]['date_start']);
                                            if (!time_is_midnight($date_list[9][0]['date_end'])) {
                                                echo " - " . ftimeMil($date_list[9][0]['date_end']);
                                            }
                                            echo "</b></li>";
                                        } else {
                                            echo "<li class='red em'>No number collection on race day</li>";
                                        }

                                        // PRE Reg
                                        if (isset($edition_data['regtype_list'][2])) {
                                            echo "<li><b>Registration / Number collection will take place on:</b><ul>";
                                            foreach ($date_list[10] as $date) {
                                                echo "<li>" . fdateHumanFull($date['date_start'], true, true) . "-" . ftimeMil($date['date_end']) . " @ " . $date['venue_name'] . "</li>";
                                            }
                                            echo "</ul></li>";
                                        }
                                        ?>
                                    </ul>
                                    <?php
                                    // always show what is in the box
                                    if (strlen($edition_data['edition_reg_detail']) > 10) {
                                        echo $edition_data['edition_reg_detail'];
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>


                        <?php
                        if (
                                (strlen($edition_data['edition_general_detail']) > 10) ||
                                ($edition_data['edition_info_medals']) ||
                                ($edition_data['edition_info_togbag']) ||
                                ($edition_data['edition_info_headphones']) ||
                                ($edition_data['edition_info_prizegizing'] != "00:00:00")
                        ) {
                            ?>
                            <h3 class="text-uppercase">GENERAL INFORMATION</h3>
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
                                } elseif (!empty($edition_data['edition_info_medals_text'])) {
                                    echo "<li><b>HANDOUTS:</b> " . $edition_data['edition_info_medals_text'];
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
                                    echo "<li><b>WALKERS:</b> Walkers are welcome</li>";
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
                            <p class='text-danger'><b>No more information</b> regarding this race has been released yet.</p>
                            <p>Do you want to get notified once information is released? Enter your email below or to the right to be added to the mailing list.</p>
                            <?php
                        }
                        if ($edition_data['edition_status'] != 17) {
                            ?>
                            <h3 class="text-uppercase">Map</h3>
                            <iframe
                                width="100%"
                                height="350"
                                frameborder="0" style="border:0; margin-bottom: 10px;"
                                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBERO5xjCtTOmjQ_zSSUvlp5YN_l-4yKQw&q=<?= $address_nospaces; ?>" allowfullscreen>
                            </iframe>
                            <p>
                                <a href="https://www.google.com/maps/search/?api=1&query=<?= $edition_data['edition_address'] . "," . $edition_data['town_name']; ?>" class="btn btn-light">
                                    <i class="fa fa-map"></i> Get Directions</a>
                                <?php
                                // If not in the past
                                if (!$in_past) {
                                    ?>
                                    <a href="<?= base_url("event/" . $slug . "/accommodation"); ?>" class="btn btn-light"><i class="fa fa-bed"></i> Find Accommodation</a>
                                    <?php
                                }
                                ?>
                            </p>
                            <?php
                        }
                        ?>
                    </div>

                    <!-- end: Product additional tabs -->
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        if (isset($flyer['edition'])) {
                            ?>
                            <a href="<?= $flyer['edition']['url']; ?>" class="btn btn-default">
                                <i class="fa fa-<?= $flyer['edition']['icon']; ?>"></i> <?= $flyer['edition']['text']; ?></a>

                            <?php
                        }
                        if (isset($entry_form['edition'])) {
                            ?>
                            <a href="<?= $entry_form['edition']['url']; ?>" class="btn btn-light">
                                <i class="fa fa-<?= $entry_form['edition']['icon']; ?>"></i> <?= $entry_form['edition']['text']; ?></a>

                            <?php
                        }
                        // website link
                        if (isset($url_list[1])) {
                            ?>
                            <a href="<?= $url_list['1'][0]['url_name']; ?>" class="btn btn-light">
                                <i class="fa fa-link"></i> Race Website</a>

                            <?php
                        }
                        // press release
                        if (isset($file_list[10])) {
                            ?>
                            <a href="<?= base_url("file/edition/" . $slug . "/press release/" . $this->data_to_views['file_list'][10][0]['file_name']); ?>" class="btn btn-light">
                                <i class="fa fa-file-pdf"></i> Official Press Release</a>

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