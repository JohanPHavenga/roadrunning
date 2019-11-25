<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2>How to Enter</h2>
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
                        if (in_array(5, $edition_data['entrytype_list'])) {
                            echo "<p>No details regarding the entries for this race has been published yet.</p>";
                        } else {
                            if (isset($edition_data['entrytype_list'][4]) && $url_list[5]) {
                                ?>
                                <p>
                                    <a href="<?= $url_list[5][0]['url_name']; ?>" class="btn btn-light btn-creative btn-icon-holder btn-shadow btn-light-hover">Enter online
                                    <i class="fa fa-arrow-right"></i></a>
                                </p>
                                <?php
                            }
                            ?>
                            <ul>
                                <?php
                                // Online entries
                                if (isset($edition_data['entrytype_list'][4])) {
                                    if ($url_list[5]) {
                                        echo "<li>Online entries close on <b>" . fdateHumanFull($date_list[3][0]['date_end'], true) . "</b></li>";
                                    } else {
                                        echo "<li>Online entries to <b>open soon</b></li>";
                                    }
                                } else {
                                    echo "<li class='text-danger'>No online entries available</li>";
                                }

                                // OTD entries
                                if (isset($edition_data['entrytype_list'][1])) {
                                    echo "<li>Entries will be taken <span class='text-danger'><b>on the day</b></span> from <b>" .
                                    ftimeMil($date_list[6][0]['date_start']);
                                    if (!time_is_midnight($date_list[6][0]['date_end'])) {
                                        echo " - " . ftimeMil($date_list[6][0]['date_end']);
                                    }
                                    echo "</b></li>";
                                } else {
                                    echo "<li class='text-danger'>No entrires avaialble on race day</li>";
                                }

                                // Manual entries
                                if (isset($edition_data['entrytype_list'][2])) {
                                    if (!empty($date_list[5][0]['venue_name'])) {
                                        echo "<li>Pre-Entries can also be completed at " . $date_list[5][0]['venue_name'] . "</li>";
                                    }
                                    echo "<li>Closing date for manual pre-entries is <u>" . fdateHumanFull($date_list[5][0]['date_end'], true, true) . "</u></li>";
                                }

                                // PRE entries
                                if (isset($edition_data['entrytype_list'][3])) {
                                    echo "<li><b>Entries will be taken on:</b><ul>";
                                    foreach ($date_list[4] as $date) {
                                        echo "<li>" . fdateHumanFull($date['date_start'], true, true) . "-" . ftimeMil($date['date_end']) . " @ " . $date['venue_name'] . "</li>";
                                    }
                                    echo "</ul></li>";
                                }


                                // OTD entries for Fun Run
                                if ($edition_data['edition_entry_funrun_otd']) {
                                    foreach ($race_list as $race) {
                                        if ($race['race_distance'] < 10) {
                                            echo "<li>Entries for the " . $race['race_name'] . " will be taken on the day</li>";
                                        }
                                    }
                                }

                                // ENTRY LIMIT
                                if (!empty($edition_data['edition_entry_limit'])) {
                                    echo "<li><strong>NOTE</strong> that the entry limit for this event is <u>" . $edition_data['edition_entry_limit'] . " entrants</u></li>";
                                }

                                // ADMIN FEES
                                if (!empty($edition_data['edition_admin_fee'])) {
                                    echo "<li>An admin fee of <strong>" . $edition_data['edition_admin_fee'] . "</strong> will be levied for " . $edition_data['edition_admin_option'] . " entries</li>";
                                }

                                // Entries Non-refundable
                                if ($edition_data['edition_entry_nonrefund']) {
                                    echo "<li>Entry fees are non-refundable</li>";
                                }

                                // BULK entries
                                if ($edition_data['edition_entry_bulk']) {
                                    echo "<li>For bulk entries (5+) please contact the organisers: "
                                    . "<a href='mailto:" . $edition_data['user_email'] . "?subject=Bulk entries for " . $edition_data['edition_name'] . "' class='link'>"
                                    . $edition_data['user_email'] . "</a></li>";
                                }

                                // Subsitutions
                                if ($edition_data['edition_entry_nosubstitution']) {
                                    echo "<li><strong>No substitutions</strong>";
                                    if (strtotime($date_list[7][0]['date_end']) < strtotime($edition_data['edition_date'])) {
                                        echo " after " . fdateHumanFull($date_list[7][0]['date_end'], true, true);
                                    }
                                    echo "</li>";
                                }

                                // Up/Downgrades
                                if ($edition_data['edition_entry_nodowngrade']) {
                                    echo "<li><strong>No up- or downgrades will be entertained</strong>";
                                    if (strtotime($date_list[8][0]['date_end']) < strtotime($edition_data['edition_date'])) {
                                        echo " after " . fdateHumanFull($date_list[8][0]['date_end'], true, true);
                                    }
                                    echo "</li>";
                                }

                                // TSHIRT FEES
                                if ($edition_data['edition_tshirt_amount'] > 0) {
                                    echo "<li>An event <strong>T-Shirt</strong> is available for purchase as part of the entry process for <strong>R" . $edition_data['edition_tshirt_amount'] . "</strong></li>";
                                    if (!empty($edition_data['edition_tshirt_text'])) {
                                        echo "<li>" . $edition_data['edition_tshirt_text'] . "</li>";
                                    }
                                }
                                ?>
                            </ul>
                            <?php
                            // always show what is in the box
                            if (strlen($edition_data['edition_entry_detail']) > 15) {
                                echo $edition_data['edition_entry_detail'];
                            }
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
                $data_to_widget['title'] = "Receive race notification";
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